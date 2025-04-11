<?php
require_once "../../config/database.php";
require_once "../../models/Student.php";

$database = new Database();
$db = $database->connect();
$studentModel = new Student($db);

// Get all students
// $students = $studentModel->getAllStudents();
// print_r($students);

// Get a specific student by user ID
$student = $studentModel->getStudentByUserId("9");
echo '<script>alert("User not registered.");</script>';
print_r($student);

// Update student profile
// $studentModel->updateStudentProfile("some-user-id", "Love programming!", "profile.jpg", "Computer Science", 2);

// Get student session history
// $sessions = $studentModel->getStudentSessions("some-user-id");
// print_r($sessions);

// Leave a review for a tutor
// $studentModel->leaveReview("some-session-id", "some-user-id", "some-tutor-id", 5, "Great tutor!", true);

// Delete a student account
// $studentModel->deleteStudent("some-user-id");
?>
