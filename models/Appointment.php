<?php
class Appointment {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new appointment
    public function create($student_id, $tutor_id, $subject_id, $start_datetime, $end_datetime, $meeting_link = null, $notes = null) {
        try {
            $this->conn->beginTransaction();
            $query = "INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, meeting_link, notes) VALUES (:student_id, :tutor_id, :subject_id, :start_datetime, :end_datetime, :meeting_link, :notes)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':tutor_id', $tutor_id);
            $stmt->bindParam(':subject_id', $subject_id);
            $stmt->bindParam(':start_datetime', $start_datetime);
            $stmt->bindParam(':end_datetime', $end_datetime);
            $stmt->bindParam(':meeting_link', $meeting_link);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();
            $appointment_id = $this->conn->lastInsertId();
            $this->conn->commit();
            return array('status' => 'success', 'appointment_id' => $appointment_id);
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return array('status' => 'error', 'message' => 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    // Get appointment by ID (with student and tutor details)
    public function getById($appointment_id) {
        $query = "SELECT a.*, 
                        s.first_name AS student_first_name, s.last_name AS student_last_name, s.email AS student_email, 
                        t.first_name AS tutor_first_name, t.last_name AS tutor_last_name, t.email AS tutor_email
                  FROM Appointments a
                  LEFT JOIN Users s ON a.student_id = s.user_id
                  LEFT JOIN Users t ON a.tutor_id = t.user_id
                  WHERE a.appointment_id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $appointment_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all appointments for a user (student or tutor)
    public function getForUser($user_id, $role = 'student') {
        $column = $role === 'tutor' ? 'tutor_id' : 'student_id';
        $query = "SELECT a.*, 
                        s.first_name AS student_first_name, s.last_name AS student_last_name, s.email AS student_email, 
                        t.first_name AS tutor_first_name, t.last_name AS tutor_last_name, t.email AS tutor_email,
                        c.course_name
                  FROM Appointments a
                  LEFT JOIN Users s ON a.student_id = s.user_id
                  LEFT JOIN Users t ON a.tutor_id = t.user_id
                  LEFT JOIN Courses c ON a.subject_id = c.course_id
                  WHERE a.$column = :user_id
                  ORDER BY a.start_datetime DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update appointment status
    public function updateStatus($appointment_id, $status, $notes = null) {
        $query = "UPDATE Appointments SET status = :status, notes = :notes WHERE appointment_id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':appointment_id', $appointment_id);
        return $stmt->execute();
    }

    // Delete appointment
    public function delete($appointment_id) {
        $query = "DELETE FROM Appointments WHERE appointment_id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $appointment_id);
        return $stmt->execute();
    }
}