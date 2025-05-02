<?php
// Start the session at the beginning
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false || strpos($_SERVER['PHP_SELF'], 'appointment.php') !== false) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
        } else {
            header('Location: /peerTutorApp/login.php');
        }
        exit;
    }
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user role
function getUserRole() {
    return $_SESSION['role'] ?? null;
}

// Check if user is admin
function isAdmin() {
    return getUserRole() === 'admin';
}

// Check if user is tutor
function isTutor() {
    return getUserRole() === 'tutor';
}

// Check if user is student
function isStudent() {
    return getUserRole() === 'student';
}

// Log out user
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: /peerTutorApp/login.php');
    exit;
}
?>