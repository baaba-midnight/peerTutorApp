<?php
// Temporary file to check database structure
require_once('config/database.php');

// Get database connection
$conn = getConnection();

// Check if the Users table exists
$result = $conn->query("SHOW TABLES LIKE 'Users'");
if ($result->num_rows == 0) {
    echo "The Users table doesn't exist!";
    exit;
}

// Get table structure
$result = $conn->query("DESCRIBE Users");
if ($result) {
    echo "<h3>Users Table Structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] === NULL ? 'NULL' : $row['Default']) . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Close the connection
$conn->close();
?>