<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .profile-header {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .stats-card {
            text-align: center;
            padding: 1rem;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-number {
            font-size: 2rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 0.5rem;
        }
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php 
    $role = 'student';
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="container py-4">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="../../assets/images/avatar.png" alt="Profile Picture" class="profile-image mb-3">
                        <button class="btn btn-outline-primary btn-sm">Change Photo</button>
                    </div>
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h1 class="mb-0">John Doe</h1>
                            <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
                        </div>
                        <p class="text-muted mb-2">Student</p>
                        <p class="mb-2"><i class="bi bi-geo-alt"></i> New York, USA</p>
                        <p class="mb-0"><i class="bi bi-calendar3"></i> Joined April 2025</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">15</div>
                        <div class="stats-label">Sessions Completed</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">4.8</div>
                        <div class="stats-label">Average Rating</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">3</div>
                        <div class="stats-label">Active Courses</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">25</div>
                        <div class="stats-label">Hours Learned</div>
                    </div>
                </div>
            </div>

            <!-- Profile Tabs -->
            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="about-tab" data-bs-toggle="tab" href="#about" role="tab">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="subjects-tab" data-bs-toggle="tab" href="#subjects" role="tab">Subjects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab">Reviews</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- About Tab -->
                <div class="tab-pane fade show active" id="about" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">About Me</h5>
                            <p>Computer Science student passionate about learning and helping others understand complex concepts. Interested in web development, artificial intelligence, and data science.</p>
                            
                            <h5 class="card-title mt-4">Contact Information</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-envelope"></i> john.doe@example.com</li>
                                <li class="mb-2"><i class="bi bi-phone"></i> +1 (555) 123-4567</li>
                                <li><i class="bi bi-globe"></i> portfolio-website.com</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Subjects Tab -->
                <div class="tab-pane fade" id="subjects" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Current Subjects</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">Mathematics</h6>
                                            <p class="card-text text-muted">Calculus, Linear Algebra</p>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 75%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">Physics</h6>
                                            <p class="card-text text-muted">Mechanics, Thermodynamics</p>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 60%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">Computer Science</h6>
                                            <p class="card-text text-muted">Programming, Algorithms</p>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 90%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recent Reviews</h5>
                            <div class="review-list">
                                <!-- Sample Review -->
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">Great student!</h6>
                                            <div class="text-warning mb-1">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                    <p class="mb-1">John is a dedicated learner who always comes prepared to sessions.</p>
                                    <small class="text-muted">By Sarah Johnson (Tutor)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
