<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
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
        .heading {
            font-size: 2.25rem;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 0.75rem;
        }
        .subheading {
            font-size: 1rem;
            color: #4a5568;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e0;
            font-size: 1rem;
            color: #4a5568;
            transition: border-color 0.15s ease-in-out, shadow-sm 0.15s ease-in-out;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .form-control::placeholder {
            color: #a0aec0;
        }
        .forgot-password {
            font-size: 0.875rem;
            color: #3b82f6;
            text-decoration: underline;
            display: block;
            text-align: right;
            margin-top: 0.5rem;
        }
        .forgot-password:hover {
            text-decoration: none;
        }
        .sign-in-as {
            font-size: 1rem;
            color: #1a202c;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding: 0.75rem 0;
        }
        .sign-in-as::before,
        .sign-in-as::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #e2e8f0;
        }
        .sign-in-as::before {
            left: 0;
        }
        .sign-in-as::after {
            right: 0;
        }
        .sign-in-as span {
            background-color: #fff;
            padding: 0 0.5rem;
            position: relative;
            z-index: 1;
        }

        .role-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .role-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            border: 2px solid #000;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
            margin: 0 0.5rem;
            background-color: #000;
            color: #fff;
        }
        .role-button:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
        }
        .role-button.tutor {
            color: #fff;
        }
        .role-button.student {
            color: #fff;
        }
        .login-button {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.375rem;
            background-color: #000;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out, transform 0.1s ease-in-out;
            margin-bottom: 1rem;
        }
        .login-button:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
        }
        .sign-up-link {
            font-size: 0.875rem;
            color: #4a5568;
            text-align: center;
        }
        .sign-up-link a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: underline;
        }
        .sign-up-link a:hover {
            text-decoration: none;
        }
    </style>
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
                <a href="#" class="forgot-password">Forgot Password</a>
            </div>

            <div class="sign-in-as">
                <span>Sign in as</span>
            </div>

            <div class="role-buttons">
                <button type="button" class="role-button student" data-role="student">Student</button>
                <button type="button" class="role-button tutor" data-role="tutor">Tutor</button>
            </div>
            <button type="submit" class="login-button">Login</button>
            <p class="sign-up-link">Don't have an account? <a href="#">Sign Up</a></p>
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
