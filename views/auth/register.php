<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/register.css">
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
    <div class="sign-up-container text-center">
        <div class="logo" style="margin: 0 auto; width: 60px; height: 60px;">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <h1 class="heading">Sign Up</h1>
        <p class="subheading">Enter your details below to create your account and get started</p>
        <form>
            <div class="form-group">
                <label for="full-name">Full Name *</label>
                <input type="text" id="full-name" class="form-control" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" class="form-control" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="phone-number">Phone Number</label>
                <input type="tel" id="phone-number" class="form-control" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label for="upload-image">Upload Image *</label>
                <label for="upload-image" class="upload-image-label">
                    Upload Image
                    <input type="file" id="upload-image" accept="image/*">
                </label>
            </div>
            <div class="form-group">
                <label>Role Selection *</label>
                <div class="role-selection-buttons">
                    <button type="button" class="role-button tutor">Tutor</button>
                    <button type="button" class="role-button student">Student</button>
                </div>
            </div>
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" class="form-control" placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password">
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password *</label>
                <input type="password" id="confirm-password" class="form-control" placeholder="Confirm your password">
            </div>
            <div class="form-group">
                <label for="subjects-of-interest">Subjects of Interest *</label>
                <select id="subjects-of-interest" class="form-control">
                    <option value="" disabled selected>Select Subjects of Interest</option>
                    <option value="mathematics">Mathematics</option>
                    <option value="science">Science</option>
                    <option value="english">English</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="availability">Availability</label>
                 <select id="availability" class="form-control">
                    <option value="" disabled selected>Select Available Days & Time</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="tutoring-experience">Tutoring Experience (e.g., 2 years)</label>
                <input type="text" id="tutoring-experience" class="form-control" placeholder="Enter your experience level">
            </div>
            <button type="submit" class="sign-up-button">Sign Up</button>
            <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
    <script>
       const tutorButton = document.querySelector('.role-button.tutor');
        const studentButton = document.querySelector('.role-button.student');

        tutorButton.addEventListener('click', () => {
            tutorButton.classList.add('active');
            studentButton.classList.remove('active');
        });

        studentButton.addEventListener('click', () => {
            studentButton.classList.add('active');
            tutorButton.classList.remove('active');
        });

         const uploadImageInput = document.getElementById('upload-image');
        const uploadImageLabel = document.querySelector('.upload-image-label');

        uploadImageInput.addEventListener('change', () => {
            if (uploadImageInput.files && uploadImageInput.files[0]) {
                uploadImageLabel.textContent = uploadImageInput.files[0].name;
            } else {
                uploadImageLabel.textContent = 'Upload Image';
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
