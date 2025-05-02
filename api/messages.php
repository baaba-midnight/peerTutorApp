<?php
require_once '../config/database.php';
require_once '../models/Message.php';
require_once '../models/User.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$database = new Database();
$conn = $database->connect();
$messageModel = new Message($conn);
$userModel = new User($conn);
$currentUserId = $_SESSION['id'];

// Only allow messaging with users you have a meeting with
$meetingUserIds = [];
$meetingQuery = "SELECT DISTINCT 
    CASE WHEN student_id = :uid THEN tutor_id ELSE student_id END AS other_user_id
FROM Appointments
WHERE (student_id = :uid OR tutor_id = :uid) AND status IN ('confirmed', 'completed')";
$stmt = $conn->prepare($meetingQuery);
$stmt->bindParam(':uid', $currentUserId);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $meetingUserIds[] = $row['other_user_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get messages for a conversation
    $contactId = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
    if (!$contactId || !in_array($contactId, $meetingUserIds)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact.']);
        exit;
    }
    $messages = $messageModel->getConversation($currentUserId, $contactId);
    echo json_encode(['status' => 'success', 'messages' => $messages]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $contactId = isset($data['contact_id']) ? intval($data['contact_id']) : 0;
    $content = isset($data['content']) ? trim($data['content']) : '';
    if (!$contactId || !in_array($contactId, $meetingUserIds) || !$content) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        exit;
    }
    $success = $messageModel->sendMessage($currentUserId, $contactId, $content);
    if ($success) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
