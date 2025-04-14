<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="../../assets/css/tutor-dashboard.css">
</head>

<body>
    <?php
    $role = 'tutor';
    include('../../includes/header.php');
    ?>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p class="text-muted">Welcome back, Tutor. Here's what's happening today.</p>

        <!-- Stat Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        Today's Session
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">
                            5
                        </div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> Sessions scheduled for today
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        Unread Messages
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">12</div>
                        <div class="stat-trend trend-down">
                            <i class="fas fa-arrow-up me-1"></i> You have 12 unread messages
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        New Reviews
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">3</div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 3 new reviews received
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    Today's Schedule
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="card-body">
                    <table class="schedule">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10:00 AM - 11:00 AM</td>
                                <td>John Doe</td>
                                <td>Mathematics</td>
                                <td><span class="status completed">Confirmed</span></td>
                                <td>
                                    <button class="btn view">View</button>
                                    <button class="btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1:00 PM - 2:00 PM</td>
                                <td>Jane Smith</td>
                                <td>Physics</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>
                                    <button class="btn view">View</button>
                                    <button class="btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>10:00 AM - 11:00 AM</td>
                                <td>John Doe</td>
                                <td>Mathematics</td>
                                <td><span class="status completed">Confirmed</span></td>
                                <td>
                                    <button class="btn view">View</button>
                                    <button class="btn delete">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1:00 PM - 2:00 PM</td>
                                <td>Jane Smith</td>
                                <td>Physics</td>
                                <td><span class="status pending">Pending</span></td>
                                <td>
                                    <button class="btn view">View</button>
                                    <button class="btn delete">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="../../assets/js/activePage.js"></script>
</body>

</html>