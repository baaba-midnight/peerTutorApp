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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
</head>

<body>
    <?php
    $role = "admin";
    include('../../includes/header.php');
    ?>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p class="text-muted">Welcome back, Admin User. Here's what's happening today.</p>

        <!-- Stat Cards Row -->
        <div class="row g-4 mb-4">
            <!-- Active Tutors -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">
                        Active Tutors
                        <i class="fa fa-user float-end"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">124</div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 8.5% vs Last Month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Students -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">
                        Active Students
                        <i class="fa fa-user float-end"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">3842</div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 12% vs Last Month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Sessions -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">
                        Completed Sessions
                        <i class="fas fa-check-circle float-end"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">1292</div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 4.2% vs Last Month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">
                        Avg Rating
                        <i class="fas fa-star float-end"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number">4.8</div>
                        <div class="stat-trend trend-up">
                            <i class="fas fa-arrow-up me-1"></i> 0.2% vs Last Month
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Second Row -->
        <div class="row g-4 mb-4">

            <!-- Pending Actions -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Pending Actions
                        <a href="#" class="text-decoration-none">View All</a>
                    </div>

                    <div class="card-body">
                        <div class="pending-item">
                            <div>
                                <i class="fas fa-circle-info me-2"></i>
                                Tutor Applications
                            </div>
                            <div>
                                <span class="badge bg-secondary">12 items require attention</span>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button class="btn btn-sm btn-outline-secondary">Review</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Rated Tutors -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Top Rated Tutors
                        <a href="#" class="text-decoration-none">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="top-tutor p-3">
                            <div class="user-avatar">JL</div>
                            <div class="tutor-info">
                                <p class="tutor-name">Jennifer Lawrence</p>
                                <p class="tutor-subject">Computer Science</p>
                            </div>
                            <div class="tutor-rating">
                                <i class="fas fa-star"></i> 4.9
                            </div>
                        </div>
                        <div class="top-tutor p-3">
                            <div class="user-avatar">RA</div>
                            <div class="tutor-info">
                                <p class="tutor-name">Rahina Abban</p>
                                <p class="tutor-subject">Writing Center</p>
                            </div>
                            <div class="tutor-rating">
                                <i class="fas fa-star"></i> 4.8
                            </div>
                        </div>
                        <div class="top-tutor p-3">
                            <div class="user-avatar">CA</div>
                            <div class="tutor-info">
                                <p class="tutor-name">Christopher Appiah</p>
                                <p class="tutor-subject">Calculus</p>
                            </div>
                            <div class="tutor-rating">
                                <i class="fas fa-star"></i> 4.7
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header">
                        Recent Activity
                    </div>
                    <div class="card-body">
                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <div>New Tutor Application From Robert Wilson</div>
                                <div class="activity-time">15 minutes ago</div>
                            </div>
                        </div>
                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <div>Student Sarah Has Booked A Meeting With Joyce</div>
                                <div class="activity-time">30 minutes ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/activePage.js"></script>
</body>

</html>