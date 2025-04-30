<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
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

    <div class="sign-up-container">
        <div class="logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M37.5 70C37.5 70 62.5 70 62.5 60C62.5 50 37.5 50 37.5 40C37.5 30 62.5 30 62.5 30" stroke="black" stroke-width="10" stroke-linecap="round" />
                <path d="M75 20L90 35L75 50" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25 50L10 65L25 80" stroke="black" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1 class="heading">Create Account</h1>
        <p class="subheading">Join our community of learners and educators</p>

        <div class="progress-indicator">
            <div class="progress-bar" id="progress-bar"></div>
        </div>

        <div class="steps-indicator">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span>Basic Info</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span>Account Setup</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span>Preferences</span>
            </div>
        </div>

        <form id="registrationForm" novalidate>
            <!-- Step 1: Basic Information -->
            <div class="form-step" id="step1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" class="form-control" placeholder="Enter your first name" required>
                        <div class="error-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" class="form-control" placeholder="Enter your last name" required>
                        <div class="error-feedback"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email address" required>
                    <div class="error-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" class="form-control" placeholder="Enter your phone number">
                    <div class="error-feedback"></div>
                </div>
            </div>

            <!-- Step 2: Account Setup -->
            <div class="form-step" id="step2" style="display: none;">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" class="form-control" placeholder="Choose a username" required>
                    <div class="error-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" class="form-control" placeholder="Create a password" required>
                    <div class="error-feedback"></div>
                    <div class="password-strength" id="password-strength"></div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password *</label>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm your password" required>
                    <div class="error-feedback"></div>
                </div>
                <div class="form-group">
                    <label>Account Type *</label>
                    <div class="role-selection-buttons">
                        <button type="button" class="role-button student" data-role="student">Student</button>
                        <button type="button" class="role-button tutor" data-role="tutor">Tutor</button>
                    </div>
                    <input type="hidden" id="selectedRole" required>
                    <div class="error-feedback"></div>
                </div>
            </div>

            <!-- Step 3: Preferences -->
            <div class="form-step" id="step3" style="display: none;">
                <div class="form-group">
                    <label for="subjects">Subjects of Interest *</label>
                    <select id="subjects" class="form-control" multiple required>
                        <option value="mathematics">Mathematics</option>
                        <option value="physics">Physics</option>
                        <option value="chemistry">Chemistry</option>
                        <option value="biology">Biology</option>
                        <option value="computer_science">Computer Science</option>
                        <option value="english">English</option>
                        <option value="history">History</option>
                        <option value="geography">Geography</option>
                    </select>
                    <div class="error-feedback"></div>
                </div>
                <div class="form-group tutor-only" style="display: none;">
                    <label for="experience">Teaching Experience</label>
                    <textarea id="experience" class="form-control" rows="3" placeholder="Describe your teaching experience"></textarea>
                    <div class="error-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="avatar">Profile Picture</label>
                    <label class="upload-image-label" for="avatar">
                        <span id="avatar-text">Choose a profile picture</span>
                        <input type="file" id="avatar" accept="image/*">
                    </label>
                    <div class="error-feedback"></div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn-prev" style="display: none;">Previous</button>
                <button type="button" class="btn-next">Next</button>
                <button type="submit" class="sign-up-button" style="display: none;">Create Account</button>
            </div>
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
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

        // Form validation and multi-step functionality
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const steps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('progress-bar');
            const prevBtn = document.querySelector('.btn-prev');
            const nextBtn = document.querySelector('.btn-next');
            const submitBtn = document.querySelector('.sign-up-button');
            let currentStep = 1;

            function updateProgress() {
                const progress = ((currentStep - 1) / (steps.length - 1)) * 100;
                progressBar.style.width = `${progress}%`;
                document.querySelectorAll('.step').forEach((step, index) => {
                    if (index + 1 <= currentStep) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
            }

            function showStep(step) {
                const currentStepEl = document.querySelector('.form-step:not([style*="display: none"])');
                if (currentStepEl) {
                    currentStepEl.classList.add('sliding-out');
                    setTimeout(() => {
                        currentStepEl.style.display = 'none';
                        currentStepEl.classList.remove('sliding-out');
                        showNextStep();
                    }, 400);
                } else {
                    showNextStep();
                }

                function showNextStep() {
                    steps.forEach(s => s.style.display = 'none');
                    const nextStep = document.getElementById(`step${step}`);
                    nextStep.style.display = 'block';
                    // Trigger reflow for animation
                    nextStep.offsetHeight;
                    nextStep.classList.add('active');
                    
                    prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
                    nextBtn.style.display = step === steps.length ? 'none' : 'inline-block';
                    submitBtn.style.display = step === steps.length ? 'inline-block' : 'none';
                    updateProgress();
                }
            }

            function validateStep(step) {
                const currentStepEl = document.getElementById(`step${step}`);
                const inputs = currentStepEl.querySelectorAll('input[required], select[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value) {
                        showError(input, 'This field is required');
                        isValid = false;
                    } else {
                        clearError(input);
                        if (input.type === 'email' && !isValidEmail(input.value)) {
                            showError(input, 'Please enter a valid email address');
                            isValid = false;
                        }
                        if (input.id === 'password' && !isValidPassword(input.value)) {
                            showError(input, 'Password must be at least 8 characters long and contain at least one number');
                            isValid = false;
                        }
                        if (input.id === 'confirmPassword' && input.value !== document.getElementById('password').value) {
                            showError(input, 'Passwords do not match');
                            isValid = false;
                        }
                    }
                });

                return isValid;
            }

            function showError(input, message) {
                input.classList.add('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-feedback')) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            }

            function clearError(input) {
                input.classList.remove('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-feedback')) {
                    errorDiv.style.display = 'none';
                }
            }

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function isValidPassword(password) {
                return password.length >= 8 && /\d/.test(password);
            }

            nextBtn.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            prevBtn.addEventListener('click', () => {
                currentStep--;
                showStep(currentStep);
            });

            // Role selection
            const roleButtons = document.querySelectorAll('.role-button');
            const selectedRoleInput = document.getElementById('selectedRole');
            const tutorOnlyFields = document.querySelectorAll('.tutor-only');

            roleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    roleButtons.forEach(b => b.classList.remove('active'));
                    button.classList.add('active');
                    selectedRoleInput.value = button.dataset.role;
                    tutorOnlyFields.forEach(field => {
                        field.style.display = button.dataset.role === 'tutor' ? 'block' : 'none';
                    });
                });
            });

            // File upload
            const avatarInput = document.getElementById('avatar');
            const avatarText = document.getElementById('avatar-text');

            avatarInput.addEventListener('change', () => {
                if (avatarInput.files && avatarInput.files[0]) {
                    avatarText.textContent = avatarInput.files[0].name;
                }
            });

            // Form submission
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (validateStep(currentStep)) {
                    // Create FormData object
                    const formData = new FormData();
                    
                    // Add all form fields to FormData
                    formData.append('firstName', document.getElementById('firstName').value);
                    formData.append('lastName', document.getElementById('lastName').value);
                    formData.append('email', document.getElementById('email').value);
                    formData.append('phone', document.getElementById('phone').value || '');
                    formData.append('username', document.getElementById('username').value);
                    formData.append('password', document.getElementById('password').value);
                    formData.append('role', document.getElementById('selectedRole').value);
                    
                    // Add selected subjects
                    const subjects = Array.from(document.getElementById('subjects').selectedOptions).map(option => option.value);
                    formData.append('subjects', subjects.join(','));
                    
                    // Add tutor-specific fields if applicable
                    if (document.getElementById('selectedRole').value === 'tutor') {
                        formData.append('experience', document.getElementById('experience').value || '');
                    }
                    
                    // Add avatar file if selected
                    if (avatarInput.files && avatarInput.files[0]) {
                        formData.append('avatar', avatarInput.files[0]);
                    }
                    
                    try {
                        const response = await fetch('../../api/auth.php?action=register', {
                            method: 'POST',
                            body: formData
                        });
                        
                        // Check if response is valid JSON
                        let data;
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            try {
                                data = await response.json();
                            } catch (jsonError) {
                                console.error('JSON parsing error:', jsonError);
                                
                                // Try to get the raw text to see the actual server response
                                const rawText = await response.text();
                                throw new Error(`Server returned invalid JSON. Raw response: ${rawText.substring(0, 100)}...`);
                            }
                        } else {
                            // If not JSON, get the text content
                            const textContent = await response.text();
                            throw new Error(`Server returned non-JSON response: ${textContent.substring(0, 100)}...`);
                        }
                        
                        if (!response.ok) {
                            throw new Error(data.error || 'Registration failed');
                        }

                        if (data.success) {
                            window.location.href = 'login.php?registered=true';
                        } else {
                            throw new Error(data.error || 'Registration failed with an unknown error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        
                        // Create an error alert above the form
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger';
                        errorAlert.innerHTML = `<strong>Error:</strong> ${error.message}`;
                        
                        // Insert at the top of the form
                        form.prepend(errorAlert);
                        
                        // Scroll to top to show error
                        window.scrollTo(0, 0);
                    }
                }
            });

            // Initialize first step
            showStep(1);
        });
    </script>
</body>
</html>
