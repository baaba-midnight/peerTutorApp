<?php
class Tutor {
    private $conn;
    private $table = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get tutor profile by user ID
    public function getTutorByUserId($user_id) {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, u.phone_number, u.is_active, 
                         p.bio, p.profile_picture_url, p.department, 
                         tp.tutor_profile_id, tp.hourly_rate, tp.overall_rating, tp.is_verified, tp.availability_schedule
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
    public function getAllTutors($filters = []) {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, 
                         p.department, p.profile_picture_url,
                         tp.tutor_profile_id, tp.hourly_rate, tp.overall_rating, tp.is_verified
                  FROM Users u 
                  LEFT JOIN Profiles p ON u.user_id = p.user_id 
                  LEFT JOIN TutorProfiles tp ON u.user_id = tp.user_id
                  WHERE u.role = 'tutor' AND u.is_active = 1";
        
        // Add optional filters
        if (!empty($filters['department'])) {
            $query .= " AND p.department = :department";
        }
        
        if (!empty($filters['is_verified'])) {
            $query .= " AND tp.is_verified = :is_verified";
        }
        
        if (!empty($filters['min_rating'])) {
            $query .= " AND tp.overall_rating >= :min_rating";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind filter parameters if they exist
        if (!empty($filters['department'])) {
            $stmt->bindParam(":department", $filters['department']);
        }
        
        if (!empty($filters['is_verified'])) {
            $stmt->bindParam(":is_verified", $filters['is_verified'], PDO::PARAM_BOOL);
        }
        
        if (!empty($filters['min_rating'])) {
            $stmt->bindParam(":min_rating", $filters['min_rating']);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update tutor profile
    public function updateTutorProfile($user_id, $data) {
        // First update the general profile information
        $profileQuery = "INSERT INTO Profiles (profile_id, user_id, bio, profile_picture_url, department, updated_at) 
                         VALUES (UUID(), :user_id, :bio, :profile_picture_url, :department, NOW()) 
                         ON DUPLICATE KEY UPDATE bio = VALUES(bio), profile_picture_url = VALUES(profile_picture_url), 
                         department = VALUES(department), updated_at = NOW()";
        $profileStmt = $this->conn->prepare($profileQuery);
        $profileStmt->bindParam(":user_id", $user_id);
        $profileStmt->bindParam(":bio", $data['bio']);
        $profileStmt->bindParam(":profile_picture_url", $data['profile_picture_url']);
        $profileStmt->bindParam(":department", $data['department']);
        $profileResult = $profileStmt->execute();
        
        return $profileResult;
    }


    public function updateTutorAvailability($user_id, $data){
        // Then update the tutor-specific information
        $tutorQuery = "INSERT INTO TutorProfiles (tutor_profile_id, user_id, hourly_rate, availability_schedule, updated_at) 
                        VALUES (UUID(), :user_id, :hourly_rate, :availability_schedule, NOW()) 
                        ON DUPLICATE KEY UPDATE hourly_rate = VALUES(hourly_rate), 
                        availability_schedule = VALUES(availability_schedule), updated_at = NOW()";
        $tutorStmt = $this->conn->prepare($tutorQuery);
        $tutorStmt->bindParam(":user_id", $user_id);
        $tutorStmt->bindParam(":hourly_rate", $data['hourly_rate']);
        $tutorStmt->bindParam(":availability_schedule", $data['availability_schedule']);
        $tutorResult = $tutorStmt->execute();
        

        return $tutorResult;

        
    }
    // Get tutor's courses
    public function getTutorCourses($tutor_profile_id) {
        $query = "SELECT tc.tutor_course_id, c.course_id, c.course_code, c.course_name, 
                         c.department, tc.proficiency_level
                  FROM TutorCourses tc
                  JOIN Courses c ON tc.course_id = c.course_id
                  WHERE tc.tutor_id = :tutor_profile_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_profile_id", $tutor_profile_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a course to tutor's profile
    public function addTutorCourse($tutor_profile_id, $course_id, $proficiency_level) {
        $query = "INSERT INTO TutorCourses (tutor_course_id, tutor_id, course_id, proficiency_level, created_at) 
                  VALUES (UUID(), :tutor_id, :course_id, :proficiency_level, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_id", $tutor_profile_id);
        $stmt->bindParam(":course_id", $course_id);
        $stmt->bindParam(":proficiency_level", $proficiency_level);
        return $stmt->execute();
    }

    // Remove a course from tutor's profile
    public function removeTutorCourse($tutor_course_id) {
        $query = "DELETE FROM TutorCourses WHERE tutor_course_id = :tutor_course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_course_id", $tutor_course_id);
        return $stmt->execute();
    }

    // Get tutor session history
    public function getTutorSessions($tutor_profile_id) {
        $query = "SELECT s.session_id, s.start_time, s.end_time, s.status, s.session_type, s.location,
                         c.course_name, 
                         u.user_id AS student_id, u.first_name AS student_first_name, u.last_name AS student_last_name
                  FROM Sessions s
                  JOIN Courses c ON s.course_id = c.course_id
                  JOIN Users u ON s.student_id = u.user_id
                  WHERE s.tutor_id = :tutor_profile_id
                  ORDER BY s.start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_profile_id", $tutor_profile_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tutor reviews
    public function getTutorReviews($tutor_profile_id) {
        $query = "SELECT r.review_id, r.rating, r.comment, r.created_at, r.is_anonymous,
                         s.session_id, c.course_name,
                         CASE 
                            WHEN r.is_anonymous = 1 THEN 'Anonymous Student'
                            ELSE CONCAT(u.first_name, ' ', u.last_name)
                         END AS student_name
                  FROM Reviews r
                  JOIN Sessions s ON r.session_id = s.session_id
                  JOIN Courses c ON s.course_id = c.course_id
                  JOIN Users u ON r.student_id = u.user_id
                  WHERE r.tutor_id = :tutor_profile_id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_profile_id", $tutor_profile_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update tutor availability schedule
    public function updateAvailabilitySchedule($tutor_profile_id, $availability_schedule) {
        $query = "UPDATE TutorProfiles SET availability_schedule = :availability_schedule, updated_at = NOW()
                  WHERE tutor_profile_id = :tutor_profile_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_profile_id", $tutor_profile_id);
        $stmt->bindParam(":availability_schedule", $availability_schedule);
        return $stmt->execute();
    }

    // Update hourly rate
    public function updateHourlyRate($tutor_profile_id, $hourly_rate) {
        $query = "UPDATE TutorProfiles SET hourly_rate = :hourly_rate, updated_at = NOW()
                  WHERE tutor_profile_id = :tutor_profile_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tutor_profile_id", $tutor_profile_id);
        $stmt->bindParam(":hourly_rate", $hourly_rate);
        return $stmt->execute();
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