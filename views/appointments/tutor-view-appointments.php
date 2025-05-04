<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../../views/auth/login.php');
    exit;
}
?>

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
        .rating-input label:hover~label,
        .rating-input input:checked~label {
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
    $role = $_SESSION['role']; 
    include('../../includes/header.php');
    ?>

    <input type="hidden" id="tutorId" value=<?php echo $_SESSION['id']?>>

    <div class="main-content">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Appointments</h1>
            </div>

            <!-- Upcoming Appointments -->
            <h4 class="mb-3">Upcoming Appointments</h4>
            <div class="row g-4 mb-5" id="upcomingAppointments"></div>

            <!-- Pending Appointments -->
            <h4 class="mb-3">Pending Appointments</h4>
            <div class="row g-4 mb-5" id="pendingAppointments"></div>

            <!-- Past Appointments -->
            <h4 class="mb-3">Past Appointments</h4>
            <div class="row g-4" id="pastAppointments"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/tutor/Appointments.js"></script>
    <script src="../../assets/js/activePage.js"></script>
</body>

</html>