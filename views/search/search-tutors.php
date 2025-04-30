<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

// Initialize database connection
$conn = getConnection();

// Get all subjects for the filter dropdown
$subjectsQuery = "SELECT DISTINCT name, subject_id FROM Subjects ORDER BY name";
$subjectResult = $conn->query($subjectsQuery);
$subjects = [];
if ($subjectResult) {
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Get tutors with their info
$tutorsQuery = "SELECT u.user_id, u.first_name, u.last_name, u.profile_image, u.location,
                (SELECT AVG(r.rating) FROM Reviews r WHERE r.reviewee_id = u.user_id) as avg_rating,
                (SELECT COUNT(r.review_id) FROM Reviews r WHERE r.reviewee_id = u.user_id) as review_count
                FROM Users u
                WHERE u.role = 'tutor'
                ORDER BY avg_rating DESC";
$tutorResult = $conn->query($tutorsQuery);
$tutors = [];
if ($tutorResult) {
    while ($row = $tutorResult->fetch_assoc()) {
        $tutor_id = $row['user_id'];
        
        // Get subjects for this tutor
        $tutorSubjectsQuery = "SELECT s.name, ts.hourly_rate 
                              FROM TutorSubjects ts 
                              JOIN Subjects s ON ts.subject_id = s.subject_id 
                              WHERE ts.tutor_id = $tutor_id";
        $subjectsResult = $conn->query($tutorSubjectsQuery);
        $tutorSubjects = [];
        $minRate = 999999;
        
        if ($subjectsResult) {
            while ($subjectRow = $subjectsResult->fetch_assoc()) {
                $tutorSubjects[] = $subjectRow;
                if ($subjectRow['hourly_rate'] < $minRate) {
                    $minRate = $subjectRow['hourly_rate'];
                }
            }
        }
        
        $row['subjects'] = $tutorSubjects;
        $row['min_rate'] = ($minRate < 999999) ? $minRate : 0;
        $tutors[] = $row;
    }
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
?>
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
        .tutor-img-container {
            height: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .tutor-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-results {
            padding: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include('../../includes/header.php'); ?>

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
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= $subject['subject_id'] ?>"><?= $subject['name'] ?></option>
                                    <?php endforeach; ?>
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
                        <?php if (empty($tutors)): ?>
                            <div class="no-results">
                                <p>No tutors found. Please adjust your filters or try again later.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($tutors as $tutor): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card tutor-card h-100">
                                        <div class="tutor-img-container">
                                            <img src="<?= $tutor['profile_image'] ?: '../../assets/images/avatar.png' ?>" alt="Tutor">
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?= $tutor['first_name'] . ' ' . $tutor['last_name'] ?></h5>
                                                <span class="rating">
                                                    <i class="bi bi-star-fill"></i>
                                                    <span><?= number_format($tutor['avg_rating'] ?: 0, 1) ?></span>
                                                </span>
                                            </div>
                                            <p class="card-text text-muted mb-2"><?= $tutor['location'] ?: 'Location not specified' ?></p>
                                            <div class="mb-2">
                                                <?php if (empty($tutor['subjects'])): ?>
                                                    <span class="subject-badge">No subjects listed</span>
                                                <?php else: ?>
                                                    <?php foreach ($tutor['subjects'] as $subject): ?>
                                                        <span class="subject-badge"><?= $subject['name'] ?></span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <p class="card-text"><small class="text-muted">$<?= $tutor['min_rate'] ?>/hour</small></p>
                                            <a href="../appointments/schedule.php?tutor_id=<?= $tutor['user_id'] ?>" class="btn btn-primary w-100">Schedule Session</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
        document.querySelectorAll('.tutor-card a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tutorId = new URL(link.href).searchParams.get('tutor_id');
                // Add navigation to scheduling page
                window.location.href = `../appointments/schedule.php?tutor_id=${tutorId}`;
            });
        });
    </script>
</body>
</html>