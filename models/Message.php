<?php
class Message {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Send a message
    public function sendMessage($sender_id, $recipient_id, $content) {
        $query = "INSERT INTO Messages (sender_id, recipient_id, content) VALUES (:sender_id, :recipient_id, :content)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':recipient_id', $recipient_id);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    // Get all messages between two users (ordered by time)
    public function getConversation($user1_id, $user2_id) {
        $query = "SELECT * FROM Messages WHERE (sender_id = :user1 AND recipient_id = :user2) OR (sender_id = :user2 AND recipient_id = :user1) ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user1', $user1_id);
        $stmt->bindParam(':user2', $user2_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all conversations for a user (latest message per conversation)
    public function getUserConversations($user_id) {
        $query = "SELECT m1.* FROM Messages m1 INNER JOIN (SELECT LEAST(sender_id, recipient_id) as user_a, GREATEST(sender_id, recipient_id) as user_b, MAX(created_at) as max_time FROM Messages WHERE sender_id = :user_id OR recipient_id = :user_id GROUP BY user_a, user_b) m2 ON ((LEAST(m1.sender_id, m1.recipient_id) = m2.user_a) AND (GREATEST(m1.sender_id, m1.recipient_id) = m2.user_b) AND m1.created_at = m2.max_time) ORDER BY m1.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mark messages as read in a conversation
    public function markAsRead($sender_id, $recipient_id) {
        $query = "UPDATE Messages SET is_read = 1 WHERE sender_id = :sender_id AND recipient_id = :recipient_id AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':recipient_id', $recipient_id);
        return $stmt->execute();
    }
}
?>
