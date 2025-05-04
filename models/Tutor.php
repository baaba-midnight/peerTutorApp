<?php
class Tutor
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Dashboard Functionality
    public function getUpcomingSessions($tutorId, $limit)
    {

        $query = "SELECT * FROM appointments WHERE tutor_id = :tutorId AND end_datetime >= NOW() ORDER BY end_datetime ASC";

        if ($limit) {
            $query .= " LIMIT :limit";
            $params[':limit'] = (int)$limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorId', $tutorId);

        if ($limit) {
            $stmt->bindParam(':limit', $params[':limit'], PDO::PARAM_INT);
        }

        $stmt->execute();

        // get student names for each appointment
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($appointments as &$appointment) {
            $studentId = $appointment['student_id'];
            $studentQuery = "SELECT CONCAT(first_name, ' ', last_name) as name FROM users WHERE user_id = :studentId";
            $studentStmt = $this->conn->prepare($studentQuery);
            $studentStmt->bindParam(':studentId', $studentId);
            $studentStmt->execute();
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            $appointment['student_name'] = $student['name'];
        }

        // get the course code and name for each appointment
        foreach ($appointments as &$appointment) {
            $courseId = $appointment['subject_id'];
            $courseQuery = "SELECT course_code, course_name FROM courses WHERE course_id = :courseId";
            $courseStmt = $this->conn->prepare($courseQuery);
            $courseStmt->bindParam(':courseId', $courseId);
            $courseStmt->execute();
            $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
            $appointment['course_code'] = $course['course_code'];
            $appointment['course_name'] = $course['course_name'];
        }

        // divide appointments into past and upcoming
        $upcomingAppointments = array_filter($appointments, function ($appointment) {
            return ($appointment['status'] === 'confirmed');
        });

        $pendingAppointments = array_filter($appointments, function ($appointment) {
            return ($appointment['status'] === 'pending');
        });

        $pastAppointments = array_filter($appointments, function ($appointment) {
            return ($appointment['status'] === 'completed' || $appointment['status'] === 'cancelled');
        });

        $appointments = [
            'upcoming' => array_values($upcomingAppointments),
            'pending' => array_values($pendingAppointments),
            'past' => array_values($pastAppointments)
        ];

        return $appointments;
    }

    public function getRecentMessages($tutorId)
    {
        $query = "SELECT * FROM messages WHERE recipient_id = :tutorId ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorId', $tutorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentReviews($tutorId)
    {
        $query = "SELECT * FROM reviews WHERE tutor_id = :tutorId ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorId', $tutorId);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get student names for each review
        foreach ($reviews as &$review) {
            $studentId = $review['student_id'];
            $studentQuery = "SELECT CONCAT(first_name, ' ', last_name) as name, email FROM users WHERE user_id = :studentId";
            $studentStmt = $this->conn->prepare($studentQuery);
            $studentStmt->bindParam(':studentId', $studentId);
            $studentStmt->execute();
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            $review['student_name'] = $student['name'];
            $review['student_email'] = $student['email'];
        }

        return $reviews;
    }

    // Appointment Management Functionality Based on filter
    public function getAppointments($tutorId, $filter)
    {
        $query = "SELECT * FROM appointments WHERE tutor_id = :tutorId AND status = :filter ORDER BY created_At ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorId', $tutorId);
        $stmt->bindParam(':filter', $filter);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAppointmentStatus($appointmentId, $status)
    {
        $query = "UPDATE appointments SET status = :status WHERE appointment_id = :appointmentId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':appointmentId', $appointmentId);
        return $stmt->execute();
    }
}
