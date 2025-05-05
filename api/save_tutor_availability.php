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
    // Decode and validate the JSON availability data
    $availabilityData = json_decode($availability, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid availability format.']);
        exit;
    }
    
    // Note: The client-side now only sends days with values, so we don't need to filter them here
    
    $db = new Database();
    $conn = $db->connect();
    // Save as JSON in TutorProfiles.availability_schedule
    $stmt = $conn->prepare('UPDATE TutorProfiles SET availability_schedule = ? WHERE user_id = ?');
    $stmt->execute([$availability, $user_id]);
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to save availability: ' . $e->getMessage()]);
}
