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
$appointment_id = $_POST['appointment_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? '';
$is_anonymous = isset($_POST['is_anonymous']) ? (bool)$_POST['is_anonymous'] : false;

if (!$appointment_id || !$rating) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Get session info to find student_id and tutor_id for this appointment
$db = (new Database())->connect();
// Get appointment/session info
$stmt = $db->prepare('SELECT appointment_id, student_id, tutor_id FROM appointments WHERE appointment_id = :appointment_id');
$stmt->bindParam(':appointment_id', $appointment_id);
$stmt->execute();
$session = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$session) {
    echo json_encode(['success' => false, 'message' => 'Appointment not found.']);
    exit;
}
$student_id = $session['student_id'];
$tutor_id = $session['tutor_id'];

$student = new Student($db);
$success = $student->leaveReview($appointment_id, $student_id, $tutor_id, $rating, $comment, $is_anonymous);
if ($success) {
    echo json_encode(['success' => true, 'message' => 'Review saved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save review.']);
}
