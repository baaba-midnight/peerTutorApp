<?php
class Tutor
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Dashboard Functionality
    public function getUpcomingSessions($userId, $role, $limit)
    {
        $params = [];

        if ($role === 'student') {
            $query = "SELECT a.*, r.review_id AS reviewed
            FROM appointments a
            LEFT JOIN reviews r ON a.appointment_id = r.session_id AND r.student_id = :userId
            WHERE a.student_id = :userId AND a.end_datetime >= NOW() ORDER BY a.end_datetime ASC";
        } else {
            $query = "SELECT * FROM appointments WHERE tutor_id = :userId AND end_datetime >= NOW() ORDER BY end_datetime ASC";
        }

        if ($limit) {
            $query .= " LIMIT :limit";
            $params[':limit'] = (int)$limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);

        if ($limit) {
            $stmt->bindParam(':limit', $params[':limit'], PDO::PARAM_INT);
        }

        $stmt->execute();

        // get student names for each appointment
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($role == 'student') {
            foreach ($appointments as &$appointment) {
                $tutorId = $appointment['tutor_id'];
                $tutorQuery = "SELECT CONCAT(first_name, ' ', last_name) as tutor_name FROM users WHERE user_id = :tutorId";
                $tutorStmt = $this->conn->prepare($tutorQuery);
                $tutorStmt->bindParam(':tutorId', $tutorId);
                $tutorStmt->execute();
                $tutor = $tutorStmt->fetch(PDO::FETCH_ASSOC);
                $appointment['tutor_name'] = $tutor['tutor_name'];
            }
        } else {
            foreach ($appointments as &$appointment) {
                $studentId = $appointment['student_id'];
                $studentQuery = "SELECT CONCAT(first_name, ' ', last_name) as student_name FROM users WHERE user_id = :studentId";
                $studentStmt = $this->conn->prepare($studentQuery);
                $studentStmt->bindParam(':studentId', $studentId);
                $studentStmt->execute();
                $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
                $appointment['student_name'] = $student['student_name'];
            }
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

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get student names for each message
        foreach ($messages as &$message) {
            $senderId = $message['sender_id'];
            $studentQuery = "SELECT CONCAT(first_name, ' ', last_name) as student_name, email FROM users WHERE user_id = :senderId";
            $studentStmt = $this->conn->prepare($studentQuery);
            $studentStmt->bindParam(':senderId', $senderId);
            $studentStmt->execute();
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            $message['student_name'] = $student['student_name'];
            $message['student_email'] = $student['email'];
        }

        return $messages;
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
            $studentQuery = "SELECT CONCAT(first_name, ' ', last_name) as student_name, email FROM users WHERE user_id = :studentId";
            $studentStmt = $this->conn->prepare($studentQuery);
            $studentStmt->bindParam(':studentId', $studentId);
            $studentStmt->execute();
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            $review['student_name'] = $student['student_name'];
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

    // Get tutor profile by user ID
    public function getTutorByUserId($user_id) {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, u.phone_number, u.is_active, p.bio, p.profile_picture_url, tp.hourly_rate, tp.availability_schedule, tp.overall_rating, tp.is_verified
                  FROM Users u
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  LEFT JOIN TutorProfiles tp ON u.user_id = tp.user_id
                  WHERE u.user_id = :user_id AND u.role = 'tutor'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all tutors
    public function getAllTutors() {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, p.bio, p.profile_picture_url, tp.hourly_rate, tp.overall_rating, tp.is_verified
                  FROM Users u
                  LEFT JOIN Profiles p ON u.user_id = p.user_id
                  LEFT JOIN TutorProfiles tp ON u.user_id = tp.user_id
                  WHERE u.role = 'tutor' AND u.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update tutor profile
    public function updateTutorProfile($user_id, $bio, $profile_picture_url, $hourly_rate, $availability_schedule) {
        // Update Profiles table
        $query1 = "INSERT INTO Profiles (user_id, bio, profile_picture_url, updated_at) VALUES (:user_id, :bio, :profile_picture_url, NOW()) ON DUPLICATE KEY UPDATE bio = VALUES(bio), profile_picture_url = VALUES(profile_picture_url), updated_at = NOW()";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(":user_id", $user_id);
        $stmt1->bindParam(":bio", $bio);
        $stmt1->bindParam(":profile_picture_url", $profile_picture_url);
        $stmt1->execute();
        // Update TutorProfiles table
        $query2 = "UPDATE TutorProfiles SET hourly_rate = :hourly_rate, availability_schedule = :availability_schedule, updated_at = NOW() WHERE user_id = :user_id";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(":hourly_rate", $hourly_rate);
        $stmt2->bindParam(":availability_schedule", $availability_schedule);
        $stmt2->bindParam(":user_id", $user_id);
        return $stmt2->execute();
    }

    // Get tutor's sessions
    public function getTutorSessions($tutor_id) {
        $query = "SELECT s.session_id, s.start_time, s.end_time, s.status, c.course_name, s.student_id, u.first_name AS student_first_name, u.last_name AS student_last_name
                  FROM Sessions s
                  JOIN Courses c ON s.course_id = c.course_id
                  JOIN Users u ON s.student_id = u.user_id
                  WHERE s.tutor_id = :tutor_id
                  ORDER BY s.start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_id", $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tutor's courses
    public function getTutorCourses($tutor_id) {
        $query = "SELECT c.course_id, c.course_name, c.course_code, c.department
                  FROM TutorCourses tc
                  JOIN Courses c ON tc.course_id = c.course_id
                  WHERE tc.tutor_id = :tutor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_id", $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update tutor's courses
    public function updateTutorCourses($tutor_id, $course_ids) {
        // Remove old courses
        $this->conn->prepare('DELETE FROM TutorCourses WHERE tutor_id = :tutor_id')->execute([':tutor_id' => $tutor_id]);
        // Insert new courses
        $query = 'INSERT INTO TutorCourses (tutor_id, course_id, proficiency_level) VALUES (:tutor_id, :course_id, "beginner")';
        $stmt = $this->conn->prepare($query);
        foreach ($course_ids as $course_id) {
            $stmt->execute([':tutor_id' => $tutor_id, ':course_id' => $course_id]);
        }
        return true;
    }

    // Get tutor's reviews
    public function getTutorReviews($tutor_id) {
        $query = "SELECT r.*, s.first_name AS student_first_name, s.last_name AS student_last_name
                  FROM Reviews r
                  JOIN Users s ON r.student_id = s.user_id
                  WHERE r.tutor_id = :tutor_id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_id", $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete tutor account
    public function deleteTutor($user_id) {
        $query = "DELETE FROM Users WHERE user_id = :user_id AND role = 'tutor'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }
}
