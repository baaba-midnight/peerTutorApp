<?php
require_once '../config/database.php';
require_once '../models/Appointment.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

$database = new Database();
$conn = $database->connect();
$appointment = new Appointment($conn);

try {
    $appointments = $appointment->getForUser($user_id, $role);
    echo json_encode(['status' => 'success', 'appointments' => $appointments]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch appointments.']);
}
?>