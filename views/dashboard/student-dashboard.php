<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <link rel="stylesheet" href="../../assets/css/student-dashboard.css">
    <style>
        .table {
            font-family: var(--poppins-font);
        }

        .table thead {
            font-weight: var(--poppins-semi-bold);
        }

        .table tbody {
            font-weight: var(--poppins-regular);
        }

        /* Reviews & Ratings Page Styles */
        .rating-stars {
            display: inline-flex;
            gap: 0.25rem;
        }

        .rating-star {
            font-size: 1.25rem;
            color: #000;
        }

        .review-content {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #000;
            font-weight: var(--poppins-regular);
        }

        /* System Log Page Styles */
        .log-entry {
            border-bottom: 1px solid #000;
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: #000;
            font-weight: var(--poppins-regular);
        }

        .log-timestamp {
            display: block;
            font-size: 0.75rem;
            color: #000;
            opacity: 0.5;
            margin-bottom: 0.25rem;
            font-weight: var(--poppins-regular);
        }

        /* Backup & Restore Page Styles */
        .backup-restore-section {
            border: 1px solid #000;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .backup-restore-title {
            font-size: 1.125rem;
            font-weight: var(--poppins-semi-bold);
            color: #000;
            margin-bottom: 1rem;
        }

        .backup-restore-description {
            font-size: 0.9rem;
            color: #000;
            opacity: 0.5;
            margin-bottom: 1.5rem;
            font-weight: var(--poppins-regular);
        }

        .backup-restore-button {
            background-color: #000;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: var(--poppins-semi-bold);
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }

        .backup-restore-button:hover {
            background-color: #000;
        }

        .restore-warning {
            color: #000;
            font-size: 0.875rem;
            margin-top: 1rem;
            font-weight: var(--poppins-medium);
        }

        .student-welcome {
            margin-bottom: 2rem;
        }

        .student-welcome h2 {
            font-weight: var(--poppins-bold);
            color: #000;
            margin-bottom: 0.5rem;
        }

        .student-welcome p {
            font-weight: var(--poppins-regular);
            color: #000;
            opacity: 0.5;
        }

        .fw-semibold {
            font-weight: var(--poppins-semi-bold) !important;
        }

        .page-link {
            font-family: var(--poppins-font);
            font-weight: var(--poppins-regular);
            color: #000;
        }

        .page-item.active .page-link {
            background-color: #000;
            border-color: #000;
            font-weight: var(--poppins-medium);
        }

        .text-muted {
            font-family: var(--poppins-font);
            font-weight: var(--poppins-regular);
            color: #000 !important;
            opacity: 0.5;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: '../../api/appointments.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    let html = '';
                    if (data.appointments.length === 0) {
                        html = '<div class="alert alert-info">No appointments found.</div>';
                    } else {
                        data.appointments.forEach(function(app) {
                            html += `
                                <div class="card-section">
                                    <div class="card-info">
                                        <p class="title">Appointment with ${app.tutor_first_name}  ${app.tutor_last_name}</p>
                                        <div class="title-meta">
                                            <p>${app.course_name}</p>
                                            <p> ${app.start_datetime ? new Date(app.start_datetime).toLocaleDateString() : ''}</p>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <button class="btn appointment-status">${app.status}</button>
                                        <button class="btn appointment-join">Join</button>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#appointments').html(html);
                } else {
                    $('#appointments').html('<div class="alert alert-danger">Failed to load appointments.</div>');
                }
            },
            error: function() {
                $('#appointments').html('<div class="alert alert-danger">Failed to load appointments.</div>');
            }
        });

        // Fetch unread messages
        $.ajax({
            url: '../../api/get_unread_messages.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.status === 'success' && data.messages.length > 0) {
                    data.messages.forEach(function(msg) {
                        html += `
                            <div class="card-section">
                                <div class="card-info">
                                    <p class="title">${msg.sender_name} sent you a chat</p>
                                    <div class="title-meta">
                                        <p>${msg.sent_time}</p>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button class="btn appointment-join" onclick="window.location.href='../messaging/chat.php?contact_id=${msg.sender_id}'">Open Message</button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="alert alert-info">No unread messages.</div>';
                }
                $('#unreadMessages').html(html);
            },
            error: function() {
                $('#unreadMessages').html('<div class="alert alert-danger">Failed to load messages.</div>');
            }
        });
    });
    </script>
</head>

<body>
    <?php
    $role = 'student'; // temporary variable
    include('../../includes/header.php');
    ?>

    <div class="main-content">
        <div class="student-welcome">
            <h2>Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
            <p>Find the best tutors and manage your learning journey.</p>
        </div>

        
        <div class="card">
            <div class="card-header">
                <p>Upcoming Appointments</p>
                <a href="../appointments/schedule.php">View All</a>
            </div>

            <div class="card-body">
                <div id = "appointments">

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p>Unread Messages</p>
                <a href="../messaging/chat.php">View All</a>
            </div>

            <div class="card-body">
                <div id="unreadMessages"></div>
            </div>
        </div>

        <!-- <div class="card" id="system-log">
            <div class="card-header">
                <h2 class="card-title">System Log</h2>
            </div>
            <div class="card-body">
                <div class="log-entry">
                    <span class="log-timestamp">2025-03-10 10:00:00</span>
                    User John Smith logged in.
                </div>
                <div class="log-entry">
                    <span class="log-timestamp">2025-03-10 09:30:00</span>
                    User Sarah Johnson updated her profile.
                </div>
                <div class="log-entry">
                    <span class="log-timestamp">2025-03-09 14:00:00</span>
                    Database backup completed.
                </div>
                <div class="log-entry">
                    <span class="log-timestamp">2025-03-09 14:00:00</span>
                    Database backup completed.
                </div>
            </div>
            <div class="card-footer">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <p class="text-muted text-center">Showing 1-4 of 20 log entries</p>
            </div>
        </div>

        <div class="card" id="backup-restore">
            <div class="card-header">
                <h2 class="card-title">Backup & Restore</h2>
            </div>
            <div class="card-body">
                <div class="backup-restore-section">
                    <h3 class="backup-restore-title">Database Backup</h3>
                    <p class="backup-restore-description">
                        Create a backup of your database to ensure data safety.
                    </p>
                    <button class="backup-restore-button">Create Backup</button>
                </div>
                <div class="backup-restore-section">
                    <h3 class="backup-restore-title">Database Restore</h3>
                    <p class="backup-restore-description">
                        Restore your database from a previous backup.
                    </p>
                    <button class="backup-restore-button">Restore Database</button>
                    <p class="restore-warning">
                        Warning: Restoring the database will overwrite the current data.
                    </p>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>

    <script src="../../assets/js/activePage.js"></script>
</body>

</html>
