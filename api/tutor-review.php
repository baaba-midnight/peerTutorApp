<?php 
require_once '../config/Database.php'; // Your DB connection
require_once '../models/Review.php'; // Where your PHP functions live

header('Content-Type: application/json');

$database = new Database();
$pdo = $database->connect();

$reviewModel = new Review($pdo);

$action = isset($_GET['action']) ? $_GET['action'] : null;
$tutor_id = isset($_GET['tutor_id']) ? $_GET['tutor_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action){
        case "getFilteredReviews":
            $rating = isset($_GET['rating']) ? $_GET['rating'] : null;
            $limit  = isset($_GET['limit']) ? $_GET['limit'] : 10;
            $reviews = $reviewModel->getFilteredReviews($tutor_id, $rating, $limit);
            $response = [
                'status' => 'success',
                'data' => $reviews
            ];
            break;
        case "getReviewStats":
            $totalReviews = $reviewModel->getTotalReviews($tutor_id);
            $avgRating = $reviewModel->getTutorAvgRating($tutor_id);
            $response = [
                'status' => 'success',
                'data' => [
                    'total_reviews' => $totalReviews,
                    'avg_rating' => $avgRating
                ]
            ];
            break;
        default:
            $response = [
                'error' => 'Invalid action'
            ];
            break;
    }
} else {
    $response = [
        'error' => 'Invalid request method'
    ];
}

echo json_encode($response);