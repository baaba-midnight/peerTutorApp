<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setResetToken($userId, $token, $expiry) {
        $stmt = $this->conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        return $stmt->execute([$token, $expiry, $userId]);
    }
    
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function verifyResetToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function resetPassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }
}