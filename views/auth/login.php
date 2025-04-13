<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
    <div class="login-container text-center">
        <div class="logo mx-auto" style="width: 60px; height: 60px; display: flex; justify-content: center; align-items: center; margin: 0 auto;">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <h1 class="heading">Welcome Back</h1>
        <p class="subheading">Glad to see you again! Login to your account below</p>
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="email-username">Email / Username</label>
                <input type="text" id="email-username" name="email" class="form-control" placeholder="Enter your email / username" required>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                <a href="forgot-password.php" class="forgot-password">Forgot Password</a>
            </div>
            <div class="sign-in-as">
                <span>Sign in as</span>
            </div>
            <div class="role-buttons">
                <button type="button" class="role-button tutor">Tutor</button>
                <button type="button" class="role-button student">Student</button>
            </div>
            <input type="hidden" name="role" id="selected-role" value="">
            <button type="submit" class="login-button">Login</button>
            <p class="sign-up-link">Don't have an account? <a href="register.php">Sign Up</a></p>
        </form>
    </div>

    <script>
        const tutorButton = document.querySelector('.role-button.tutor');
        const studentButton = document.querySelector('.role-button.student');
        const selectedRole = document.getElementById('selected-role');
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('error-message');

        tutorButton.addEventListener('click', () => {
            tutorButton.classList.add('active');
            studentButton.classList.remove('active');
            selectedRole.value = 'tutor';
        });

        studentButton.addEventListener('click', () => {
            studentButton.classList.add('active');
            tutorButton.classList.remove('active');
            selectedRole.value = 'student';
        });

        // Set default role as student
        studentButton.click();

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorMessage.style.display = 'none';

            if (!selectedRole.value) {
                errorMessage.textContent = 'Please select a role (Tutor or Student)';
                errorMessage.style.display = 'block';
                return;
            }

            try {
                const formData = {
                    email: document.getElementById('email-username').value,
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
            }
        });

        // Particle effect configuration
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#4a5568'
                },
                shape: {
                    type: 'circle',
                    stroke: {
                        width: 0,
                        color: '#000000'
                    }
                },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#4a5568',
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
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    repulse: {
                        distance: 200,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
