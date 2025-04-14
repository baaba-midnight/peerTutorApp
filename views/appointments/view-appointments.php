<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <link href="../../assets/css/main.css" rel="stylesheet">
    <link href="../../assets/css/header.css" rel="stylesheet">
    <link href="../../assets/css/dashboard.css" rel="stylesheet">
    <link href="../../assets/css/tutor-dashboard.css" rel="stylesheet">
</head>

<body>

    <?php
    $role = "tutor";
    include('../../includes/header.php');
    ?>

    <div class="main-content">
        <div class="appointments-page p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-calendar-alt me-2"></i>
                    Appointments
                </h2>
                <button class="btn btn-primary" id="addAvailabilityBtn">
                    <i class="fas fa-plus me-2"></i> Add Availability
                </button>
            </div>

            <div class="row">
                <!-- Calendar Section -->
                <div class="col-lg-8">
                    <div class="card mb-4 calendar-card">
                        <div class="card-body">
                            <div class="calendar-container">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary mb-3 w-100">
                                Add Availability
                            </button>
                            <button class="btn btn-outline-secondary mb-3 w-100">
                                Block Time Off
                            </button>
                            <button class="btn btn-outline-danger w-100">
                                Cancel Selected Sessions
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Appointment Settings</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Default Session Duration</label>
                                    <select class="form-select" id="sessionDuration">
                                        <option value="30">30 minutes</option>
                                        <option value="60" selected>1 hour</option>
                                        <option value="90">1.5 hours</option>
                                        <option value="120">2 hours</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Buffer Time Between Sessions</label>
                                    <select class="form-select" id="bufferTime">
                                        <option value="0">No buffer</option>
                                        <option value="10">10 minutes</option>
                                        <option value="15" selected>15 minutes</option>
                                        <option value="30">30 minutes</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Booking Notice</label>
                                    <select class="form-select" id="bookingNotice">
                                        <option value="0">No notice required</option>
                                        <option value="2">2 hours</option>
                                        <option value="24" selected>24 hours</option>
                                        <option value="48">48 hours</option>
                                    </select>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="allowScheduling" checked>
                                    <label class="form-check-label" for="allowScheduling">
                                        Allow students to book sessions
                                    </label>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="requireApproval">
                                    <label class="form-check-label" for="requireApproval">
                                        Require approval for bookings
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment List Section -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="appointmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                                Upcoming (2)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                                Past (1)
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="appointmentTabContent">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                            <!-- Upcoming Appointment 1 -->
                            <div class="appointment-item p-3 mb-2 border rounded d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Alex Johnson - Mathematics</h6>
                                    <div class="text-muted">
                                        2025-04-12 | 10:00 - 11:00
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-3">
                                        upcoming
                                    </span>
                                    <button class="btn btn-success btn-sm me-2" title="Start Session">
                                        <i class="fas fa-video me-1"></i> Start
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" title="Cancel">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                </div>
                            </div>

                            <!-- Upcoming Appointment 2 -->
                            <div class="appointment-item p-3 mb-2 border rounded d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Maria Garcia - Physics</h6>
                                    <div class="text-muted">
                                        2025-04-12 | 13:00 - 14:00
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-3">
                                        upcoming
                                    </span>
                                    <button class="btn btn-success btn-sm me-2" title="Start Session">
                                        <i class="fas fa-video me-1"></i> Start
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" title="Cancel">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="past" role="tabpanel">
                            <!-- Past Appointment -->
                            <div class="appointment-item p-3 mb-2 border rounded d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>James Smith - Chemistry</h6>
                                    <div class="text-muted">
                                        2025-04-10 | 15:00 - 16:00
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-3">
                                        completed
                                    </span>
                                    <button class="btn btn-outline-primary btn-sm">
                                        View Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample appointment data
            const appointments = [{
                    id: 1,
                    student: "Alex Johnson",
                    subject: "Mathematics",
                    date: "2025-04-12",
                    startTime: "10:00",
                    endTime: "11:00",
                    status: "upcoming"
                },
                {
                    id: 2,
                    student: "Maria Garcia",
                    subject: "Physics",
                    date: "2025-04-12",
                    startTime: "13:00",
                    endTime: "14:00",
                    status: "upcoming"
                },
                {
                    id: 3,
                    student: "James Smith",
                    subject: "Chemistry",
                    date: "2025-04-10",
                    startTime: "15:00",
                    endTime: "16:00",
                    status: "completed"
                }
            ];

            // Format appointments for FullCalendar
            const calendarEvents = appointments.map(appt => ({
                id: appt.id,
                title: `${appt.student} - ${appt.subject}`,
                start: `${appt.date}T${appt.startTime}`,
                end: `${appt.date}T${appt.endTime}`,
                color: appt.status === 'completed' ? '#28a745' : appt.status === 'upcoming' ? '#007bff' : '#dc3545'
            }));

            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'timeGrid', 'interaction'],
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: calendarEvents,
                eventClick: function(info) {
                    // Show appointment details
                    alert(`Appointment details for ID: ${info.event.id}`);
                },
                dateClick: function(info) {
                    // Handle date click
                    alert(`Selected date: ${info.dateStr}. Open availability modal.`);
                },
                height: 600,
                nowIndicator: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true
            });

            calendar.render();

            // Add event listener for Add Availability button
            document.getElementById('addAvailabilityBtn').addEventListener('click', function() {
                alert('Opening availability modal...');
            });

            // Handle start session buttons
            document.querySelectorAll('.btn-success[title="Start Session"]').forEach(button => {
                button.addEventListener('click', function() {
                    const appointmentItem = this.closest('.appointment-item');
                    const studentName = appointmentItem.querySelector('h6').textContent.split(' - ')[0];
                    alert(`Starting session with ${studentName}`);
                });
            });

            // Handle cancel buttons
            document.querySelectorAll('.btn-outline-danger[title="Cancel"]').forEach(button => {
                button.addEventListener('click', function() {
                    const appointmentItem = this.closest('.appointment-item');
                    const studentName = appointmentItem.querySelector('h6').textContent.split(' - ')[0];
                    if (confirm(`Are you sure you want to cancel appointment with ${studentName}?`)) {
                        alert(`Appointment with ${studentName} cancelled.`);
                    }
                });
            });
        });
    </script>
</body>

</html>