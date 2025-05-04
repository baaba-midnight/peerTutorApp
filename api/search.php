<?php
// API endpoint to search and fetch tutors (and optionally other users)
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/User.php';

try {
    $db = new Database();
    $conn = $db->connect();
    $userModel = new User($conn);

    // Only support GET for now
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        exit;
    }

    // Get query params
    $role = isset($_GET['role']) ? $_GET['role'] : null;
    $searchParams = [];

    // If role is tutor, fetch tutors only
    if ($role === 'tutor') {
        $tutors = $userModel->searchTutors([]);
        // Add courses_offered as a comma-separated string for each tutor
        foreach ($tutors as &$tutor) {
            $tutor['courses_offered'] = $userModel->getTutorCourses($tutor['user_id']);
        }
        echo json_encode(['status' => 'success', 'data' => $tutors]);
        exit;
    }

    // Otherwise, fetch all users (admin use case)
    $users = $userModel->getAllUsers();
    echo json_encode(['status' => 'success', 'data' => $users['data']]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
