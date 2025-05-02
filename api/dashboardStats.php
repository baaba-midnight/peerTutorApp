<?php
require_once '../config/Database.php'; // Your DB connection
require_once '../models/dashboardStat.php'; // Where your PHP functions live

header('Content-Type: application/json');

$database = new Database();
$pdo = $database->connect();

$statModel = new DashboardStat($pdo);

$response = [
    'activeTutors' => $statModel->getActiveTutors(),
    'activeStudents' => $statModel->getActiveStudents(),
    'completedSessions' => $statModel->getCompletedSessions(),
    'avgRating' => $statModel->getAverageRating(),
    'topTutors' => $statModel->getTopRatedTutors(),
];

echo json_encode($response);
