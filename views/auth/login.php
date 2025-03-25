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