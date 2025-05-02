<?php
require_once '../config/database.php';
require_once '../models/Student.php';
header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
$appointment_id = $input['appointment_id'] ?? null;
$rating = $input['rating'] ?? null;
$comment = $input['comment'] ?? '';
$is_anonymous = isset($input['is_anonymous']) ? (bool)$input['is_anonymous'] : false;

if (!$appointment_id || !$rating) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Get session info to find student_id and tutor_id for this appointment
try {
    $db = (new Database())->connect();
    // Get appointment/session info
    $stmt = $db->prepare('SELECT session_id, student_id, tutor_id FROM Sessions WHERE session_id = :session_id');
    $stmt->bindParam(':session_id', $appointment_id);
    $stmt->execute();
    $session = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$session) {
        echo json_encode(['success' => false, 'message' => 'Session not found.']);
        exit;
    }
    $student_id = $session['student_id'];
    $tutor_id = $session['tutor_id'];

    $student = new Student($db);
    $success = $student->leaveReview($appointment_id, $student_id, $tutor_id, $rating, $comment, $is_anonymous);
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save review.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
