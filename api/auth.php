<?php
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

// Handle login requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['role'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email, password and role are required']);
        exit;
    }
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
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
    
    if (!password_verify($password, $user['password'])) {
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