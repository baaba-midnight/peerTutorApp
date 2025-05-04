<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/student-dashboard.css">
    <style>
        .main-content {
            /* padding: 2rem; */
            padding-top: 7rem !important;
        }

        .main-content h2 {
            font-size: 2rem;
            font-weight: var(--poppins-bold);
            margin-bottom: 0.5rem;
            color: var(--primary-black);
        }

        .text-muted {
            font-size: 1.1rem !important;
            color: var(--primary-black) !important;
            opacity: 0.7 !important;
            margin-bottom: 2rem !important;
            text-align: left;
            margin-top: 0;
        }

        .table-container {
            flex: 1;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            font-size: 13px;
            color: var(--gray-dark);
            text-align: left;
        }

        th,
        td {
            padding: 12px 20px;
        }

        th:last-child,
        td:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid var(--gray-medium);
        }

        tbody tr:hover {
            background-color: var(--gray-light);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-black);
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-white);
            font-weight: bold;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            color: var(--primary-black);
        }

        .user-email {
            font-size: 13px;
            color: var(--gray-dark);
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <?php
    $role = 'tutor';
    include('../../includes/header.php');
    ?>

    <input type="hidden" id="tutorId" value=2>

    <div class="main-content">
        <h2>Welcome back, Tutor!</h2>
        <p class="text-muted">Manage your sessions and connect with students.</p>

        <!-- Upcoming Sessions -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <p>Upcoming Sessions</p>
                        <a href="../appointments/view-appointments.php">View All</a>
                    </div>
                    <div class="card-body" id="upcomingAppointments">
                        <!-- Will be dynamically inserted with JS -->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Student Messages -->
                <div class="card">
                    <div class="card-header">
                        <p>Recent Messages</p>
                        <a href="../messaging/chat.php">View All</a>
                    </div>
                    <div class="card-body" id="recentMessages">
                        <!-- Will be dynamically inserted with JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews & Ratings -->
        <div class="card" id="reviews-ratings">
            <div class="card-header">
                <h2 class="card-title">Recent Reviews</h2>
                <!-- <a href="../reviews/view-reviews.php">View All</a> -->
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recentReviews">
                            <!-- Will be dynamically inserted with JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/activePage.js"></script>
    <script src="../../assets/js/tutor/dashboardStats.js"></script>
</body>

</html>