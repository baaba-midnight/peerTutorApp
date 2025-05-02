<?php
session_start();
require_once  '../../config/database.php';
require '../appointments/update_appointment.php';



$role = 'student';
include('../../includes/header.php');



$user_id = $_SESSION['user_id'];
$pdo = getConnection();

// Get filters if set
$status_filter = $_GET['status'] ?? '';
$subject_filter = $_GET['subject'] ?? '';
$time_filter = $_GET['time'] ?? '';

// Build SQL query with filters
$sql = "SELECT a.*, 
            s.name AS subject_name, 
            u.first_name AS student_first_name, 
            u.last_name AS student_last_name
        FROM Appointments a
        JOIN Subjects s ON a.subject_id = s.subject_id
        JOIN Users u ON a.student_id = u.user_id
        WHERE a.tutor_id = ?";

$params = [$user_id];

// Add status filter if set
if (!empty($status_filter) && $status_filter !== 'all') {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
}

// Add subject filter if set
if (!empty($subject_filter) && $subject_filter !== 'all') {
    $sql .= " AND s.name = ?";
    $params[] = $subject_filter;
}

// Add time filter if set
if (!empty($time_filter) && $time_filter !== 'all') {
    $current_date = date('Y-m-d');
    if ($time_filter === 'today') {
        $sql .= " AND DATE(a.start_datetime) = ?";
        $params[] = $current_date;
    } elseif ($time_filter === 'this-week') {
        $start_of_week = date('Y-m-d', strtotime('monday this week'));
        $end_of_week = date('Y-m-d', strtotime('sunday this week'));
        $sql .= " AND DATE(a.start_datetime) BETWEEN ? AND ?";
        $params[] = $start_of_week;
        $params[] = $end_of_week;
    } elseif ($time_filter === 'this-month') {
        $start_of_month = date('Y-m-01');
        $end_of_month = date('Y-m-t');
        $sql .= " AND DATE(a.start_datetime) BETWEEN ? AND ?";
        $params[] = $start_of_month;
        $params[] = $end_of_month;
    }
}

$sql .= " ORDER BY a.start_datetime ASC";

// Get appointment statistics via AJAX instead of hardcoding
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get subjects for filter dropdown
$stmt = $pdo->prepare("SELECT DISTINCT s.name FROM Subjects s 
                       JOIN Appointments a ON s.subject_id = a.subject_id 
                       WHERE a.tutor_id = ?");
$stmt->execute([$user_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Helper
function formatDateTime($datetime) {
    $date = new DateTime($datetime);
    return $date->format('M j, Y') . ' — ' . $date->format('g:i A');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments - PeerEd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">

    <script src="../../assets/js/appointment.js"></script> <!-- This is where the issue is -->

    <style>
        .appointment-card { transition: transform 0.2s; }
        .appointment-card:hover { transform: translateY(-5px); }
        .status-badge { font-weight: normal; padding: 0.5rem 1rem; }
        .appointment-actions { gap: 0.5rem; }
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

<div class="main-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>My Appointments</h1>
            <a href="schedule.php" class="btn btn-primary">Schedule New Session</a>
        </div>

        <!-- Appointment Statistics -->
        <div class="row mb-4" id="stats-container">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number" id="upcoming-count">-</div>
                    <div class="stats-label">Upcoming Sessions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number" id="completed-count">-</div>
                    <div class="stats-label">Completed Sessions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number" id="hours-count">-</div>
                    <div class="stats-label">Hours Taught</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number" id="rating-value">-</div>
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
                            <option value="all" <?= $status_filter === '' || $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                            <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $status_filter === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="subjectFilter">
                            <option value="all" <?= $subject_filter === '' || $subject_filter === 'all' ? 'selected' : '' ?>>All Subjects</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= htmlspecialchars($subject) ?>" 
                                    <?= $subject_filter === $subject ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="timeFilter">
                            <option value="all" <?= $time_filter === '' || $time_filter === 'all' ? 'selected' : '' ?>>All Time</option>
                            <option value="today" <?= $time_filter === 'today' ? 'selected' : '' ?>>Today</option>
                            <option value="this-week" <?= $time_filter === 'this-week' ? 'selected' : '' ?>>This Week</option>
                            <option value="this-month" <?= $time_filter === 'this-month' ? 'selected' : '' ?>>This Month</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Cards -->
        <div class="appointment-list">
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $row): ?>
                    <div class="card mb-4 appointment-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($row['subject_name']); ?> Session</h5>
                                    <p class="text-muted mb-0">with <?php echo htmlspecialchars($row['student_first_name'] . ' ' . $row['student_last_name']); ?></p>
                                </div>
                                <span class="badge bg-<?php echo $row['status'] === 'completed' ? 'success' : ($row['status'] === 'cancelled' ? 'danger' : ($row['status'] === 'pending' ? 'warning' : 'primary')); ?> status-badge">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1"><i class="bi bi-calendar"></i> <?php echo formatDateTime($row['start_datetime']); ?></p>
                                <p class="mb-0"><i class="bi bi-tag"></i> <?php echo htmlspecialchars($row['topic'] ?? 'General'); ?></p>
                            </div>
                            <div class="d-flex appointment-actions">
                                <?php if ($row['status'] === 'completed'): ?>
                                    <a href="../reviews/give-reviews.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-primary flex-grow-1">Leave Review</a>
                                    <button class="btn btn-outline-secondary">View Notes</button>
                                <?php elseif ($row['status'] === 'pending'): ?>
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="updateStatus(<?php echo $row['appointment_id']; ?>, 'confirmed')">Accept</button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="updateStatus(<?php echo $row['appointment_id']; ?>, 'cancelled')">Decline</button>
                                <?php elseif ($row['status'] === 'confirmed'): ?>
                                    <button class="btn btn-primary flex-grow-1" onclick="completedappointment(<?php echo $row['appointment_id']; ?>)">Completed</button>
                                    <button class="btn btn-outline-primary" onclick="rescheduleAppointment(<?php echo $row['appointment_id']; ?>)">Reschedule</button>
                                    <button class="btn btn-outline-danger" onclick="cancelAppointment(<?php echo $row['appointment_id']; ?>)">Cancel</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <h3>No appointments found</h3>
                    <p>You don't have any scheduled appointments matching your filters.</p>
                    <?php if (!empty($status_filter) || !empty($subject_filter) || !empty($time_filter)): ?>
                        <a href="appointments.php" class="btn btn-outline-primary">Clear filters</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination (if needed) -->
        <?php if (count($appointments) > 10): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('../../models/update_appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_statistics'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('upcoming-count').textContent = data.statistics.upcoming;
            document.getElementById('completed-count').textContent = data.statistics.completed;
            document.getElementById('hours-count').textContent = data.statistics.hours;
            document.getElementById('rating-value').textContent = data.statistics.rating;
        }
    })
    .catch(error => {
        console.error('Error loading statistics:', error);
    });
});
</script>

</body>
</html>