<?php
// Script to add username column to Users table
require_once('../config/database.php');

// Get database connection
$conn = getConnection();

// Add username column if it doesn't exist
$sql = "ALTER TABLE Users ADD COLUMN username VARCHAR(50) UNIQUE NOT NULL";
$result = $conn->query($sql);

if ($result) {
    echo "Username column added successfully!";
} else {
    echo "Error adding username column: " . $conn->error;
}

// Close the connection
$conn->close();
?>