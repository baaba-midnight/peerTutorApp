<?php
require_once '../config/database.php';
require_once '../models/User.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['id'];
$currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';

if (!$currentPassword || !$newPassword) {
    echo json_encode(['status' => 'error', 'message' => 'All password fields are required.']);
    exit;
}

$database = new Database();
$conn = $database->connect();
$user = new User($conn);

// Fetch user data
$query = 'SELECT password_hash FROM Users WHERE user_id = :user_id';
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit;
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!password_verify($currentPassword, $row['password_hash'])) {
    echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
    exit;
}

// Update password
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$updateQuery = 'UPDATE Users SET password_hash = :new_hash WHERE user_id = :user_id';
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bindParam(':new_hash', $newPasswordHash);
$updateStmt->bindParam(':user_id', $userId);
if ($updateStmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
}
