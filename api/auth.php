<?php
// Prevent PHP from outputting errors as HTML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Set up error handling to capture errors and output them as JSON
function handleError($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $errstr,
        'details' => "Error in $errfile on line $errline"
    ]);
    exit;
}
set_error_handler('handleError');

// Handle exceptions
function handleException($exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server exception: ' . $exception->getMessage(),
        'details' => $exception->getTraceAsString()
    ]);
    exit;
}
set_exception_handler('handleException');

require_once('../config/database.php');
require_once('../config/session.php');

header('Content-Type: application/json');

// Get database connection
$conn = getConnection();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session
    session_start();
    session_unset();
    session_destroy();
    
    // Clear session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    
    // Redirect to login page
    header('Location: ../views/auth/login.php');
    exit;
}

// Handle registration requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    // Get form data
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : '';
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || 
        empty($username) || empty($password) || empty($role)) {
        http_response_code(400);
        echo json_encode(['error' => 'All required fields must be filled']);
        exit;
    }
    
    // Check if email or username already exists
    $sql = "SELECT * FROM Users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Email or username already exists']);
        exit;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Handle file upload if present
    $avatarPath = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = '../uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath)) {
            $avatarPath = 'uploads/avatars/' . $fileName;
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert user
        $sql = "INSERT INTO Users (first_name, last_name, email, phone, username, password_hash, role, profile_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss', $firstName, $lastName, $email, $phone, $username, $hashedPassword, $role, $avatarPath);
        $stmt->execute();
        
        $userId = $conn->insert_id;
        
        // Handle subjects
        if (!empty($subjects)) {
            $subjectArray = explode(',', $subjects);
            foreach ($subjectArray as $subject) {
                $subject = trim($subject);
                if (!empty($subject)) {
                    $sql = "INSERT INTO UserSubjects (user_id, subject) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('is', $userId, $subject);
                    $stmt->execute();
                }
            }
        }
        
        // Additional tutor info if applicable
        if ($role === 'tutor' && !empty($experience)) {
            $sql = "INSERT INTO TutorProfiles (user_id, experience) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $userId, $experience);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
    
    exit;
}

// Handle login requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    // Check if the request contains JSON data
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    
    if (strpos($contentType, 'application/json') !== false) {
        // Parse JSON input
        $jsonData = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($jsonData['email']) || !isset($jsonData['password']) || !isset($jsonData['role'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email, password and role are required']);
            exit;
        }
        
        $email = filter_var($jsonData['email'], FILTER_SANITIZE_EMAIL);
        $password = $jsonData['password'];
        $role = $jsonData['role'];
    } else {
        // Handle traditional form data
        if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['role'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email, password and role are required']);
            exit;
        }
        
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $role = $_POST['role'];
    }
    
    // Verify credentials
    $sql = "SELECT * FROM Users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    if (!password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    
    // Start session and store user data
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
    
    echo json_encode([
        'message' => 'Login successful',
        'user' => [
            'id' => $user['user_id'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'role' => $user['role']
        ]
    ]);
}