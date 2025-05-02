<?php
// Save tutor courses (courses offered)
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}
require_once '../config/database.php';

$user_id = $_SESSION['id'];
// Remove the forced dummy user_id for production

$courses = isset($_POST['courses']) ? $_POST['courses'] : [];
if (!is_array($courses)) {
    $courses = [$courses];
}

try {
    $db = new Database();
    $conn = $db->connect();
    $tutor_profile_id = $user_id;
    // Remove old courses
    $conn->prepare('DELETE FROM TutorCourses WHERE tutor_id = ?')->execute([$tutor_profile_id]);
    // Insert new courses
    $insert = $conn->prepare('INSERT INTO TutorCourses (tutor_id, course_id, proficiency_level) VALUES (?, ?, "beginner")');
    foreach ($courses as $course_id) {
        $insert->execute([$tutor_profile_id, $course_id]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save courses.']);
}
