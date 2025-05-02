<?php
// Returns all courses as JSON for populating course dropdowns
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare('SELECT course_id, course_code, course_name, department, description FROM Courses ORDER BY course_name');
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'courses' => $courses]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch courses.']);
}
