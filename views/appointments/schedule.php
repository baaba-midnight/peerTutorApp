<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Session - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .tutor-info {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .time-slot {
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .time-slot:hover {
            background-color: #f8f9fa;
        }
        .time-slot.selected {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }
        .calendar-wrapper {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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
            <h1 class="mb-4">Schedule a Session</h1>

            <div class="row">
                <div class="col-md-4">
                    <!-- Tutor Information -->
                    <div class="tutor-info">
                        <div class="d-flex align-items-center mb-3">
                            <img src="../../assets/images/avatar.png" alt="John Smith" class="rounded-circle me-3" width="64" height="64">
                            <div>
                                <h4 class="mb-1">John Smith</h4>
                                <p class="mb-0 text-muted">Mathematics Tutor</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <span>4.8 (120 reviews)</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2"></i>
                                <span>$30/hour</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>New York, USA</span>
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-3">Subjects</h5>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-2 mb-2">Calculus</span>
                            <span class="badge bg-light text-dark me-2 mb-2">Linear Algebra</span>
                            <span class="badge bg-light text-dark me-2 mb-2">Statistics</span>
                        </div>
                    </div>

                    <!-- Session Details Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Session Details</h5>
                            <form id="sessionDetailsForm">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="calculus">Calculus</option>
                                        <option value="linear-algebra">Linear Algebra</option>
                                        <option value="statistics">Statistics</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration</label>
                                    <select class="form-select" id="duration" required>
                                        <option value="30">30 minutes</option>
                                        <option value="60" selected>1 hour</option>
                                        <option value="90">1.5 hours</option>
                                        <option value="120">2 hours</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="topic" class="form-label">Specific Topics</label>
                                    <textarea class="form-control" id="topic" rows="3" placeholder="What would you like to focus on?"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Calendar -->
                    <div class="calendar-wrapper mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Select Date & Time</h5>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary" id="prevWeek">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="btn btn-outline-secondary" id="nextWeek">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </div>

                    <!-- Available Time Slots -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Available Time Slots</h5>
                            <div class="time-slots">
                                <div class="time-slot">9:00 AM - 10:00 AM</div>
                                <div class="time-slot">10:00 AM - 11:00 AM</div>
                                <div class="time-slot">11:00 AM - 12:00 PM</div>
                                <div class="time-slot">2:00 PM - 3:00 PM</div>
                                <div class="time-slot">3:00 PM - 4:00 PM</div>
                                <div class="time-slot">4:00 PM - 5:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3">Booking Summary</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Date:</strong> April 15, 2025</p>
                                    <p class="mb-1"><strong>Time:</strong> 10:00 AM - 11:00 AM</p>
                                    <p class="mb-1"><strong>Duration:</strong> 1 hour</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Subject:</strong> Calculus</p>
                                    <p class="mb-1"><strong>Tutor:</strong> John Smith</p>
                                    <p class="mb-1"><strong>Price:</strong> $30</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary btn-lg">Confirm Booking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize calendar
        flatpickr("#calendar", {
            inline: true,
            minDate: "today",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                // Add AJAX call to fetch available time slots
                console.log('Selected date:', dateStr);
            }
        });

        // Handle time slot selection
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', () => {
                document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                slot.classList.add('selected');
                // Update booking summary
                updateBookingSummary();
            });
        });

        // Handle session details form changes
        document.getElementById('sessionDetailsForm').addEventListener('change', () => {
            updateBookingSummary();
        });

        function updateBookingSummary() {
            // Add logic to update booking summary
            console.log('Updating booking summary...');
        }

        // Handle booking confirmation
        document.querySelector('.btn-primary').addEventListener('click', () => {
            // Add AJAX call to submit booking
            alert('Booking confirmed successfully!');
            window.location.href = 'view-appointments.php';
        });
    </script>
</body>
</html>