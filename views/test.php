<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Tutor.php';

$database = new Database();
$conn = $database->connect();
$tutorModel = new Tutor($conn);


// Test getTutorCourses
$tutorId = 3; // Change to a valid tutor user_id in your database

print_r($tutorModel->getTutorCourses($tutorId));
?>
