<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../models/User.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $user = new User($conn);
        $userData = $user->findByEmail($email);
        
        if ($userData) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            if ($user->setResetToken($userData['id'], $token, $expiry)) {
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/views/auth/reset-password.php?token=" . $token;
                
                // Send email
                $to = $email;
                $subject = "Password Reset Request";
                $message = "Hello,\n\nYou have requested to reset your password. Click the link below to reset your password:\n\n";
                $message .= $resetLink . "\n\n";
                $message .= "This link will expire in 1 hour.\n\n";
                $message .= "If you didn't request this, please ignore this email.\n\n";
                $message .= "Best regards,\nPeer Tutor App Team";
                $headers = "From: noreply@peertutorapp.com";

                if (mail($to, $subject, $message, $headers)) {
                    $success = "Password reset instructions have been sent to your email.";
                } else {
                    $error = "Failed to send reset email. Please try again later.";
                }
            } else {
                $error = "An error occurred. Please try again later.";
            }
        } else {
            // To prevent email enumeration, show the same message even if email doesn't exist
            $success = "If your email exists in our system, you will receive password reset instructions.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Peer Tutor App</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Forgot Password</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           class="form-control" placeholder="Enter your email address">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                
                <div class="form-footer">
                    <p>Remember your password? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>