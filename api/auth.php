<?php
// Add proper error handling and headers
header('Content-Type: application/json');
session_start();

// Include database connection
require_once '../config/database.php';

// Process JSON input for login requests
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);
    
    if(is_array($decoded)) {
        // Assign to $_POST so we can use the same code structure
        $_POST = $decoded;
    }
}

// Determine if this is a registration or login request
$isRegistration = isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['username']);
$isLogin = isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role']) && !$isRegistration;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLogin) {
    try {
        // Get the login data
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $role = $_POST['role'];
        
        // Check email exists
        $stmt = $pdo->prepare("SELECT user_id, username, password_hash, role FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            // Email not found
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid email or password'
            ]);
            exit;
        }
        
        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            // Password incorrect
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid email or password'
            ]);
            exit;
        }
        
        // Check if role matches
        if ($user['role'] !== $role) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => "This account is not registered as a $role. Please select the correct role."
            ]);
            exit;
        }
        
        // Create session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Return success
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['user_id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ]);
        
    } catch (PDOException $e) {
        // Database error
        error_log('Database Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        // Other errors
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
// Handle registration
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isRegistration) {
    try {
        // Collect form data
        $firstName = htmlspecialchars(trim($_POST['firstName']));
        $lastName = htmlspecialchars(trim($_POST['lastName']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : null;
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password']; // Will be hashed before storage
        $role = $_POST['selectedRole'];
        
        // Subjects handling - will handle this separately after user creation
        $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];
        
        // Experience (for tutors) - will store in bio field for now
        $bio = isset($_POST['experience']) ? htmlspecialchars(trim($_POST['experience'])) : null;
        
        // Handle profile picture upload
        $profileImage = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileInfo = pathinfo($_FILES['avatar']['name']);
            $extension = strtolower($fileInfo['extension']);
            
            // Validate file type
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedExtensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');
            }
            
            // Generate unique filename
            $avatarFilename = uniqid('avatar_') . '.' . $extension;
            $profileImage = 'uploads/avatars/' . $avatarFilename;
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $profileImage)) {
                throw new Exception('Failed to upload profile picture.');
            }
        }
        
        // Validate email not already in use
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Email address already in use.');
        }
        
        // Validate username not already in use
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Username already in use.');
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database - using your actual table and column names
        $stmt = $pdo->prepare("
            INSERT INTO Users (
                username, email, password_hash, first_name, last_name, 
                role, profile_image, phone, bio
            ) VALUES (
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?
            )
        ");
        
        $stmt->execute([
            $username, $email, $passwordHash, $firstName, $lastName,
            $role, $profileImage, $phone, $bio
        ]);
        
        // Get the user_id of the newly created user
        $userId = $pdo->lastInsertId();
        
        // If the user is a tutor and has selected subjects, add them to TutorSubjects table
        if ($role === 'tutor' && !empty($subjects)) {
            foreach ($subjects as $subjectName) {
                // Find or create the subject
                $stmt = $pdo->prepare("SELECT subject_id FROM Subjects WHERE name = ?");
                $stmt->execute([$subjectName]);
                $subjectId = $stmt->fetchColumn();
                
                // If subject doesn't exist, create it
                if (!$subjectId) {
                    $stmt = $pdo->prepare("INSERT INTO Subjects (name) VALUES (?)");
                    $stmt->execute([$subjectName]);
                    $subjectId = $pdo->lastInsertId();
                }
                
                // Add to TutorSubjects with a default hourly rate
                $stmt = $pdo->prepare("
                    INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$userId, $subjectId, 20.00]); // Default rate of $20/hour
            }
        }
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful!',
            'user_id' => $userId
        ]);
        
    } catch (PDOException $e) {
        // Handle database errors
        error_log('Database Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        // Handle other exceptions
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    // Handle invalid request method or invalid data
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed or invalid data'
    ]);
}