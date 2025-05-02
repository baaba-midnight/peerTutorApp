<?php
require_once('../config/database.php');
require_once('../config/session.php');

header('Content-Type: application/json');

// Get database connection
$conn = getConnection();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single appointment
            getAppointment($conn, $_GET['id']);
        } else {
            // Get list of appointments with filters
            $filters = [
                'status' => $_GET['status'] ?? null,
                'subject' => $_GET['subject'] ?? null,
                'timeframe' => $_GET['timeframe'] ?? null,
                'student_id' => $_GET['student_id'] ?? null,
                'tutor_id' => $_GET['tutor_id'] ?? null
            ];
            getAppointments($conn, $filters);
        }
        break;

    case 'POST':
        // Create new appointment
        $data = json_decode(file_get_contents('php://input'), true);
        createAppointment($conn, $data);
        break;

    case 'PUT':
        // Update appointment
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        updateAppointment($conn, $_GET['id'], $data);
        break;

    case 'DELETE':
        // Cancel appointment
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            exit;
        }
        cancelAppointment($conn, $_GET['id']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Function to get a single appointment
function getAppointment($conn, $id) {
    $sql = "SELECT a.*, 
            s.name as subject_name,
            CONCAT(u1.first_name, ' ', u1.last_name) as student_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as tutor_name
            FROM Appointments a
            JOIN Subjects s ON a.subject_id = s.subject_id
            JOIN Users u1 ON a.student_id = u1.user_id
            JOIN Users u2 ON a.tutor_id = u2.user_id
            WHERE appointment_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Appointment not found']);
        exit;
    }

    echo json_encode($result->fetch_assoc());
}

// Function to get filtered list of appointments
function getAppointments($conn, $filters) {
    $sql = "SELECT a.*, 
            s.name as subject_name,
            CONCAT(u1.first_name, ' ', u1.last_name) as student_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as tutor_name
            FROM Appointments a
            JOIN Subjects s ON a.subject_id = s.subject_id
            JOIN Users u1 ON a.student_id = u1.user_id
            JOIN Users u2 ON a.tutor_id = u2.user_id
            WHERE 1=1";
    
    $params = [];
    $types = '';

    if ($filters['status']) {
        $sql .= " AND a.status = ?";
        $params[] = $filters['status'];
        $types .= 's';
    }

    if ($filters['subject']) {
        $sql .= " AND s.name = ?";
        $params[] = $filters['subject'];
        $types .= 's';
    }

    if ($filters['student_id']) {
        $sql .= " AND a.student_id = ?";
        $params[] = $filters['student_id'];
        $types .= 'i';
    }

    if ($filters['tutor_id']) {
        $sql .= " AND a.tutor_id = ?";
        $params[] = $filters['tutor_id'];
        $types .= 'i';
    }

    if ($filters['timeframe']) {
        switch ($filters['timeframe']) {
            case 'today':
                $sql .= " AND DATE(a.start_datetime) = CURDATE()";
                break;
            case 'this-week':
                $sql .= " AND YEARWEEK(a.start_datetime) = YEARWEEK(CURDATE())";
                break;
            case 'this-month':
                $sql .= " AND MONTH(a.start_datetime) = MONTH(CURDATE())";
                break;
        }
    }

    $sql .= " ORDER BY a.start_datetime DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    echo json_encode($appointments);
}

// Function to create a new appointment
function createAppointment($conn, $data) {
    // Validate required fields
    $required = ['student_id', 'tutor_id', 'subject_id', 'start_datetime', 'end_datetime'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Missing required field: $field"]);
            exit;
        }
    }

    // Check tutor availability
    $sql = "SELECT * FROM Appointments 
            WHERE tutor_id = ? 
            AND status != 'cancelled'
            AND ((start_datetime BETWEEN ? AND ?) 
            OR (end_datetime BETWEEN ? AND ?))";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', 
        $data['tutor_id'], 
        $data['start_datetime'], 
        $data['end_datetime'],
        $data['start_datetime'], 
        $data['end_datetime']
    );
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Tutor is not available at this time']);
        exit;
    }

    // Insert appointment
    $sql = "INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiss', 
        $data['student_id'], 
        $data['tutor_id'],
        $data['subject_id'],
        $data['start_datetime'],
        $data['end_datetime']
    );

    if ($stmt->execute()) {
        $appointment_id = $conn->insert_id;
        
        // Create notification for tutor
        $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                VALUES (?, 'appointment', 'New Appointment Request', 
                'You have a new appointment request for " . date('F j, Y g:i A', strtotime($data['start_datetime'])) . "')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $data['tutor_id']);
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            'message' => 'Appointment created successfully',
            'appointment_id' => $appointment_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create appointment']);
    }
}

// Function to update an appointment
function updateAppointment($conn, $id, $data) {
    $allowed_fields = ['status', 'meeting_link', 'notes'];
    $updates = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        if (in_array($field, $allowed_fields)) {
            $updates[] = "$field = ?";
            $params[] = $value;
            $types .= 's';
        }
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        exit;
    }

    $params[] = $id;
    $types .= 'i';

    $sql = "UPDATE Appointments SET " . implode(', ', $updates) . " WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // If status was updated to confirmed, create a notification
        if (isset($data['status']) && $data['status'] === 'confirmed') {
            $sql = "SELECT student_id, start_datetime FROM Appointments WHERE appointment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $appointment = $result->fetch_assoc();

            $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                    VALUES (?, 'appointment', 'Appointment Confirmed', 
                    'Your appointment for " . date('F j, Y g:i A', strtotime($appointment['start_datetime'])) . " has been confirmed')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $appointment['student_id']);
            $stmt->execute();
        }

        echo json_encode(['message' => 'Appointment updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update appointment']);
    }
}

// Function to cancel an appointment
function cancelAppointment($conn, $id) {
    $sql = "UPDATE Appointments SET status = 'cancelled' WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Get appointment details for notification
        $sql = "SELECT student_id, tutor_id, start_datetime FROM Appointments WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();

        // Create notifications for both student and tutor
        $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                VALUES (?, 'appointment', 'Appointment Cancelled', 
                'The appointment scheduled for " . date('F j, Y g:i A', strtotime($appointment['start_datetime'])) . " has been cancelled')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $appointment['student_id']);
        $stmt->execute();

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $appointment['tutor_id']);
        $stmt->execute();

        echo json_encode(['message' => 'Appointment cancelled successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to cancel appointment']);
    }
}

$conn->close();<?php
require_once('../config/database.php');
require_once('../config/session.php');


header('Content-Type: application/json');

// Get database connection
$conn = getConnection();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single appointment
            getAppointment($conn, $_GET['id']);
        } else {
            // Get list of appointments with filters
            $filters = [
                'status' => $_GET['status'] ?? null,
                'subject' => $_GET['subject'] ?? null,
                'timeframe' => $_GET['timeframe'] ?? null,
                'student_id' => $_GET['student_id'] ?? null,
                'tutor_id' => $_GET['tutor_id'] ?? null
            ];
            getAppointments($conn, $filters);
        }
        break;

        case 'POST':
            // Decode raw input if JSON (used for creating new appointment)
            $data = json_decode(file_get_contents('php://input'), true);
        
            // Fallback to $_POST if form-encoded
            $action = $_POST['action'] ?? $data['action'] ?? null;
        
            if ($action === 'cancel') {
                $appointmentId = $_POST['appointment_id'] ?? $data['appointment_id'] ?? null;
                if ($appointmentId) {
                    cancelAppointment($conn, $appointmentId);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Appointment ID is required']);
                }
            } else {
                // Default: Create new appointment
                createAppointment($conn, $data);
            }
            break;
        
    case 'PUT':
        // Update appointment
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        updateAppointment($conn, $_GET['id'], $data);
        break;

    case 'DELETE':
        // Cancel appointment
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            exit;
        }
        cancelAppointment($conn, $_GET['id']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Function to get a single appointment
function getAppointment($conn, $id) {
    $sql = "SELECT a.*, 
            s.name as subject_name,
            CONCAT(u1.first_name, ' ', u1.last_name) as student_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as tutor_name
            FROM Appointments a
            JOIN Subjects s ON a.subject_id = s.subject_id
            JOIN Users u1 ON a.student_id = u1.user_id
            JOIN Users u2 ON a.tutor_id = u2.user_id
            WHERE appointment_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Appointment not found']);
        exit;
    }

    echo json_encode($result->fetch_assoc());
}

// Function to get filtered list of appointments
function getAppointments($conn, $filters) {
    $sql = "SELECT a.*, 
            s.name as subject_name,
            CONCAT(u1.first_name, ' ', u1.last_name) as student_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as tutor_name
            FROM Appointments a
            JOIN Subjects s ON a.subject_id = s.subject_id
            JOIN Users u1 ON a.student_id = u1.user_id
            JOIN Users u2 ON a.tutor_id = u2.user_id
            WHERE 1=1";
    
    $params = [];
    $types = '';

    if ($filters['status']) {
        $sql .= " AND a.status = ?";
        $params[] = $filters['status'];
        $types .= 's';
    }

    if ($filters['subject']) {
        $sql .= " AND s.name = ?";
        $params[] = $filters['subject'];
        $types .= 's';
    }

    if ($filters['student_id']) {
        $sql .= " AND a.student_id = ?";
        $params[] = $filters['student_id'];
        $types .= 'i';
    }

    if ($filters['tutor_id']) {
        $sql .= " AND a.tutor_id = ?";
        $params[] = $filters['tutor_id'];
        $types .= 'i';
    }

    if ($filters['timeframe']) {
        switch ($filters['timeframe']) {
            case 'today':
                $sql .= " AND DATE(a.start_datetime) = CURDATE()";
                break;
            case 'this-week':
                $sql .= " AND YEARWEEK(a.start_datetime) = YEARWEEK(CURDATE())";
                break;
            case 'this-month':
                $sql .= " AND MONTH(a.start_datetime) = MONTH(CURDATE())";
                break;
        }
    }

    $sql .= " ORDER BY a.start_datetime DESC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    echo json_encode($appointments);
}

// Function to create a new appointment
function createAppointment($conn, $data) {
    // Validate required fields
    $required = ['student_id', 'tutor_id', 'subject_id', 'start_datetime', 'end_datetime'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Missing required field: $field"]);
            exit;
        }
    }

    // Check tutor availability
    $sql = "SELECT * FROM Appointments 
            WHERE tutor_id = ? 
            AND status != 'cancelled'
            AND ((start_datetime BETWEEN ? AND ?) 
            OR (end_datetime BETWEEN ? AND ?))";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', 
        $data['tutor_id'], 
        $data['start_datetime'], 
        $data['end_datetime'],
        $data['start_datetime'], 
        $data['end_datetime']
    );
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Tutor is not available at this time']);
        exit;
    }

    // Insert appointment
    $sql = "INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiss', 
        $data['student_id'], 
        $data['tutor_id'],
        $data['subject_id'],
        $data['start_datetime'],
        $data['end_datetime']
    );

    if ($stmt->execute()) {
        $appointment_id = $conn->insert_id;
        
        // Create notification for tutor
        $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                VALUES (?, 'appointment', 'New Appointment Request', 
                'You have a new appointment request for " . date('F j, Y g:i A', strtotime($data['start_datetime'])) . "')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $data['tutor_id']);
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            'message' => 'Appointment created successfully',
            'appointment_id' => $appointment_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create appointment']);
    }
}

// Function to update an appointment
function updateAppointment($conn, $id, $data) {
    $allowed_fields = ['status', 'meeting_link', 'notes'];
    $updates = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        if (in_array($field, $allowed_fields)) {
            $updates[] = "$field = ?";
            $params[] = $value;
            $types .= 's';
        }
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        exit;
    }

    $params[] = $id;
    $types .= 'i';

    $sql = "UPDATE Appointments SET " . implode(', ', $updates) . " WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // If status was updated to confirmed, create a notification
        if (isset($data['status']) && $data['status'] === 'confirmed') {
            $sql = "SELECT student_id, start_datetime FROM Appointments WHERE appointment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $appointment = $result->fetch_assoc();

            $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                    VALUES (?, 'appointment', 'Appointment Confirmed', 
                    'Your appointment for " . date('F j, Y g:i A', strtotime($appointment['start_datetime'])) . " has been confirmed')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $appointment['student_id']);
            $stmt->execute();
        }

        echo json_encode(['message' => 'Appointment updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update appointment']);
    }
}

// Function to cancel an appointment
function cancelAppointment($conn, $id) {
    $sql = "UPDATE Appointments SET status = 'cancelled' WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Get appointment details for notification
        $sql = "SELECT student_id, tutor_id, start_datetime FROM Appointments WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();

        // Create notifications for both student and tutor
        $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                VALUES (?, 'appointment', 'Appointment Cancelled', 
                'The appointment scheduled for " . date('F j, Y g:i A', strtotime($appointment['start_datetime'])) . " has been cancelled')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $appointment['student_id']);
        $stmt->execute();

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $appointment['tutor_id']);
        $stmt->execute();

        echo json_encode(['message' => 'Appointment cancelled successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to cancel appointment']);
    }
}

$conn = null; // Close the connection