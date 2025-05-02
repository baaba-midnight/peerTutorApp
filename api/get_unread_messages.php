<?php
// api/get_unread_messages.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/database.php';
require_once '../models/Message.php';
require_once '../models/User.php';

$user_id = $_SESSION['id'];

$database = new Database();
$conn = $database->connect();
$messageModel = new Message($conn);
$userModel = new User($conn);

try {
    // Get unread messages for the user (grouped by sender, latest message per sender)
    $sql = "SELECT m.message_id, m.sender_id, m.content, m.created_at, u.first_name, u.last_name
            FROM Messages m
            JOIN Users u ON m.sender_id = u.user_id
            WHERE m.recipient_id = :user_id AND m.is_read = 0
            AND m.message_id IN (
                SELECT MAX(message_id) FROM Messages WHERE recipient_id = :user_id AND is_read = 0 GROUP BY sender_id
            )
            ORDER BY m.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = [
            'sender_id' => $row['sender_id'],
            'sender_name' => $row['first_name'] . ' ' . $row['last_name'],
            'sent_time' => date('M d, Y h:i A', strtotime($row['created_at'])),
            'content' => $row['content']
        ];
    }
    echo json_encode(['status' => 'success', 'messages' => $messages]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch unread messages.']);
}
