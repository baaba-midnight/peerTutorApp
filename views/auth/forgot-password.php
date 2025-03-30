<?php
include '../../config/database.php';

// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Prepare query to check if email exists
    $query = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        $row = $results->fetch_assoc();

        // Generate a random password
        $new_password = bin2hex(random_bytes(4)); // Generates an 8-character password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_query = "UPDATE Users SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Send the new password via email
        $subject = "Your New Password";
        $message = "Hello, your new password is: $new_password\nPlease change it after logging in.";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A new password has been sent to your email.";
        } else {
            echo "Failed to send email. Please try again.";
        }
    } else {
        echo "Email not found!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}