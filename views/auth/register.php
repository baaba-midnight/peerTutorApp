<?php
include '../../config/database.php';

// session_start();

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $email = trim($_POST['email']);
    $firstName = trim($_POST['fname']);
    $lastName = trim($_POST['lname']);
    $role = $_POST['role-options'];
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['password2']);

    //check if fields are empty
    if(empty($email)||empty($password) || empty($firstName) || empty($lastName)) {
        die("Dont leave field empty");
    }

    if ($confirmPassword !== $password) {
        echo "Passwords do not match";
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = 'SELECT user_id, first_name, last_name FROM Users WHERE email = ?';
    $stmt = $conn -> prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $results = $stmt -> get_result();

    if($results -> num_rows > 0){
        echo "<script>alert(User already registered)</script>";
    } else {
        $sql = "INSERT INTO Users (first_name, last_name, email, phone_number, password_hash, role)
                VALUES (?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss',$firstName, $lastName, $email, $phone, $hashedPassword, $role);

        if ($stmt->execute()) {
            header("Location: login.php");
            echo "<script>alert('Registration Successful')</script>";
        } else {
            echo "<script>alert('Registration Unsuccessful')</script>";
        }
    }
    $stmt -> close();
}
$conn -> close();