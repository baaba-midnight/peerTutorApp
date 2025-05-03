<?php
// Save tutor availability
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}
require_once '../config/database.php';

$user_id = $_SESSION['id'];
$availability = isset($_POST['availability']) ? $_POST['availability'] : null;
if (!$availability) {
    echo json_encode(['status' => 'error', 'message' => 'No availability data provided.']);
    exit;
}
try {
    $db = new Database();
    $conn = $db->connect();
    // Save as JSON in TutorProfiles.availability_schedule
    $stmt = $conn->prepare('UPDATE TutorProfiles SET availability_schedule = ? WHERE user_id = ?');
    $stmt->execute([$availability, $user_id]);
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save availability.']);
}
