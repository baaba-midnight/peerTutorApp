<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <img src="https://via.placeholder.com/80" alt="Logo" class="logo">
        <h1 class="heading">Welcome Back</h1>
        <p class="subheading">Glad to see you again! Login to your account below</p>
        <form>
            <div class="form-group">
                <label for="email-username">Email / Username</label>
                <input type="text" id="email-username" class="form-control" placeholder="Enter your email / username">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password">
                <a href="forgot-password.php" class="forgot-password">Forgot Password</a>
            </div>
            <div class="sign-in-as">
                <span>Sign in as</span>
            </div>
            <div class="role-buttons">
                <button type="button" class="role-button tutor">Tutor</button>
                <button type="button" class="role-button student">Student</button>
            </div>
            <button type="submit" class="login-button">Login</button>
            <p class="sign-up-link">Don't have an account? <a href="register.php">Sign Up</a></p>
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
    </script>
</body>
</html>
