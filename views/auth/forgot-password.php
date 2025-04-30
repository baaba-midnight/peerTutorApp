<?php
require_once '../../config/database.php';
require_once '../../models/User.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    // Generate a random password
    $new_password = bin2hex(random_bytes(4));
    $result = $user->resetPasswordByEmail($email, $new_password);

    if ($result['status'] === 'success') {
        $subject = "Your New Password";
        $message = "Hello, your new password is: $new_password\nPlease change it after logging in.";
        $headers = "From: no-reply@ashesi.com";
        if (mail($email, $subject, $message, $headers)) {
            $success = "A new password has been sent to your email.";
        } else {
            $error = "Failed to send email. Please try again.";
        }
    } else {
        $error = $result['message'];
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
    <style>
        .forgot-password-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            font-weight: var(--poppins-medium);
            font-size: 0.875rem;
        }

        .alert-danger {
            background-color: rgba(0, 0, 0, 0.05);
            color: #000;
            border: 1px solid #000;
        }

        .alert-success {
            background-color: rgba(0, 0, 0, 0.05);
            color: #000;
            border: 1px solid #000;
        }

        .heading {
            font-size: 2.25rem;
            font-weight: var(--poppins-bold);
            color: #000;
            margin-bottom: 0.75rem;
        }

        .subheading {
            font-size: 1rem;
            color: #000;
            opacity: 0.5;
            margin-bottom: 1.5rem;
            font-weight: var(--poppins-regular);
        }

        .reset-button {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.375rem;
            background-color: #000;
            color: #fff;
            font-size: 1rem;
            font-weight: var(--poppins-semi-bold);
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out, transform 0.1s ease-in-out;
            margin-bottom: 1rem;
        }

        .reset-button:hover {
            background-color: #000;
            transform: translateY(-2px);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .form-footer p {
            font-size: 0.875rem;
            color: #000;
            font-weight: var(--poppins-regular);
        }

        .form-footer a {
            color: #000;
            font-weight: var(--poppins-semi-bold);
            text-decoration: underline;
        }

        .form-footer a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1 class="heading">Password Reset</h1>
        <p class="subheading">Enter your email address and we'll send you instructions to reset your password.</p>

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
            
            <button type="submit" class="reset-button">Send Reset Instructions</button>
            
            <div class="form-footer">
                <p>Remember your password? <a href="login.php">Back to Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>
