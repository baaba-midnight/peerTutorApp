<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body>
    <div class="sign-up-container">
        <img src="https://via.placeholder.com/80" alt="Logo" class="logo">
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
    </script>
</body>
</html>
