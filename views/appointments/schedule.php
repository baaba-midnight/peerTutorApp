<?php
// Start session and check if user is logged in
require_once '../../config/session.php';
require_once '../../config/database.php';

// Get the tutor ID from URL parameter
$tutorId = isset($_GET['tutor_id']) ? (int)$_GET['tutor_id'] : 0;

// If no tutor ID provided, redirect to search page
if ($tutorId === 0) {
    header('Location: ../../views/search/search-tutors.php');
    exit;
}

$conn = getConnection();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isStudent = $isLoggedIn && $_SESSION['role'] === 'student';

// Get tutor details
$tutorQuery = "SELECT u.*, 
    (SELECT AVG(r.rating) FROM Reviews r WHERE r.reviewee_id = u.user_id) as avg_rating,
    (SELECT COUNT(r.review_id) FROM Reviews r WHERE r.reviewee_id = u.user_id) as review_count,
    (SELECT location FROM Users WHERE user_id = u.user_id) as location
FROM Users u
WHERE u.user_id = ? AND u.role = 'tutor'";

$stmt = $conn->prepare($tutorQuery);
$stmt->bind_param('i', $tutorId);
$stmt->execute();
$tutorResult = $stmt->get_result();

if ($tutorResult->num_rows === 0) {
    header('Location: ../../views/search/search-tutors.php');
    exit;
}

$tutor = $tutorResult->fetch_assoc();

// Get tutor's subjects with hourly rates
$subjectsQuery = "SELECT s.subject_id, s.name, ts.hourly_rate, ts.experience_years
FROM Subjects s
JOIN TutorSubjects ts ON s.subject_id = ts.subject_id
WHERE ts.tutor_id = ?";

$stmt = $conn->prepare($subjectsQuery);
$stmt->bind_param('i', $tutorId);
$stmt->execute();
$subjectsResult = $stmt->get_result();
$subjects = [];
while ($row = $subjectsResult->fetch_assoc()) {
    $subjects[] = $row;
}

// Get tutor's availability
$availabilityQuery = "SELECT * FROM Availability WHERE tutor_id = ? ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";
$stmt = $conn->prepare($availabilityQuery);
$stmt->bind_param('i', $tutorId);
$stmt->execute();
$availabilityResult = $stmt->get_result();
$availability = [];
while ($row = $availabilityResult->fetch_assoc()) {
    $availability[] = $row;
}

$role = $isStudent ? 'student' : 'guest';
?>
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
        .badge-subject {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <?php include('../../includes/header.php'); ?>

    <div class="main-content">
        <div class="container py-4">
            <h1 class="mb-4">Schedule a Session</h1>

            <div class="row">
                <div class="col-md-4">
                    <!-- Tutor Information -->
                    <div class="tutor-info">
                        <div class="d-flex align-items-center mb-3">
                            <?php if ($tutor['profile_image']): ?>
                                <img src="../../<?php echo htmlspecialchars($tutor['profile_image']); ?>" alt="<?php echo htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']); ?>" class="rounded-circle me-3" width="64" height="64">
                            <?php else: ?>
                                <img src="../../assets/images/avatar.png" alt="<?php echo htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']); ?>" class="rounded-circle me-3" width="64" height="64">
                            <?php endif; ?>
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']); ?></h4>
                                <p class="mb-0 text-muted"><?php echo ucfirst(htmlspecialchars($tutor['role'])); ?></p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <span>
                                    <?php 
                                    if ($tutor['avg_rating']) {
                                        echo number_format($tutor['avg_rating'], 1) . ' (' . $tutor['review_count'] . ' reviews)';
                                    } else {
                                        echo 'No reviews yet';
                                    }
                                    ?>
                                </span>
                            </div>
                            <?php if (!empty($subjects)): ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2"></i>
                                <span>From $<?php echo number_format($subjects[0]['hourly_rate'], 2); ?>/hour</span>
                            </div>
                            <?php endif; ?>
                            <?php if ($tutor['location']): ?>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span><?php echo htmlspecialchars($tutor['location']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($subjects)): ?>
                        <hr>
                        <h5 class="mb-3">Subjects</h5>
                        <div class="mb-3">
                            <?php foreach ($subjects as $subject): ?>
                                <span class="badge bg-light text-dark me-2 mb-2 badge-subject"><?php echo htmlspecialchars($subject['name']); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($tutor['bio']): ?>
                        <hr>
                        <h5 class="mb-3">About</h5>
                        <p><?php echo nl2br(htmlspecialchars($tutor['bio'])); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($isStudent): ?>
                    <!-- Session Details Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Session Details</h5>
                            <form id="sessionDetailsForm">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="">Select a subject</option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?php echo htmlspecialchars($subject['subject_id']); ?>"><?php echo htmlspecialchars($subject['name']); ?> ($<?php echo number_format($subject['hourly_rate'], 2); ?>/hr)</option>
                                        <?php endforeach; ?>
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
                    <?php endif; ?>
                </div>

                <div class="col-md-8">
                    <!-- Calendar -->
                    <div class="calendar-wrapper mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Select Date & Time</h5>
                        </div>
                        <div id="calendar"></div>
                    </div>

                    <!-- Available Time Slots -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Available Time Slots</h5>
                            <div class="time-slots">
                                <p>Please select a date to see available time slots.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($isStudent): ?>
            <!-- Booking Summary -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3">Booking Summary</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Date:</strong> <span data-summary="date">-</span></p>
                                    <p class="mb-1"><strong>Time:</strong> <span data-summary="time">-</span></p>
                                    <p class="mb-1"><strong>Duration:</strong> <span data-summary="duration">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Subject:</strong> <span data-summary="subject">-</span></p>
                                    <p class="mb-1"><strong>Tutor:</strong> <span data-summary="tutor"><?php echo htmlspecialchars($tutor['first_name'] . " " . $tutor['last_name']); ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary btn-lg" id="confirmBooking" disabled>Confirm Booking</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Store tutor ID and other session data
        const tutorId = <?php echo $tutorId; ?>;
        const studentId = <?php echo $isStudent ? $_SESSION['user_id'] : 'null'; ?>;
        let selectedDate = '';
        let selectedTimeSlot = '';
        let selectedSubject = '';
        let selectedDuration = 60; // Default to 1 hour
        let selectedSubjectName = '';
        let hourlyRate = 0;

        // Initialize calendar with disabled dates based on tutor's availability
        const availableDays = [
            <?php 
            $daysMap = [
                'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 
                'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 0
            ];
            
            $availableDaysOfWeek = [];
            foreach ($availability as $slot) {
                $availableDaysOfWeek[] = $daysMap[$slot['day_of_week']];
            }
            echo implode(',', array_unique($availableDaysOfWeek));
            ?>
        ];

        flatpickr("#calendar", {
            inline: true,
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    // Disable dates where tutor is not available
                    return !availableDays.includes(date.getDay());
                }
            ],
            onChange: function(selectedDates, dateStr, instance) {
                selectedDate = dateStr;
                
                // Get day of week for the selected date
                const dayOfWeek = new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long' });
                
                // Fetch available time slots for the selected day
                fetchAvailableTimeSlots(dayOfWeek);
                
                // Update booking summary
                updateBookingSummary();
            }
        });

        // Fetch available time slots based on day of week
        function fetchAvailableTimeSlots(dayOfWeek) {
            // Clear existing time slots
            const timeSlotsContainer = document.querySelector('.time-slots');
            timeSlotsContainer.innerHTML = '';
            
            // Filter availability by day of week
            const daySlots = <?php echo json_encode($availability); ?>.filter(
                slot => slot.day_of_week === dayOfWeek
            );
            
            if (daySlots.length === 0) {
                timeSlotsContainer.innerHTML = '<p>No available time slots for this day</p>';
                return;
            }
            
            // Create time slot elements
            daySlots.forEach(slot => {
                const timeSlot = document.createElement('div');
                timeSlot.className = 'time-slot';
                timeSlot.setAttribute('data-start', slot.start_time);
                timeSlot.setAttribute('data-end', slot.end_time);
                
                // Format the time for display
                const startTime = formatTime(slot.start_time);
                const endTime = formatTime(slot.end_time);
                timeSlot.textContent = `${startTime} - ${endTime}`;
                
                // Add click event
                timeSlot.addEventListener('click', () => {
                    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    timeSlot.classList.add('selected');
                    selectedTimeSlot = {
                        start: slot.start_time,
                        end: slot.end_time,
                        displayStart: startTime,
                        displayEnd: endTime
                    };
                    updateBookingSummary();
                });
                
                timeSlotsContainer.appendChild(timeSlot);
            });
        }
        
        // Format time from 24h to 12h format
        function formatTime(time24h) {
            const [hours, minutes] = time24h.split(':');
            const hour = parseInt(hours, 10);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        // Handle subject selection
        document.getElementById('subject').addEventListener('change', function() {
            const subjectId = this.value;
            selectedSubject = subjectId;
            
            // Find the selected subject
            const subjects = <?php echo json_encode($subjects); ?>;
            const subject = subjects.find(s => s.subject_id === subjectId);
            
            if (subject) {
                selectedSubjectName = subject.name;
                hourlyRate = subject.hourly_rate;
                updateBookingSummary();
            }
        });

        // Handle duration selection
        document.getElementById('duration').addEventListener('change', function() {
            selectedDuration = parseInt(this.value, 10);
            updateBookingSummary();
        });

        // Update booking summary
        function updateBookingSummary() {
            if (!selectedDate || !selectedTimeSlot || !selectedSubject) {
                return; // Not all required fields are selected
            }
            
            // Calculate end time based on duration
            const endTime = calculateEndTime(selectedTimeSlot.start, selectedDuration);
            
            // Calculate price
            const price = calculatePrice(hourlyRate, selectedDuration);
            
            // Update summary elements
            document.querySelector('[data-summary="date"]').textContent = formatDate(selectedDate);
            document.querySelector('[data-summary="time"]').textContent = `${selectedTimeSlot.displayStart} - ${formatTime(endTime)}`;
            document.querySelector('[data-summary="duration"]').textContent = `${selectedDuration / 60} hour${selectedDuration > 60 ? 's' : ''}`;
            document.querySelector('[data-summary="subject"]').textContent = selectedSubjectName;
            document.querySelector('[data-summary="tutor"]').textContent = '<?php echo htmlspecialchars($tutor["first_name"] . " " . $tutor["last_name"]); ?>';
            document.querySelector('[data-summary="price"]').textContent = `$${price}`;
            
            // Enable the confirm button
            document.getElementById('confirmBooking').disabled = false;
        }
        
        // Format date for display
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric'
            });
        }
        
        // Calculate end time based on start time and duration (in minutes)
        function calculateEndTime(startTime, durationMinutes) {
            const [hours, minutes] = startTime.split(':').map(Number);
            
            let totalMinutes = hours * 60 + minutes + durationMinutes;
            const newHours = Math.floor(totalMinutes / 60);
            const newMinutes = totalMinutes % 60;
            
            return `${String(newHours).padStart(2, '0')}:${String(newMinutes).padStart(2, '0')}`;
        }
        
        // Calculate price based on hourly rate and duration
        function calculatePrice(rate, minutes) {
            return ((rate / 60) * minutes).toFixed(2);
        }

        // Handle booking confirmation
        document.getElementById('confirmBooking').addEventListener('click', async () => {
            // Disable the button to prevent multiple submissions
            const confirmButton = document.getElementById('confirmBooking');
            confirmButton.disabled = true;
            confirmButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Processing...
            `;
            
            try {
                // Calculate end time based on start time and duration
                const endTime = calculateEndTime(selectedTimeSlot.start, selectedDuration);
                
                // Create appointment data
                const appointmentData = {
                    student_id: studentId,
                    tutor_id: tutorId,
                    subject_id: selectedSubject,
                    start_datetime: `${selectedDate} ${selectedTimeSlot.start}`,
                    end_datetime: `${selectedDate} ${endTime}`,
                    notes: document.getElementById('topic').value
                };
                
                // Send appointment data to the server
                const response = await fetch('../../api/appointments.php?action=create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(appointmentData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message and redirect
                    alert('Booking confirmed successfully!');
                    window.location.href = 'view-appointments.php';
                } else {
                    // Show error message
                    alert(`Booking failed: ${result.error}`);
                    confirmButton.disabled = false;
                    confirmButton.innerHTML = 'Confirm Booking';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while booking the appointment. Please try again.');
                confirmButton.disabled = false;
                confirmButton.innerHTML = 'Confirm Booking';
            }
        });
    </script>
</body>
</html>