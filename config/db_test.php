<?php
require_once 'Database.php';

header('Content-Type: text/plain');
error_reporting(E_ALL);

try {
    $db = new Database();
    $conn = $db->connect();
    
    echo "Connection successful!\n";
    
    // Test query
    $stmt = $conn->query("SELECT COUNT(*) FROM system_logs");
    $count = $stmt->fetchColumn();
    
    echo "System logs count: $count";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}