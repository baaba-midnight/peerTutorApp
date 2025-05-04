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
    session_start();
    if (!isset($_SESSION['role'])) {
        header('Location: ../../views/auth/login.php');
        exit;
    }
    $role = $_SESSION['role'];
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

                            

                            <button class="btn btn-primary w-100" id="applyFilters">Apply Filters</button>
                        </div>
                    </div>
                </div>

                <!-- Tutors Grid -->
                <div class="col-md-9">
                    <div class="row g-4" id="tutorsGrid">
                        <!-- Tutors will be loaded here by AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Book Session Modal -->
    <div class="modal fade" id="bookSessionModal" tabindex="-1" aria-labelledby="bookSessionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bookSessionModalLabel">Book a Session</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="bookSessionForm">
            <div class="modal-body">
              <input type="hidden" id="modalTutorId" name="tutor_id">
              <div class="mb-3">
                <label for="modalCourse" class="form-label">Course/Subject</label>
                <select class="form-select" id="modalCourse" name="course_id" required>
                  <option value="">Select a course</option>
                  <!-- Options will be loaded dynamically -->
                </select>
              </div>
              <div class="mb-3">
                <label for="modalAvailableDay" class="form-label">Available Day</label>
                <select class="form-select" id="modalAvailableDay" name="available_day" required>
                  <option value="">Select a day</option>
                  <!-- Days will be loaded dynamically -->
                </select>
              </div>
              <div class="mb-3" id="timeSlotContainer">
                <label for="modalTimeSlot" class="form-label">Available Time Slots</label>
                <select class="form-select" id="modalTimeSlot" name="time_slot" required>
                  <option value="">Select a time slot</option>
                  <!-- Time slots will be loaded dynamically -->
                </select>
              </div>
              <div class="mb-3">
                <label for="modalLocation" class="form-label">Meeting Link</label>
                <input type="text" class="form-control" id="modalLocation" name="link" placeholder="e.g. Zoom link, Google Meet" required>
              </div>
              <div class="mb-3">
                <label for="modalNotes" class="form-label">Notes (optional)</label>
                <textarea class="form-control" id="modalNotes" name="session_notes" rows="2" placeholder="Anything you'd like the tutor to know?"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Book Session</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
        // Fetch and display all tutors on page load
        $(document).ready(function() {
            $.ajax({
                url: '../../api/search.php',
                method: 'GET',
                data: { role: 'tutor' },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(function(tutor) {
                            html += `
                            <div class="col-md-6 col-lg-4">
                                <div class="card tutor-card h-100" data-tutor-id="${tutor.user_id}">
                                    <img src="../../${tutor.profile_picture_url || 'assets/images/avatar.png'}" class="card-img-top" alt="Tutor">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">${tutor.first_name} ${tutor.last_name}</h5>
                                            <span class="rating">
                                                <i class="bi bi-star-fill"></i>
                                                <span>${tutor.overall_rating ? parseFloat(tutor.overall_rating).toFixed(1) : 'N/A'}</span>
                                            </span>
                                        </div>
                                        <p class="card-text text-muted mb-2">${tutor.department || ''}</p>
                                        <div class="mb-2">
                                            ${(tutor.courses_offered ? tutor.courses_offered.split(',').map(course => `<span class='subject-badge'>${course.trim()}</span>`).join('') : '')}
                                        </div>
                                        <button class="btn btn-primary w-100">Schedule Session</button>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $('#tutorsGrid').html(html);
                    } else {
                        $('#tutorsGrid').html('<div class="col-12"><div class="alert alert-info">No tutors found.</div></div>');
                    }
                },
                error: function() {
                    $('#tutorsGrid').html('<div class="col-12"><div class="alert alert-danger">Failed to load tutors.</div></div>');
                }
            });

            // Handle schedule session clicks (delegated for dynamic content)
            $(document).on('click', '.tutor-card button', function() {
                const card = $(this).closest('.tutor-card');
                const tutorId = card.data('tutor-id') || card.find('input[name="tutor_id"]').val() || card.attr('data-tutor-id');
                $('#modalTutorId').val(tutorId);
                
                // Clear previous selections
                $('#modalAvailableDay').empty().append('<option value="">Select a day</option>');
                $('#modalTimeSlot').empty().append('<option value="">Select a time slot</option>');
                
                // Fetch courses for this tutor and populate the dropdown
                $.ajax({
                    url: '../../api/get_tutor_courses.php',
                    method: 'GET',
                    data: { tutor_id: tutorId },
                    dataType: 'json',
                    success: function(response) {
                        var $dropdown = $('#modalCourse');
                        $dropdown.empty();
                        $dropdown.append('<option value="">Select a course</option>');
                        if (response.status === 'success' && response.courses.length > 0) {
                            response.courses.forEach(function(course) {
                                var text = course.course_code ? (course.course_code + ' - ' + course.course_name) : course.course_name;
                                $dropdown.append('<option value="' + course.course_id + '">' + text + '</option>');
                            });
                        } else {
                            $dropdown.append('<option value="">No courses found</option>');
                        }
                    },
                    error: function() {
                        var $dropdown = $('#modalCourse');
                        $dropdown.empty();
                        $dropdown.append('<option value="">Failed to load courses</option>');
                    }
                });
                
                // Fetch tutor availability and populate days dropdown
                $.ajax({
                    url: '../../api/get_tutor_availability.php',
                    method: 'GET',
                    data: { tutor_id: tutorId },
                    dataType: 'json',
                    success: function(response) {
                        var $dayDropdown = $('#modalAvailableDay');
                        $dayDropdown.empty();
                        $dayDropdown.append('<option value="">Select a day</option>');
                        
                        if (response.status === 'success' && response.availability && Object.keys(response.availability).length > 0) {
                            // Store availability data for later use
                            window.tutorAvailability = response.availability;
                            
                            // Sort days in correct order
                            const dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            const availableDays = Object.keys(response.availability).filter(day => 
                                response.availability[day] && 
                                response.availability[day].toLowerCase() !== 'not available'
                            );
                            
                            availableDays.sort((a, b) => dayOrder.indexOf(a) - dayOrder.indexOf(b));
                            
                            // Add available days to dropdown
                            availableDays.forEach(function(day) {
                                $dayDropdown.append('<option value="' + day + '">' + day + '</option>');
                            });
                        } else {
                            $dayDropdown.append('<option value="">No availability found</option>');
                        }
                    },
                    error: function() {
                        var $dayDropdown = $('#modalAvailableDay');
                        $dayDropdown.empty();
                        $dayDropdown.append('<option value="">Failed to load availability</option>');
                    }
                });
                
                // Show the modal
                $('#bookSessionModal').modal('show');
            });
            
            // Handle day selection - populate time slots
            $('#modalAvailableDay').on('change', function() {
                const selectedDay = $(this).val();
                var $timeDropdown = $('#modalTimeSlot');
                $timeDropdown.empty();
                $timeDropdown.append('<option value="">Select a time slot</option>');
                
                if (selectedDay && window.tutorAvailability && window.tutorAvailability[selectedDay]) {
                    // Get time ranges for the selected day
                    const timeRanges = window.tutorAvailability[selectedDay].split(',');
                    
                    timeRanges.forEach(function(timeRange, index) {
                        timeRange = timeRange.trim();
                        
                        if (timeRange.toLowerCase() === 'not available') {
                            return;
                        }
                        
                        // Parse time range (e.g., "9:00 AM - 12:00 PM")
                        const parts = timeRange.split('-');
                        if (parts.length !== 2) {
                            return;
                        }
                        
                        const startTimeStr = parts[0].trim();
                        const endTimeStr = parts[1].trim();
                        
                        // Create date objects for start and end times
                        const startTime = parseTimeString(startTimeStr);
                        const endTime = parseTimeString(endTimeStr);
                        
                        if (!startTime || !endTime) {
                            return;
                        }
                        
                        // Generate time slots in 1-hour increments
                        let slotStart = new Date(startTime);
                        while (slotStart < endTime) {
                            // Calculate slot end (1 hour later)
                            let slotEnd = new Date(slotStart);
                            slotEnd.setHours(slotEnd.getHours() + 1);
                            
                            // Only add complete hour slots that fit within availability window
                            if (slotEnd <= endTime) {
                                const formattedStart = formatTime(slotStart);
                                const formattedEnd = formatTime(slotEnd);
                                const valueString = `${selectedDay}|${formatTimeForDb(slotStart)}|${formatTimeForDb(slotEnd)}`;
                                $timeDropdown.append(`<option value="${valueString}">${formattedStart} - ${formattedEnd}</option>`);
                            }
                            
                            // Move to next slot
                            slotStart.setHours(slotStart.getHours() + 1);
                        }
                    });
                    
                    if ($timeDropdown.find('option').length <= 1) {
                        $timeDropdown.append('<option value="">No time slots available for this day</option>');
                    }
                }
            });
            
            // Helper function to parse time strings like "9:00 AM" or "2:30 PM"
            function parseTimeString(timeStr) {
                try {
                    const [timePart, ampm] = timeStr.split(/\s+/);
                    let [hours, minutes] = timePart.split(':').map(Number);
                    
                    if (ampm.toUpperCase() === 'PM' && hours < 12) {
                        hours += 12;
                    } else if (ampm.toUpperCase() === 'AM' && hours === 12) {
                        hours = 0;
                    }
                    
                    const date = new Date();
                    date.setHours(hours, minutes || 0, 0, 0);
                    return date;
                } catch (e) {
                    console.error('Error parsing time:', timeStr, e);
                    return null;
                }
            }

            // Helper function to format time for display
            function formatTime(date) {
                let hours = date.getHours();
                let minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                
                hours = hours % 12;
                hours = hours ? hours : 12; // Convert 0 to 12
                minutes = minutes < 10 ? '0' + minutes : minutes;
                
                return hours + ':' + minutes + ' ' + ampm;
            }
            
            // Helper function to format time for database storage
            function formatTimeForDb(date) {
                let hours = date.getHours();
                let minutes = date.getMinutes();
                
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                
                return hours + ':' + minutes + ':00';
            }

            // Handle book session form submission
            $('#bookSessionForm').on('submit', function(e) {
                e.preventDefault();
                
                // Get the selected time slot
                const timeSlotValue = $('#modalTimeSlot').val();
                if (!timeSlotValue) {
                    alert('Please select a time slot');
                    return;
                }
                
                // Parse the time slot value
                const [day, start_time, end_time] = timeSlotValue.split('|');
                
                // Get the course ID
                const course_id = $('#modalCourse').val();
                
                // Get the tutor ID
                const tutor_id = $('#modalTutorId').val();
                
                // Meeting link and notes
                const link = $('#modalLocation').val();
                const notes = $('#modalNotes').val();
                
                // Create a date object for the selected day and time
                const today = new Date();
                const dayOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const currentDay = dayOfWeek[today.getDay()];
                
                // Calculate the day offset
                let dayOffset = dayOfWeek.indexOf(day) - dayOfWeek.indexOf(currentDay);
                if (dayOffset <= 0) {
                    dayOffset += 7; // Next week if day has already passed
                }
                
                // Set the date for the selected day
                const sessionDate = new Date(today);
                sessionDate.setDate(today.getDate() + dayOffset);
                
                // Format the date for the API
                const year = sessionDate.getFullYear();
                const month = String(sessionDate.getMonth() + 1).padStart(2, '0');
                const date = String(sessionDate.getDate()).padStart(2, '0');
                
                // Create the datetime strings
                const start_datetime = `${year}-${month}-${date} ${start_time}`;
                const end_datetime = `${year}-${month}-${date} ${end_time}`;
                
                // Create the form data
                const formData = {
                    tutor_id: tutor_id,
                    course_id: course_id,
                    start_datetime: start_datetime,
                    end_datetime: end_datetime,
                    link: link,
                    session_notes: notes
                };
                
                // Submit the booking
                $.ajax({
                    url: '../../api/book_session.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Session booked successfully!');
                            $('#bookSessionModal').modal('hide');
                            // Optionally, refresh tutors or user sessions
                        } else {
                            alert('Failed to book session: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error booking session. Please try again.');
                    }
                });
            });
            
            // Handle filters
            $('#applyFilters').on('click', function() {
                const subject = $('#subjectFilter').val();
                const rating = $('#ratingFilter').val();
                
                // Add AJAX call to filter tutors
                console.log('Applying filters:', { subject, rating });
            });
        });
    </script>
</body>
</html>