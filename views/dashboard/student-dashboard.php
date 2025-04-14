<?php
$_SESSION['role'] = 2;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/student-dashboard.css">
</head>

<body>
    <?php 
    $role = 'student'; // temporary variable
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="student-welcome">
            <h2>Welcome Malcom!</h2>
            <p>Find the best tutors and manage your learning journey.</p>
        </div>

        <!-- Appointment Card -->
        <div class="card">
            <div class="card-header">
                <p>Upcoming Appointments</p>
                <a href="#">View All</a>
            </div>

            <div class="card-body">
                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Appointment with Jennifer</p>

                        <div class="title-meta">
                            <p>Mathematics</p>
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-status">Pending</button>
                        <button class="btn appointment-join">Join</button>
                    </div>
                </div>

                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Appointment with Jennifer</p>

                        <div class="title-meta">
                            <p>Mathematics</p>
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-status">Pending</button>
                        <button class="btn appointment-join">Join</button>
                    </div>
                </div>

                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Appointment with Jennifer</p>

                        <div class="title-meta">
                            <p>Mathematics</p>
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-status">Pending</button>
                        <button class="btn appointment-join">Join</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="card">
            <div class="card-header">
                <p>Unread Messages</p>
                <a href="#">View All</a>
            </div>

            <div class="card-body">
                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Jennifer Sent You A chat</p>

                        <div class="title-meta">
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-join">Open Message</button>
                    </div>
                </div>

                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Jennifer Sent You A chat</p>

                        <div class="title-meta">
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-join">Open Message</button>
                    </div>
                </div>

                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Jennifer Sent You A chat</p>

                        <div class="title-meta">
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-join">Open Message</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>