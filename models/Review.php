<?php
class Review {
    private $conn;
    // private $table = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Update tutor's overall rating
    public function updateTutorRating($tutor_id) {
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
    public function getReviewsByTutor($tutor_id) {
        $sql = "SELECT * FROM Reviews WHERE tutor_id = :tutor_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tutor_id' => $tutor_id]);
        return $stmt->fetchAll();
    }

    // Get all reviews for a student
    public function getReviewsByStudent($student_id) {
        $sql = "SELECT * FROM Reviews WHERE student_id = :student_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll();
    }

    //Get all reviews
    public function getAllReviews() {
        $sql = "SELECT * FROM Reviews ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Get first ten reviews
    public function getFirstTenReviews() {
        $sql = "SELECT * FROM Reviews ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
?>
