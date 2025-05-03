<?php
class Review
{
    private $conn;
    // private $table = "Users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Update tutor's overall rating
    public function updateTutorRating($tutor_id)
    {
        $sql = "SELECT AVG(rating) as average_rating FROM Reviews WHERE tutor_id = :tutor_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tutor_id' => $tutor_id]);
        $result = $stmt->fetch();

        $average = round($result['average_rating'], 2);

        $update = "UPDATE TutorProfiles SET overall_rating = :average WHERE tutor_profile_id = :tutor_id";
        $stmt2 = $this->conn->prepare($update);
        $stmt2->execute([':average' => $average, ':tutor_id' => $tutor_id]);
    }

    // Get all reviews for a tutor
    public function getReviewsByTutor($tutor_id)
    {
        $sql = "SELECT * FROM Reviews WHERE tutor_id = :tutor_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tutor_id' => $tutor_id]);
        return $stmt->fetchAll();
    }

    // Get all reviews for a student
    public function getReviewsByStudent($student_id)
    {
        $sql = "SELECT * FROM Reviews WHERE student_id = :student_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    //Get all reviews
    public function getAllReviews()
    {
        $sql = "SELECT * FROM Reviews ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Get first ten reviews
    public function getFirstTenReviews()
    {
        $sql = "SELECT * FROM Reviews ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getTutorReviews($tutor_id)
    {
        $query = "SELECT * FROM reviews WHERE tutor_id = :tutor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTutorAvgRating($tutor_id)
    {
        $query = "SELECT AVG(rating) as average_rating FROM reviews WHERE tutor_id = :tutor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
    }

    function getFilteredReviews($tutor_id, $rating, $length, $start = 0)
    {
        $whereClauses = [];
        $params = [
            ':start' => (int)$start,
            ':length' => (int)$length,
        ];

        // rating clause
        if (!empty($rating) && $rating !== 'all') {
            $whereClauses[] = "rating = :rating";
            $params[':rating'] = (int)$rating;
        }

        // tutor_id clause
        if (!empty($tutor_id)) {
            $whereClauses[] = "tutor_id = :tutor_id";
            $params[':tutor_id'] = (int)$tutor_id;
        }

        $where = '';
        if (count($whereClauses)) {
            $where = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        // --- Get total reviews ---
        $query = "SELECT COUNT(*) as total FROM reviews " . $where;
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            if ($key !== ':start' && $key !== ':length') {
                $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // --- Get filtered reviews ---
        $query = "SELECT * FROM reviews " . $where . " ORDER BY created_at DESC LIMIT :start, :length";
        $stmt = $this->conn->prepare($query);

        // Bind values correctly
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return result
        return [
            "data" => $reviews,
            "total" => $total,
            "filtered" => $total,
        ];
    }

    // get total reviews for a tutor
    function getTotalReviews($tutor_id)
    {
        $query = "SELECT COUNT(*) as total FROM reviews WHERE tutor_id = :tutor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutor_id', $tutor_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
