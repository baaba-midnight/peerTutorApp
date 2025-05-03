<?php
require_once('../config/database.php');
require_once('../config/session.php');
require_once('../models/User.php'); // Include the User model

header('Content-Type: application/json');

// Get database connection 
$database = new Database();
$conn = $database->connect();
$user = new User($conn);

// Handle logout requests
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

// Handle POST requests (login or register)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only use $_POST and $_FILES, do not use JSON input
    $data = $_POST;
    
    // Check for action parameter
    $action = isset($data['action']) ? $data['action'] : '';
    
    // REGISTRATION
    if ($action === 'register') {
        // Validate required fields
        // if (empty($data['email']) || empty($data['password']) || empty($data['firstName']) || 
        //     empty($data['lastName']) || empty($data['selectedRole'])) {
        //     http_response_code(400);
        //     error_log('Required fields are not filled: ' . json_encode($data));
        //     // echo json_encode(['status' => 'error', 'message' => 'Required fields are not filled']);
        //     exit;
        // }
        
        // Extract registration data
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];
        $firstName = htmlspecialchars($data['firstName']);
        $lastName = htmlspecialchars($data['lastName']);
        $role = $data['selectedRole'];
        $phone = isset($data['phone']) ? htmlspecialchars($data['phone']) : null;
        
        // Optional profile data
        $bio = isset($data['experience']) ? htmlspecialchars($data['experience']) : null;
        $profile_picture_url = null;
        
        // Process profile picture if uploaded
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $uploadDir = '../../uploads/avatars/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['avatar']['name']);
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                $profile_picture_url = 'uploads/avatars/' . $fileName;
            }
        }

        // Process subjects if provided
        $subjects = isset($data['subjects']) ? $data['subjects'] : null;
        
        // Call the User model's register method
        $result = $user->register($firstName, $lastName, $email, $password, $role, $phone, $bio, $profile_picture_url, $subjects);
        
        if ($result['status'] === 'success') {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }
    // LOGIN
    else {
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
            exit;
        }
        
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];
        
        // Call the User model's login method
        $result = $user->login($email, $password);
        
        if ($result['status'] === 'success') {
            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $result['user']['user_id'];
            $_SESSION['role'] = $result['user']['role'];
            $_SESSION['name'] = $result['user']['full_name'];
            
            echo json_encode($result);
        } else {
            http_response_code(401);
            echo json_encode($result);
        }
    }
}
?>