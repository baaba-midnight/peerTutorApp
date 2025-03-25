<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
</head>
<body>
    <div id="particles-js"></div>
    <a href="../../index.php" class="back-button">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.5 12.5L5.5 8L10.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back to Home
    </a>

    <div class="login-container">
        <div class="logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1 class="heading">Welcome Back</h1>
        <p class="subheading">Glad to see you again! Please login to your account.</p>

        <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
        <div class="alert alert-success" role="alert">
            Registration successful! Please login with your credentials.
        </div>
        <?php endif; ?>

        <div id="error-message" class="alert alert-danger" style="display: none;" role="alert"></div>

        <form id="loginForm" novalidate>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="Enter your email address" required>
                <div class="error-feedback"></div>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <div class="password-input-group">
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5C5.636 5 0 12 0 12s5.636 7 12 7 12-7 12-7-5.636-7-12-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <div class="error-feedback"></div>
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
            </div>

            <div class="sign-in-as">
                <span>Sign in as</span>
            </div>

            <div class="role-buttons">
                <button type="button" class="role-button student" data-role="student">Student</button>
                <button type="button" class="role-button tutor" data-role="tutor">Tutor</button>
            </div>
            <input type="hidden" id="selected-role" name="role" required>

            <button type="submit" class="login-button">
                <span class="button-text">Login</span>
                <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>

            <p class="sign-up-link">Don't have an account? <a href="register.php">Sign Up</a></p>
        </form>
    </div>

    <script>
        // Initialize particles.js
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#000000' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#000000',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'repulse' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                }
            },
            retina_detect: true
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const errorMessage = document.getElementById('error-message');
            const roleButtons = document.querySelectorAll('.role-button');
            const selectedRole = document.getElementById('selected-role');
            const loginButton = document.querySelector('.login-button');
            const buttonText = loginButton.querySelector('.button-text');
            const spinner = loginButton.querySelector('.spinner-border');
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            // Role selection
            roleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    roleButtons.forEach(b => b.classList.remove('active'));
                    button.classList.add('active');
                    selectedRole.value = button.dataset.role;
                });
            });

            // Set default role as student
            document.querySelector('.role-button.student').click();

            // Toggle password visibility
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassword.innerHTML = type === 'password' ? 
                    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5C5.636 5 0 12 0 12s5.636 7 12 7 12-7 12-7-5.636-7-12-7z"/><circle cx="12" cy="12" r="3"/></svg>' :
                    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c-7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            });

            function validateForm() {
                let isValid = true;
                const email = document.getElementById('email');
                const password = document.getElementById('password');

                // Reset previous errors
                errorMessage.style.display = 'none';
                document.querySelectorAll('.error-feedback').forEach(ef => ef.textContent = '');
                document.querySelectorAll('.form-control').forEach(fc => fc.classList.remove('error'));

                if (!email.value) {
                    showFieldError(email, 'Email is required');
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    showFieldError(email, 'Please enter a valid email address');
                    isValid = false;
                }

                if (!password.value) {
                    showFieldError(password, 'Password is required');
                    isValid = false;
                }

                if (!selectedRole.value) {
                    errorMessage.textContent = 'Please select a role (Student or Tutor)';
                    errorMessage.style.display = 'block';
                    isValid = false;
                }

                return isValid;
            }

            function showFieldError(field, message) {
                field.classList.add('error');
                const errorDiv = field.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-feedback')) {
                    errorDiv.textContent = message;
                }
            }

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Show loading state
                buttonText.style.display = 'none';
                spinner.style.display = 'inline-block';
                loginButton.disabled = true;

                try {
                    const formData = {
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        role: selectedRole.value
                    };

                    const response = await fetch('../../api/auth.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.error || 'Login failed');
                    }

                    // Redirect based on role
                    const dashboardPath = selectedRole.value === 'tutor' 
                        ? '../dashboard/tutor-dashboard.php'
                        : '../dashboard/student-dashboard.php';
                    
                    window.location.href = dashboardPath;

                } catch (error) {
                    errorMessage.textContent = error.message;
                    errorMessage.style.display = 'block';
                } finally {
                    // Reset loading state
                    buttonText.style.display = 'inline';
                    spinner.style.display = 'none';
                    loginButton.disabled = false;
                }
            });

            // Hide error message when user starts typing
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', () => {
                    input.classList.remove('error');
                    const errorDiv = input.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('error-feedback')) {
                        errorDiv.textContent = '';
                    }
                    errorMessage.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
<?php
include '../../config/database.php';

session_start();

if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //check if fields are empty
    if(empty($email)||empty($password)){
        die("Dont leave field empty");
    }

    $query = 'SELECT user_id, first_name, last_name, `password_hash`, email, `role` FROM Users WHERE email = ?';
    $stmt = $conn -> prepare($query);
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $results = $stmt -> get_result();

    if($results -> num_rows > 0){
        $row = $results -> fetch_assoc();

        $user_id = $row['user_id'];
        $user_role = $row['role'];
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $email = $row['email'];

        if (password_verify($password, $row['password'])){
            // set session variables
            $_SESSION['id'] = $user_id;
            $_SESSION['role'] = $user_role;
            $_SESSION['full_name'] = $firstName . " " . $lastName;
            $_SESSION['email'] = $email;

            if ($user_role == 'nurse'){
                header("Location: ../dashboard/admin-dashboard.php");
            }elseif($user_role == 'doctor'){
                header("Location: ../dashboard/student-dashboard.php");
            }elseif($user_role == 'admin'){
                header("Location: ../dashboard/tutor-dashboard.php");
            }else{
                header("Location: login.php");
            }
            exit();
        }
    }else {
        // Show an alert if the user is not registered
        header("Location: register.php");
        echo '<script>alert("User not registered.");</script>';
    }
    $stmt -> close();
}
$conn -> close();
?>
