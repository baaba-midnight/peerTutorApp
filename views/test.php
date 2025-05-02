<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Student.php';

$database = new Database();
$conn = $database->connect();
$studentModel = new Student($conn);

// Test leaveReview
$session_id = 1; // Replace with a valid session_id from your Sessions table
$student_id = 1; // Replace with a valid student_id
$tutor_id = 2;     // Replace with a valid tutor_id
$rating = 5;
$comment = 'Great session!';
$is_anonymous = true;

$result = $studentModel->leaveReview($session_id, $student_id, $tutor_id, $rating, $comment, $is_anonymous);

echo '<pre>';
if ($result) {
    echo "leaveReview succeeded\n";
} else {
    echo "leaveReview failed\n";
}
echo '</pre>';
