<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$database = new Database();
$conn = $database->connect();
$userModel = new User($conn);

// Test login
$email = 'kevin13cudjoe@gmail.com'; // Replace with a valid email from your Users table
$password = '123456789';   // Replace with the correct password for the above email

$result = $userModel->login($email, $password);

echo '<pre>';
print_r($result);
echo '</pre>';
