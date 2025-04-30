<?php
// Script to add UserSubjects table
require_once('../config/database.php');

// Get database connection
$conn = getConnection();

// Create UserSubjects table
$sql = "CREATE TABLE IF NOT EXISTS UserSubjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
)";

$result = $conn->query($sql);

if ($result) {
    echo "UserSubjects table created successfully!";
} else {
    echo "Error creating UserSubjects table: " . $conn->error;
}

// Close the connection
$conn->close();
?>