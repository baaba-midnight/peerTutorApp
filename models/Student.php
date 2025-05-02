<?php
class Student {
    private $conn;
    // private $table = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get student profile by user ID
    public function getStudentByUserId($user_id) {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, u.phone_number, u.is_active, p.bio, p.profile_picture_url, p.department, p.year_of_study 
                  FROM Users u 
                  LEFT JOIN Profiles p ON u.user_id = p.user_id 
                  WHERE u.user_id = :user_id AND u.role = 'student'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all students
    public function getAllStudents() {
        $query = "SELECT u.user_id, u.email, u.first_name, u.last_name, p.department, p.year_of_study 
                  FROM Users u 
                  LEFT JOIN Profiles p ON u.user_id = p.user_id 
                  WHERE u.role = 'student' AND u.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update student profile
    public function updateStudentProfile($user_id, $bio, $profile_picture_url, $department, $year_of_study) {
        $query = "INSERT INTO Profiles (profile_id, user_id, bio, profile_picture_url, department, year_of_study, updated_at) 
                  VALUES (UUID(), :user_id, :bio, :profile_picture_url, :department, :year_of_study, NOW()) 
                  ON DUPLICATE KEY UPDATE bio = VALUES(bio), profile_picture_url = VALUES(profile_picture_url), 
                  department = VALUES(department), year_of_study = VALUES(year_of_study), updated_at = NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":bio", $bio);
        $stmt->bindParam(":profile_picture_url", $profile_picture_url);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":year_of_study", $year_of_study);
        return $stmt->execute();
    }

    // Get student session history
    public function getStudentSessions($student_id) {
        $query = "SELECT s.session_id, s.start_time, s.end_time, s.status, c.course_name, 
                         t.user_id AS tutor_id, u.first_name AS tutor_first_name, u.last_name AS tutor_last_name
                  FROM Sessions s
                  JOIN Courses c ON s.course_id = c.course_id
                  JOIN TutorProfiles t ON s.tutor_id = t.tutor_profile_id
                  JOIN Users u ON t.user_id = u.user_id
                  WHERE s.student_id = :student_id
                  ORDER BY s.start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Leave a review for a tutor
    public function leaveReview($session_id, $student_id, $tutor_id, $rating, $comment, $is_anonymous) {
        $query = "INSERT INTO Reviews (session_id, student_id, tutor_id, rating, comment, is_anonymous, created_at) 
                  VALUES (:session_id, :student_id, :tutor_id, :rating, :comment, :is_anonymous, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":session_id", $session_id);
        $stmt->bindParam(":student_id", $student_id);
        $stmt->bindParam(":tutor_id", $tutor_id);
        $stmt->bindParam(":rating", $rating);
        $stmt->bindParam(":comment", $comment);
        $stmt->bindParam(":is_anonymous", $is_anonymous, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    // Delete student account
    public function deleteStudent($user_id) {
        $query = "DELETE FROM Users WHERE user_id = :user_id AND role = 'student'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }
}
?>
