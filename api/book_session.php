<?php
require_once '../config/database.php';
require_once '../models/Appointment.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Accept both JSON and form POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Support both application/json and x-www-form-urlencoded
if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

// Map incoming POST variables to expected model variables
$student_id = $_SESSION['id'];
$tutor_id = $input['tutor_id'] ?? null;
$subject_id = $input['course_id'] ?? $input['subject_id'] ?? null;
$start_datetime = $input['start_time'] ?? $input['start_datetime'] ?? null;
$end_datetime = $input['end_time'] ?? $input['end_datetime'] ?? null;
$meeting_link = $input['link'] ?? $input['meeting_link'] ?? null;
$notes = $input['session_notes'] ?? $input['notes'] ?? null;

if (!$tutor_id || !$subject_id || !$start_datetime || !$end_datetime) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$appointment = new Appointment($conn);

$result = $appointment->create($student_id, $tutor_id, $subject_id, $start_datetime, $end_datetime, $meeting_link, $notes);
echo json_encode($result);
