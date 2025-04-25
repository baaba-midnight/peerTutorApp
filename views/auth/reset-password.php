*++--<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../models/User.php';

$error = '';
$success = '';
$validToken = false;
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    $error = "Invalid reset link.";
} else {
    $user = new User($conn);
    $userData = $user->verifyResetToken($token);
    
    if ($userData) {
        $validToken = true;
    } else {
        $error = "This reset link has expired or is invalid.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        if ($user->resetPassword($userData['id'], $password)) {
            $success = "Your password has been reset successfully. You can now login with your new password.";
        } else {
            $error = "Failed to reset password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Peer Tutor App</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Reset Password</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <p><a href="login.php">Click here to login</a></p>
                </div>
            <?php endif; ?>
            
            <?php if ($validToken && !$success): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required 
                               class="form-control" placeholder="Enter new password"
                               minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               required class="form-control" 
                               placeholder="Confirm new password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            <?php endif; ?>
            
            <div class="form-footer">
                <p><a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>