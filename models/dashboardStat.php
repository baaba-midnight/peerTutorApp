<?php

class DashboardStat
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // number of active tutors
    function getActiveTutors()
    {
        $query = "SELECT COUNT(*) as active_tutors FROM users WHERE role = 'tutor' AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['active_tutors'];
    }

    // number of active students
    function getActiveStudents()
    {
        $query = "SELECT COUNT(*) as active_students FROM users WHERE role = 'student' AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['active_students'];
    }

    // number of completed sessions
    function getCompletedSessions()
    {
        $query = "SELECT COUNT(*) as completed_sessions FROM sessions WHERE status = 'completed'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['completed_sessions'];
    }

    // average rating of all tutors
    function getAverageRating()
    {
        $query = "SELECT ROUND(AVG(overall_rating), 2) as average_rating FROM TutorProfiles WHERE overall_rating IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
    }

    // top 5 rated tutors
    function getTopRatedTutors($limit = 5)
    {
        $stmt = $this->conn->prepare("
        SELECT u.user_id, u.first_name, u.last_name, tp.overall_rating
        FROM Users u
        JOIN TutorProfiles tp ON u.user_id = tp.user_id
        WHERE u.role = 'tutor' AND tp.overall_rating IS NOT NULL
        ORDER BY tp.overall_rating DESC
        LIMIT :limit
    ");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
