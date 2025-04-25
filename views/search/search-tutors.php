<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Tutors - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .tutor-card {
            transition: transform 0.2s;
        }
        .tutor-card:hover {
            transform: translateY(-5px);
        }
        .rating {
            color:rgb(166, 143, 11);
        }
        .subject-badge {
            background-color: #e9ecef;
            color: #212529;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            margin: 0.25rem;
            display: inline-block;
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
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1>Find Your Perfect Tutor</h1>
                    <p class="text-muted">Search from our pool of qualified tutors</p>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search tutors..." id="searchInput">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Filters</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select" id="subjectFilter">
                                    <option value="">All Subjects</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="physics">Physics</option>
                                    <option value="chemistry">Chemistry</option>
                                    <option value="biology">Biology</option>
                                    <option value="computer-science">Computer Science</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <select class="form-select" id="ratingFilter">
                                    <option value="">Any Rating</option>
                                    <option value="4">4+ Stars</option>
                                    <option value="3">3+ Stars</option>
                                    <option value="2">2+ Stars</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price Range</label>
                                <select class="form-select" id="priceFilter">
                                    <option value="">Any Price</option>
                                    <option value="0-25">$0 - $25/hr</option>
                                    <option value="25-50">$25 - $50/hr</option>
                                    <option value="50-100">$50 - $100/hr</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Availability</label>
                                <select class="form-select" id="availabilityFilter">
                                    <option value="">Any Time</option>
                                    <option value="morning">Morning</option>
                                    <option value="afternoon">Afternoon</option>
                                    <option value="evening">Evening</option>
                                    <option value="weekend">Weekend</option>
                                </select>
                            </div>

                            <button class="btn btn-primary w-100" id="applyFilters">Apply Filters</button>
                        </div>
                    </div>
                </div>

                <!-- Tutors Grid -->
                <div class="col-md-9">
                    <div class="row g-4" id="tutorsGrid">
                        <!-- Sample Tutor Cards -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card tutor-card h-100">
                                <img src="../../assets/images/avatar.png" class="card-img-top" alt="Tutor">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">John Doe</h5>
                                        <span class="rating">
                                            <i class="bi bi-star-fill"></i>
                                            <span>4.8</span>
                                        </span>
                                    </div>
                                    <p class="card-text text-muted mb-2">Mathematics | Physics</p>
                                    <div class="mb-2">
                                        <span class="subject-badge">Calculus</span>
                                        <span class="subject-badge">Linear Algebra</span>
                                    </div>
                                    <p class="card-text"><small class="text-muted">$30/hour</small></p>
                                    <button class="btn btn-primary w-100">Schedule Session</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card tutor-card h-100">
                                <img src="../../assets/images/avatar.png" class="card-img-top" alt="Tutor">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">Jane Smith</h5>
                                        <span class="rating">
                                            <i class="bi bi-star-fill"></i>
                                            <span>4.9</span>
                                        </span>
                                    </div>
                                    <p class="card-text text-muted mb-2">Computer Science</p>
                                    <div class="mb-2">
                                        <span class="subject-badge">Python</span>
                                        <span class="subject-badge">Java</span>
                                        <span class="subject-badge">Web Dev</span>
                                    </div>
                                    <p class="card-text"><small class="text-muted">$40/hour</small></p>
                                    <button class="btn btn-primary w-100">Schedule Session</button>
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
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle search and filters
        document.getElementById('applyFilters').addEventListener('click', () => {
            const subject = document.getElementById('subjectFilter').value;
            const rating = document.getElementById('ratingFilter').value;
            const price = document.getElementById('priceFilter').value;
            const availability = document.getElementById('availabilityFilter').value;
            
            // Add AJAX call to filter tutors
            console.log('Applying filters:', { subject, rating, price, availability });
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value;
            // Add AJAX call to search tutors
            console.log('Searching:', searchTerm);
        });

        // Handle schedule session clicks
        document.querySelectorAll('.tutor-card button').forEach(button => {
            button.addEventListener('click', () => {
                // Add navigation to scheduling page
                window.location.href = '../appointments/schedule.php';
            });
        });
    </script>
</body>
</html>