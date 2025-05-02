<?php
/**
 * Logout API
 * Handles user logout by destroying session and redirecting
 */
require_once '../config/session.php';

// Log the logout event
if (isLoggedIn()) {
    error_log("User {$_SESSION['id']} ({$_SESSION['email']}) logged out");
}

// Call the logout function from session.php
logout();

// Set a success message
$_SESSION['success'] = "You have been successfully logged out";

// Redirect to login page
header('Location: ../views/auth/login.php');
exit;
?>