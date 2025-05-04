<?php
// Include required files
require_once '../config/database.php';
require_once '../models/Tutor.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'courses' => []
];

// Check if a tutor_id is provided
if (!isset($_GET['tutor_id']) || empty($_GET['tutor_id'])) {
    $response['message'] = 'Tutor ID is required';
    echo json_encode($response);
    exit;
}

try {
    // Get the tutor ID from the request
    $tutor_id = $_GET['tutor_id'];

    
    // Create a database connection
    $database = new Database();
    $db= $database->connect();
    
    // Create a Tutor object
    $tutor = new Tutor($db);
    
    // Get the tutor's courses
    $courses = $tutor->getTutorCourses($tutor_id);
    
    if ($courses) {
        $response['status'] = 'success';
        $response['message'] = 'Courses retrieved successfully';
        $response['courses'] = $courses;
    } else {
        $response['status'] = 'success';
        $response['message'] = 'No courses found for this tutor';
        $response['courses'] = [];
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Return the response as JSON
echo json_encode($response);
?>
