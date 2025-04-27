<?php
include '../config/database.php';
require_once '../models/User.php';

$database = new Database();
$conn = $database->connect();
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $email = trim($_POST['email']);
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $role = $_POST['role-options'];
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['password2']);
    $username = trim($_POST['username']);

    // Check if fields are empty
    if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($username)) {
        die("Don't leave required fields empty");
    }

    if ($confirmPassword !== $password) {
        die("Passwords do not match");
    }

    // Handle profile picture upload if present
    $profilePictureUrl = '';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = '../../uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $profilePictureUrl = 'uploads/avatars/' . $fileName;
        }
    }

    $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : null;
    $bio = isset($_POST['experience']) ? trim($_POST['experience']) : null;

    // Use User class to register
    $result = $user->register($firstName, $lastName, $email, $password, $role, $phone, $bio, $profilePictureUrl, $subjects);

    if ($result['status'] === 'success') {
        echo "<script>alert('Registration Successful'); window.location.href = '../views/auth/login.php';</script>";
    } else {
        echo "<script>alert('Registration Unsuccessful: " . htmlspecialchars($result['message']) . "'); window.history.back();</script>";
    }
}
?>