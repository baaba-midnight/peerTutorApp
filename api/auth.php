<?php
// Error handling - must be at the very top
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Handle any errors and convert to JSON response
function handleFatalErrors() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Server error: ' . $error['message']
        ]);
        exit;
    }
}
register_shutdown_function('handleFatalErrors');

// Now include your database connection
require_once '../config/database.php'; // Adjust path as needed

// Always set JSON header
header('Content-Type: application/json');

// Helper: respond with JSON
function respond($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Sanitize function
function clean($data) {
    return htmlspecialchars(trim($data));
}

// Check for POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method');
}

try {
    // Debug: log received data
    error_log("Registration request received: " . print_r($_POST, true));
    error_log("Files received: " . print_r($_FILES, true));

    // Validate input data exists
    $requiredFields = ['firstName', 'lastName', 'email', 'username', 'password', 'selectedRole'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            respond(false, "Missing required field: $field");
        }
    }

    // Check subjects array
    if (!isset($_POST['subjects']) || !is_array($_POST['subjects']) || empty($_POST['subjects'])) {
        respond(false, 'Please select at least one subject');
    }

    // Get and sanitize input data
    $firstName = clean($_POST['firstName']);
    $lastName = clean($_POST['lastName']);
    $email = clean($_POST['email']);
    $phone = isset($_POST['phone']) ? clean($_POST['phone']) : '';
    $username = clean($_POST['username']);
    $password = $_POST['password']; // Will be hashed, no need to clean
    $role = clean($_POST['selectedRole']);
    $subjects = $_POST['subjects'];
    $experience = isset($_POST['experience']) ? clean($_POST['experience']) : null;

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Invalid email format');
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM Users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        respond(false, 'Email or username already exists');
    }

    // Password validation
    if (strlen($password) < 8 || !preg_match('/[0-9]/', $password)) {
        respond(false, 'Password must be at least 8 characters long and contain at least one number');
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Begin transaction
    $pdo->beginTransaction();

    // Handle avatar upload
    $avatarPath = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['avatar']['type'];
        
        if (!in_array($fileType, $allowed)) {
            respond(false, 'Invalid file type. Only JPG, PNG and GIF are allowed.');
        }
        
        // Create directory if it doesn't exist
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatarFilename = uniqid() . '.' . $ext;
        $avatarPath = 'uploads/' . $avatarFilename;
        
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../../' . $avatarPath)) {
            throw new Exception('Failed to upload avatar');
        }
    }

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO Users (username, password, email, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$username, $passwordHash, $email, $role]);
    $userId = $pdo->lastInsertId();

    // Insert profile
    $stmt = $pdo->prepare("INSERT INTO Profiles (user_id, first_name, last_name, phone, avatar) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $firstName, $lastName, $phone, $avatarPath]);

    // Insert tutor profile if applicable
    if ($role === 'tutor' && $experience !== null) {
        $stmt = $pdo->prepare("INSERT INTO TutorProfiles (user_id, experience) VALUES (?, ?)");
        $stmt->execute([$userId, $experience]);
    }

    // Insert interests
    foreach ($subjects as $subject) {
        $cleanSubject = clean($subject);
        $stmt = $pdo->prepare("INSERT INTO UserInterests (user_id, subject_name) VALUES (?, ?)");
        $stmt->execute([$userId, $cleanSubject]);
    }

    // Commit transaction
    $pdo->commit();
    
    respond(true, 'Registration successful');
} catch (PDOException $e) {
    // If a transaction is active, roll it back
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("PDO Error: " . $e->getMessage());
    respond(false, 'Database error: ' . $e->getMessage());
} catch (Exception $e) {
    // If a transaction is active, roll it back
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("General Error: " . $e->getMessage());
    respond(false, 'Server error: ' . $e->getMessage());
}