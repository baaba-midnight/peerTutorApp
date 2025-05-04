<?php
require_once '../config/Database.php'; // Your DB connection
require_once '../models/Tutor.php'; // Where your PHP functions live

header('Content-Type: application/json');

$database = new Database();
$pdo = $database->connect();

$tutorModel = new Tutor($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : null;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
} else {
    $action = null;
}

$tutor_id = isset($_GET['tutor_id']) ? $_GET['tutor_id'] : null;
$limit = isset($_GET['limit']) ? $_GET['limit'] : null; // Default limit for dashboard data

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action) {
        case "getDashboardData":
            $sessions = $tutorModel->getUpcomingSessions($tutor_id, $limit);
            $messages = $tutorModel->getRecentMessages($tutor_id);
            $reviews = $tutorModel->getRecentReviews($tutor_id);

            $response = [
                'status' => 'success',
                'sessions' => $sessions,
                'messages' => $messages,
                'reviews' => $reviews
            ];
            break;
        case "getAppointments":
            $appointments = $tutorModel->getUpcomingSessions($tutor_id, $limit);
            $response = [
                'status' => 'success',
                'appointments' => $appointments
            ];
            break;
        default:
            $response = [
                'error' => 'Invalid action'
            ];
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === "updateAppointmentStatus" && isset($_POST['status'])) {
        $appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null;
        $status = $_POST['status'];

        if ($appointment_id && in_array($status, ['completed', 'pending', 'cancelled'])) {
            if ($tutorModel->updateAppointmentStatus($appointment_id, $status)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Appointment status updated successfully.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to update appointment status.'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid appointment ID or status.'
            ];
        }
    } else {
        $response = [
            'error' => 'Invalid action or missing parameters'
        ];
    }
} else
    $response = [
        'error' => 'Invalid request method'
    ];

echo json_encode($response);
