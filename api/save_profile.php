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

// Collect POST data
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : null;
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;
$role = isset($_POST['role']) ? trim($_POST['role']) : null;
$bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;
$profile_picture_url = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileTmpPath = $_FILES['profile_image']['tmp_name'];
    $fileName = basename($_FILES['profile_image']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = $userId . '_' . time() . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $profile_picture_url = 'uploads/avatars/' . $newFileName;
    }
}

// if ($profile_picture_url) {
//     $updateData['profile_picture_url'] = $profile_picture_url;
// }

if (!$first_name || !$last_name) {
    echo json_encode(['status' => 'error', 'message' => 'First and last name are required.']);
    exit;
}

$database = new Database();
$conn = $database->connect();
$user = new User($conn);

$updateData = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'role' => $role,
    'bio' => $bio,
    'profile_picture_url' => $profile_picture_url,
];

$result = $user->updateUser($userId, $updateData);

if ($result && $result['status'] === 'success') {
    echo json_encode(['status' => 'success']);
} else {
    $msg = isset($result['message']) ? $result['message'] : 'Update failed.';
    echo json_encode(['status' => 'error', 'message' => $msg]);
}
