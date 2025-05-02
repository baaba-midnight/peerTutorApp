<?php
// api/get_user.php

require_once '../config/database.php';
require_once '../models/User.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['id'];
$database = new Database();
$conn = $database->connect();
$userModel = new User($conn); // Assumes User constructor takes DB connection
$user = $userModel->getUserById($userId); // Assumes getUserById exists

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
