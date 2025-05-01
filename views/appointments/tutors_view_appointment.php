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
            <h1 class="mb-4">My Appointments</h1>

            <div class="card">
                <div class="card-header">
                    <p>Session with Alex Smith</p>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <div class="card-info">
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

        </div>
    </div>
</body>
</html>

<?php
require '../../config/database.php';
session_start();

// Get current user ID (assuming it's stored in session)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user ID is not set
    header("Location: ../../login.php");
    exit();
}

// Function to format datetime
function formatDateTime($datetime) {
    $date = new DateTime($datetime);
    return $date->format('l') . ', ' . $date->format('g:i A');
}

// Get all appointments for the current tutor
$sql = "SELECT a.*, 
               s.name AS subject_name, 
               u.first_name AS student_first_name, 
               u.last_name AS student_last_name
        FROM Appointments a
        JOIN Subjects s ON a.subject_id = s.subject_id
        JOIN Users u ON a.student_id = u.user_id
        WHERE a.tutor_id = ?
        ORDER BY a.start_datetime ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Inside your container div, replace the single hardcoded card with this code: -->
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-header">
                <p>Session with <?php echo htmlspecialchars($row['student_first_name'] . ' ' . $row['student_last_name']); ?></p>
            </div>
            <div class="card-body">
                <div class="card-section">
                    <div class="card-info">
                        <div class="title-meta">
                            <p><?php echo htmlspecialchars($row['subject_name']); ?></p>
                            <p><?php echo formatDateTime($row['start_datetime']); ?></p>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn appointment-status"><?php echo ucfirst($row['status']); ?></button>
                        <?php if ($row['status'] == 'confirmed'): ?>
                            <button class="btn appointment-join">Start Session</button>
                        <?php elseif ($row['status'] == 'pending'): ?>
                            <button class="btn btn-success btn-sm me-2" onclick="updateStatus(<?php echo $row['appointment_id']; ?>, 'confirmed')">Accept</button>
                            <button class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $row['appointment_id']; ?>, 'cancelled')">Decline</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="text-center py-5">
        <h3>No appointments found</h3>
        <p>You don't have any scheduled appointments at the moment.</p>
    </div>
<?php endif; ?>

<!-- Add this JavaScript at the bottom of your page before the closing body tag -->
<script>
function updateStatus(appointmentId, status) {
    if (confirm('Are you sure you want to ' + (status === 'confirmed' ? 'accept' : 'decline') + ' this appointment?')) {
        // Create form data
        const formData = new FormData();
        formData.append('appointment_id', appointmentId);
        formData.append('status', status);
        
        // Send AJAX request to update status
        fetch('update_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show updated status
                location.reload();
            } else {
                alert('Failed to update appointment status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating appointment status');
        });
    }
}
</script>
