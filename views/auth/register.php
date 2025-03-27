<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script-->
    <link rel="stylesheet" href="SignUp.css">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="heading-container">
            <h1 class="heading">Sign Up</h1>
            <p class="subheading">Enter your details below to create your account and get started.</p>
        </div>
        <form class="form-container">
            <div class="form-section">
                <h2 class="form-section-title">Personal Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full-name">Full Name *</label>
                        <input type="text" id="full-name" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Phone Number</label>
                        <input type="tel" id="phone-number" class="form-control" placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="role">Role Selection *</label>
                        <select id="role" class="form-select" required>
                            <option value="" disabled selected>Select your role</option>
                            <option value="student">Student</option>
                            <option value="tutor">Tutor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image *</label>
                        <div class="upload-container">
                            <input type="file" id="image" accept="image/*" required>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="upload-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5v-9m0 9l-4.5-4.5M12 7.5l4.5 4.5m-6-6h-3m6 6h3" />
                            </svg>
                            <p class="upload-text">Click to upload or drag and drop</p>
                            <p class="upload-button-text">Upload Image</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h2 class="form-section-title">Account Details</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password *</label>
                        <input type="password" id="confirm-password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h2 class="form-section-title">Preferences & Interests</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="subjects">Subjects of Interest *</label>
                        <select id="subjects" class="form-select" multiple required>
                            <option value="" disabled >Select Subjects</option>
                            <option value="math">Mathematics</option>
                            <option value="science">Science</option>
                            <option value="english">English</option>
                            <option value="history">History</option>
                            <option value="computer_science">Computer Science</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <select id="availability" class="form-select" multiple>
                            <option value="" disabled>Select Available Days & Time</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tutoring-experience">Tutoring Experience (e.g., 2 years)</label>
                        <textarea id="tutoring-experience" class="form-control" placeholder="Enter your experience level"></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary sign-up-button">Sign Up</button>
            <p class="login-link">Already have an account? <a href="#">Log in</a></p>
        </form>
    </div>
    <script>
        const imageInput = document.getElementById('image');
        const uploadContainer = document.querySelector('.upload-container');
        const uploadText = document.querySelector('.upload-text');
        const uploadButtonText = document.querySelector('.upload-button-text');

        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                uploadText.textContent = file.name;
                uploadButtonText.textContent = 'Change Image';
                uploadContainer.classList.add('bg-light-gray');
            } else {
                uploadText.textContent = 'Click to upload or drag and drop';
                uploadButtonText.textContent = 'Upload Image';
                uploadContainer.classList.remove('bg-light-gray');
            }
        });

        uploadContainer.addEventListener('click', () => {
            imageInput.click();
        });

    </script>
</body>
</html>
