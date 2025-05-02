<?php
// Start session at the beginning of the file
require_once '../../config/database.php';


//Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$pdo = getConnection();

// Process the appointment action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
    
    // Validate appointment exists and user has permission
    if ($appointment_id > 0 && $action != 'get_statistics') {
        $stmt = $pdo->prepare("SELECT * FROM Appointments WHERE appointment_id = ?");
        $stmt->execute([$appointment_id]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$appointment) {
            echo json_encode(['success' => false, 'message' => 'Appointment not found']);
            exit();
        }
        
        // Check if user is the tutor or student for this appointment
        if ($appointment['tutor_id'] != $user_id && $appointment['student_id'] != $user_id) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission for this appointment']);
            exit();
        }
    }
    
    switch ($action) {
        case 'update_status':
            handleStatusUpdate($pdo, $appointment_id, $_POST['status'] ?? '', $user_id, $appointment);
            break;
            
        case 'cancel':
            handleCancellation($pdo, $appointment_id, $user_id, $appointment);
            break;
            
        case 'get_meeting_link':
            getMeetingLink($pdo, $appointment_id);
            break;
            
        case 'get_statistics':
            getAppointmentStatistics($pdo, $user_id);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} else {
    // For development, you can either add GET handling here or keep the error
    echo json_encode(['success' => false, 'message' => 'Invalid request method - POST required']);
}

/**
 * Handles updating an appointment's status (confirm/decline)
 */
function handleStatusUpdate($pdo, $appointment_id, $status, $user_id, $appointment) {
    // Validate status
    if (!in_array($status, ['confirmed', 'cancelled'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        return;
    }
    
    // Only tutors can confirm/decline appointments
    if ($appointment['tutor_id'] != $user_id) {
        echo json_encode(['success' => false, 'message' => 'Only tutors can update appointment status']);
        return;
    }
    
    // Only pending appointments can be updated
    if ($appointment['status'] !== 'pending') {
        echo json_encode(['success' => false, 'message' => 'Only pending appointments can be updated']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE Appointments SET status = ?, updated_at = NOW() WHERE appointment_id = ?");
        $result = $stmt->execute([$status, $appointment_id]);
        
        if ($result) {
            // Create notification for the student
            createNotification($pdo, $appointment['student_id'], 'appointment', 
                               'Appointment ' . ucfirst($status), 
                               'Your appointment has been ' . $status . ' by the tutor.');
            
            echo json_encode([
                'success' => true, 
                'message' => 'Appointment has been ' . ($status === 'confirmed' ? 'accepted' : 'declined') . ' successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update appointment']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Handles cancellation of an appointment
 */
function handleCancellation($pdo, $appointment_id, $user_id, $appointment) {
    // Only confirmed appointments can be cancelled
    if ($appointment['status'] !== 'confirmed' && $appointment['status'] !== 'pending') {
        echo json_encode(['success' => false, 'message' => 'Only confirmed or pending appointments can be cancelled']);
        return;
    }
    
    // Check if cancellation is within 24 hours of appointment
    $appointment_time = new DateTime($appointment['start_datetime']);
    $current_time = new DateTime();
    $time_diff = $current_time->diff($appointment_time);
    $hours_remaining = $time_diff->days * 24 + $time_diff->h;
    
    // Optional: Add a policy about cancellation timeframes
    $late_cancellation = $hours_remaining < 24;
    
    try {
        $stmt = $pdo->prepare("UPDATE Appointments SET status = 'cancelled', updated_at = NOW() WHERE appointment_id = ?");
        $result = $stmt->execute([$appointment_id]);
        
        if ($result) {
            // Determine who cancelled
            $canceller_type = ($appointment['student_id'] == $user_id) ? 'student' : 'tutor';
            echo $canceller_type, 'cancelled the appointment';
            $other_user_id = ($canceller_type == 'student') ? $appointment['tutor_id'] : $appointment['student_id'];
            
            // Create notification for the other party
            createNotification($pdo, $other_user_id, 'appointment', 
                               'Appointment Cancelled', 
                               'Your appointment has been cancelled by the ' . $canceller_type . '.');
            
            $message = 'Appointment cancelled successfully';
            if ($late_cancellation) {
                $message .= ' (Note: This was a late cancellation within 24 hours of the scheduled time)';
            }
            
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel appointment']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Retrieves the meeting link for an appointment
 */
function getMeetingLink($pdo, $appointment_id) {
    try {
        $stmt = $pdo->prepare("SELECT meeting_link FROM Appointments WHERE appointment_id = ?");
        $stmt->execute([$appointment_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['meeting_link'])) {
            echo json_encode(['success' => true, 'meeting_link' => $result['meeting_link']]);
        } else {
            // If no meeting link exists, generate one
            $meeting_link = generateMeetingLink($pdo, $appointment_id);
            echo json_encode(['success' => true, 'meeting_link' => $meeting_link]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Generates a meeting link if one doesn't exist
 */
function generateMeetingLink($pdo, $appointment_id) {
    // In a real app, this would integrate with Zoom, Google Meet, etc.
    // For now, using a simple placeholder
    $meeting_link = "https://meet.example.com/" . md5("meeting" . $appointment_id . time());
    
    // Save the meeting link
    $stmt = $pdo->prepare("UPDATE Appointments SET meeting_link = ? WHERE appointment_id = ?");
    $stmt->execute([$meeting_link, $appointment_id]);
    
    return $meeting_link;
}

/**
 * Gets statistics about appointments for the user
 */
function getAppointmentStatistics($pdo, $user_id) {
    try {
        // Get upcoming sessions
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Appointments 
                              WHERE (tutor_id = ? OR student_id = ?) 
                              AND status = 'confirmed' 
                              AND start_datetime > NOW()");
        $stmt->execute([$user_id, $user_id]);
        $upcoming = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Get completed sessions
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM Appointments 
                              WHERE (tutor_id = ? OR student_id = ?) 
                              AND status = 'completed'");
        $stmt->execute([$user_id, $user_id]);
        $completed = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Get total hours
        $stmt = $pdo->prepare("SELECT SUM(TIMESTAMPDIFF(HOUR, start_datetime, end_datetime)) as hours 
                              FROM Appointments 
                              WHERE (tutor_id = ? OR student_id = ?) 
                              AND status = 'completed'");
        $stmt->execute([$user_id, $user_id]);
        $hours = $stmt->fetch(PDO::FETCH_ASSOC)['hours'] ?: 0;
        
        // Get average rating (if user is a tutor)
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM Reviews 
                              WHERE reviewee_id = ?");
        $stmt->execute([$user_id]);
        $rating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'] ?: 0;
        
        echo json_encode([
            'success' => true,
            'statistics' => [
                'upcoming' => (int)$upcoming,
                'completed' => (int)$completed,
                'hours' => (int)$hours,
                'rating' => round($rating, 1)
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Creates a notification in the database
 */
function createNotification($pdo, $user_id, $type, $title, $content) {
    try {
        $stmt = $pdo->prepare("INSERT INTO Notifications (user_id, type, title, content) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $type, $title, $content]);
        return true;
    } catch (PDOException $e) {
        // Log error but continue execution
        error_log('Failed to create notification: ' . $e->getMessage());
        return false;
    }
}
?>