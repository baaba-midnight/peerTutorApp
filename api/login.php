<?php
include '../config/database.php';
require_once '../models/User.php';

session_start();

$database = new Database();
$conn = $database->connect();
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = isset($_POST['role']) ? $_POST['role'] : null;

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        die("Don't leave fields empty");
    }

    $result = $user->login($email, $password);

    if ($result['status'] === 'success') {
        $userData = $result['user'];
        $_SESSION['id'] = $userData['user_id'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['full_name'] = $userData['full_name'];
        $_SESSION['email'] = $userData['email'];
        echo "<script>alert('" . htmlspecialchars($_SESSION['id'], ENT_QUOTES) . "');</script>";
        echo "<script>alert('" . htmlspecialchars($_SESSION['role'], ENT_QUOTES) . "');</script>";

        // Redirect based on role
        if ($userData['role'] === 'admin') {
            header('Location: ../views/dashboard/admin-dashboard.php');
        } elseif ($userData['role'] === 'student') {
            header('Location: ../views/dashboard/student-dashboard.php');
        } elseif ($userData['role'] === 'tutor') {
            header('Location: ../views/dashboard/tutor-dashboard.php');
        } else {
            header('Location: ../index.php');
        }
        exit();
    } else {
        echo '<script>alert("Login failed: ' . htmlspecialchars($result['message']) . '"); window.history.back();</script>';
        exit();
    }
}
?>