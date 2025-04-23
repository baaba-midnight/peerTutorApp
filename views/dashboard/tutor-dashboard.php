<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
<<<<<<< HEAD
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/student-dashboard.css">
    <style>
        .earnings-card {
            background: linear-gradient(45deg, #000, #333);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .earnings-amount {
            font-size: 2rem;
            font-weight: var(--poppins-bold);
            margin: 1rem 0;
        }
        .availability-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .availability-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .availability-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .availability-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .availability-slider {
            background-color: #000;
        }
        input:checked + .availability-slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body>
    <?php
    $role = 'tutor';
    include('../../includes/header.php');
    ?>

    <div class="main-content">
        <div class="student-welcome">
            <h2>Welcome back, Tutor!</h2>
            <p>Manage your sessions and connect with students.</p>
        </div>

        <!-- Availability Toggle and Earnings -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Current Availability</h2>
                    </div>
                    <div class="card-body">
                        <label class="availability-toggle">
                            <input type="checkbox" checked>
                            <span class="availability-slider"></span>
                        </label>
                        <p class="mt-3">You are currently showing as available for new sessions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="earnings-card">
                    <h3>Total Earnings</h3>
                    <div class="earnings-amount">$1,240.00</div>
                    <p>This month</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="card">
            <div class="card-header">
                <p>Upcoming Sessions</p>
                <a href="../appointments/view-appointments.php">View All</a>
            </div>
            <div class="card-body">
                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Session with Alex Smith</p>
                        <div class="title-meta">
                            <p>Mathematics - Calculus</p>
                            <p>Monday, 10:00 AM</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-status">Confirmed</button>
                        <button class="btn appointment-join">Start Session</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Messages -->
        <div class="card">
            <div class="card-header">
                <p>Recent Messages</p>
                <a href="../messaging/chat.php">View All</a>
            </div>
            <div class="card-body">
                <div class="card-section">
                    <div class="card-info">
                        <p class="title">Message from Sarah</p>
                        <div class="title-meta">
                            <p>Question about tomorrow's session</p>
                            <p>5 minutes ago</p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-join">Reply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews & Ratings -->
        <div class="card" id="reviews-ratings">
            <div class="card-header">
                <h2 class="card-title">Recent Reviews</h2>
                <a href="../reviews/view-reviews.php">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="initials rounded-circle bg-primary text-white fw-semibold">AS</span>
                                        <div>
                                            <div class="fw-semibold">Alex Smith</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="rating-stars">
                                        <i class="fas fa-star rating-star"></i>
                                        <i class="fas fa-star rating-star"></i>
                                        <i class="fas fa-star rating-star"></i>
                                        <i class="fas fa-star rating-star"></i>
                                        <i class="fas fa-star rating-star"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-content">Excellent teaching style and very patient!</div>
                                </td>
                                <td>14 Apr, 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle availability toggle
        const availabilityToggle = document.querySelector('.availability-toggle input');
        availabilityToggle.addEventListener('change', function() {
            // TODO: Add AJAX call to update availability status
            const status = this.checked ? 'available' : 'unavailable';
            console.log('Availability status changed to:', status);
        });

        document.querySelectorAll('.appointment-join').forEach(button => {
            button.addEventListener('click', function() {
                // TODO: Add logic to start session or open chat
                const action = this.textContent.toLowerCase();
                if (action.includes('start')) {
                    window.location.href = '../appointments/view-appointments.php';
                } else if (action.includes('reply')) {
                    window.location.href = '../messaging/chat.php';
                }
            });
        });
    </script>
=======

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

    <? include '../../includes/footer.php'; ?>
>>>>>>> 7ab1668b5ca453233f9563c1affe63fad6ac59a5
</body>

</html>