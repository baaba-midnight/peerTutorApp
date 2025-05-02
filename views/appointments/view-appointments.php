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
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .rating-input input {
            display: none;
        }
        .rating-input label {
            cursor: pointer;
            font-size: 2rem;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffd700;
        }
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
    $role = 'student'; // Example role, replace with actual session variable
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
                    <div class="card appointment-card h-100" data-appointment-id="2">
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
                    <div class="card appointment-card h-100" data-appointment-id="3">
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
                    <div class="card appointment-card h-100" data-appointment-id="1">
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
                                <button class="btn btn-primary flex-grow-1 leave-review-btn" data-appointment-id="1">Leave Review</button>
                                <button class="btn btn-outline-secondary">View Notes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Modal -->
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Write Your Review</h4>
                                    <form id="reviewForm">
                                        <!-- Rating -->
                                        <div class="mb-4">
                                            <label class="form-label">Rating</label>
                                            <div class="rating-input">
                                                <input type="radio" id="star1" name="rating" value="1">
                                                <label for="star1" class="bi bi-star-fill"></label>
                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2" class="bi bi-star-fill"></label>
                                                <input type="radio" id="star3" name="rating" value="3">
                                                <label for="star3" class="bi bi-star-fill"></label>
                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4" class="bi bi-star-fill"></label>
                                                <input type="radio" id="star5" name="rating" value="5">
                                                <label for="star5" class="bi bi-star-fill"></label>
                                            </div>
                                        </div>

                                        <!-- Detailed Review -->
                                        <div class="mb-3">
                                            <label for="reviewText" class="form-label">Detailed Review</label>
                                            <textarea class="form-control" id="reviewText" rows="4" placeholder="Share your experience with the tutor"></textarea>
                                        </div>


                                        <!-- Anonymous Option -->
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="anonymous">
                                                <label class="form-check-label" for="anonymous">
                                                    Submit review anonymously
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
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

        // Handle Leave Review modal
        document.querySelectorAll('.leave-review-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                document.querySelectorAll('.leave-review-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                modal.show();
            });
        });

        // Optionally handle review form submission
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            // Collect review data
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const comment = document.getElementById('reviewText').value;
            const is_anonymous = document.getElementById('anonymous').checked ? 1 : 0;
            // Find the appointment id (assume modal opened from a button with data-appointment-id)
            const appointmentId = document.querySelector('.leave-review-btn.active')?.getAttribute('data-appointment-id');
            alert(appointmentId);
            if (!rating || !appointmentId) {
                alert('Please select a rating and appointment.');
                return;
            }
            // Optionally, get tutor_id if needed (could be set as data attribute)
            // For now, just send appointmentId, rating, comment, is_anonymous
            try {
                const response = await fetch('../../api/leave_review.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        appointment_id: appointmentId,
                        rating,
                        comment,
                        is_anonymous
                    })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Review submitted!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                    modal.hide();
                } else {
                    alert(result.message || 'Failed to submit review.');
                }
            } catch (err) {
                alert('Error submitting review.');
            }
        });
    </script>
</body>
</html>