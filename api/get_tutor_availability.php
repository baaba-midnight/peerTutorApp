<?php
// Get tutor availability for booking
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../models/Tutor.php';

// Initialize response
$response = [
    'status' => 'error',
    'message' => '',
    'availability' => []
];

// Check if tutor_id is provided
if (!isset($_GET['tutor_id']) || empty($_GET['tutor_id'])) {
    $response['message'] = 'Tutor ID is required';
    echo json_encode($response);
    exit;
}

try {
    $tutor_id = $_GET['tutor_id'];
    
    // Create database connection
    $database = new Database();
    $db = $database->connect();
    
    // Query for getting tutor availability from TutorProfiles.availability_schedule
    $query = "SELECT availability_schedule FROM TutorProfiles WHERE user_id = :tutor_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':tutor_id', $tutor_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && !empty($result['availability_schedule'])) {
        // Parse the JSON availability data
        $availability = json_decode($result['availability_schedule'], true);
        
        // Format the response
        $response['status'] = 'success';
        $response['message'] = 'Availability retrieved successfully';
        $response['availability'] = $availability;
    } else {
        $response['status'] = 'success';
        $response['message'] = 'No availability found for this tutor';
        $response['availability'] = [];
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>