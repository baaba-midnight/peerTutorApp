<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .appointment-card {
            transition: transform 0.2s;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            font-weight: normal;
            padding: 0.5rem 1rem;
        }
        .appointment-actions {
            gap: 0.5rem;
        }
        .stats-card {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Appointments</h1>
                <a href="schedule.php" class="btn btn-primary">Schedule New Session</a>
            </div>

            <!-- Appointment Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">5</div>
                        <div class="stats-label">Upcoming Sessions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">15</div>
                        <div class="stats-label">Completed Sessions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">25</div>
                        <div class="stats-label">Hours Learned</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">4.8</div>
                        <div class="stats-label">Average Rating</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="all">All Status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="subjectFilter">
                                <option value="all">All Subjects</option>
                                <option value="mathematics">Mathematics</option>
                                <option value="physics">Physics</option>
                                <option value="chemistry">Chemistry</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="timeFilter">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="this-week">This Week</option>
                                <option value="this-month">This Month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <h4 class="mb-3">Upcoming Appointments</h4>
            <div class="row g-4 mb-5">
                <!-- Sample Appointment Cards -->
                <div class="col-md-6">
                    <div class="card appointment-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Mathematics Session</h5>
                                    <p class="text-muted mb-0">with John Smith</p>
                                </div>
                                <span class="badge bg-primary status-badge">Upcoming</span>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="bi bi-calendar"></i>
                                    April 15, 2025
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i>
                                    10:00 AM - 11:00 AM
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-tag"></i>
                                    Calculus, Chain Rule
                                </p>
                            </div>
                            <div class="d-flex appointment-actions">
                                <button class="btn btn-primary flex-grow-1">Join Session</button>
                                <button class="btn btn-outline-primary">Reschedule</button>
                                <button class="btn btn-outline-danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card appointment-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Physics Session</h5>
                                    <p class="text-muted mb-0">with Sarah Johnson</p>
                                </div>
                                <span class="badge bg-warning status-badge">Pending</span>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="bi bi-calendar"></i>
                                    April 16, 2025
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i>
                                    2:00 PM - 3:00 PM
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-tag"></i>
                                    Mechanics, Forces
                                </p>
                            </div>
                            <div class="d-flex appointment-actions">
                                <button class="btn btn-primary flex-grow-1" disabled>Join Session</button>
                                <button class="btn btn-outline-primary">Reschedule</button>
                                <button class="btn btn-outline-danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Past Appointments -->
            <h4 class="mb-3">Past Appointments</h4>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card appointment-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Chemistry Session</h5>
                                    <p class="text-muted mb-0">with Michael Brown</p>
                                </div>
                                <span class="badge bg-success status-badge">Completed</span>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="bi bi-calendar"></i>
                                    April 10, 2025
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i>
                                    3:00 PM - 4:00 PM
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-tag"></i>
                                    Organic Chemistry
                                </p>
                            </div>
                            <div class="d-flex appointment-actions">
                                <a href="../reviews/give-reviews.php" class="btn btn-primary flex-grow-1">Leave Review</a>
                                <button class="btn btn-outline-secondary">View Notes</button>
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

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle filters
        const filters = ['statusFilter', 'subjectFilter', 'timeFilter'];
        filters.forEach(filter => {
            document.getElementById(filter).addEventListener('change', () => {
                // Add AJAX call to filter appointments
                console.log('Filtering appointments...');
            });
        });

        // Handle appointment actions
        document.querySelectorAll('.appointment-actions button').forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.textContent.toLowerCase();
                if (action === 'cancel') {
                    if (confirm('Are you sure you want to cancel this appointment?')) {
                        // Add AJAX call to cancel appointment
                        alert('Appointment cancelled successfully!');
                    }
                } else if (action === 'reschedule') {
                    window.location.href = 'schedule.php';
                } else if (action === 'join session') {
                    // Add logic to join virtual session
                    window.open('https://meet.example.com/session-id', '_blank');
                }
            });
        });
    </script>
</body>
</html>