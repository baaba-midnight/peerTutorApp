<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .review-card {
            transition: transform 0.2s;
        }
        .review-card:hover {
            transform: translateY(-5px);
        }
        .rating {
            color:rgb(200, 173, 23);
        }
        .review-stats {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .review-filter {
            margin-bottom: 2rem;
        }
        .badge-subject {
            background-color: #e9ecef;
            color: #212529;
            font-weight: normal;
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Reviews</h1>
                <a href="give-reviews.php" class="btn btn-primary">Write a Review</a>
            </div>

            <!-- Review Statistics -->
            <div class="review-stats">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Reviews Given</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-number">3</div>
                        <div class="stat-label">Reviews Received</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="review-filter">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="typeFilter">
                            <option value="all">All Reviews</option>
                            <option value="given">Reviews Given</option>
                            <option value="received">Reviews Received</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="ratingFilter">
                            <option value="all">All Ratings</option>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="subjectFilter">
                            <option value="all">All Subjects</option>
                            <option value="mathematics">Mathematics</option>
                            <option value="physics">Physics</option>
                            <option value="chemistry">Chemistry</option>
                            <option value="biology">Biology</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="row g-4">
                <!-- Sample Review Cards -->
                <div class="col-md-6">
                    <div class="card review-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Excellent Tutoring Session!</h5>
                                    <div class="rating mb-2">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                                <span class="badge bg-primary">Given</span>
                            </div>
                            <p class="card-text">John was an excellent tutor! He explained complex concepts in a way that was easy to understand.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">Review for: John Smith</small><br>
                                    <small class="text-muted">Subject: <span class="badge badge-subject">Mathematics</span></small>
                                </div>
                                <small class="text-muted">April 10, 2025</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card review-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Great Student!</h5>
                                    <div class="rating mb-2">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </div>
                                </div>
                                <span class="badge bg-info">Received</span>
                            </div>
                            <p class="card-text">A dedicated learner who always comes prepared to sessions.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">Review from: Sarah Johnson</small><br>
                                    <small class="text-muted">Subject: <span class="badge badge-subject">Physics</span></small>
                                </div>
                                <small class="text-muted">April 8, 2025</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <?php  
    include('../../includes/footer.php');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle filters
        const filters = ['typeFilter', 'ratingFilter', 'subjectFilter'];
        filters.forEach(filter => {
            document.getElementById(filter).addEventListener('change', () => {
                // Add AJAX call to filter reviews
                console.log('Filtering reviews...');
            });
        });
    </script>
</body>
</html>