<?php
class Tutor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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
?>
