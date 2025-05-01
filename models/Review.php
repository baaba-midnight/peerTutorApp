<?php
require_once '../../config/database.php'; // Include database connection

// Get current tutor ID (assuming it's stored in session)
if (isset($_SESSION['user_id'])) {
    $tutor_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user ID is not set
    header("Location: ../../login.php");
    exit();
}
// Get all reviews for the current tutor
$sql = "SELECT r.*, 
               a.start_datetime, 
               s.name AS subject_name,
               u.first_name, 
               u.last_name,
               SUBSTRING(u.first_name, 1, 1) AS first_initial,
               SUBSTRING(u.last_name, 1, 1) AS last_initial
        FROM Reviews r
        JOIN Appointments a ON r.appointment_id = a.appointment_id
        JOIN Users u ON r.reviewer_id = u.user_id
        JOIN Subjects s ON a.subject_id = s.subject_id
        WHERE r.reviewee_id = ? 
        ORDER BY r.created_at DESC
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutor_id);
$stmt->execute();
$result = $stmt->get_result();

// Function to format date
function formatReviewDate($datetime) {
    $date = new DateTime($datetime);
    return $date->format('d M, Y');
}

// Function to generate star rating HTML
function generateStarRating($rating) {
    $html = '<div class="rating-stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star rating-star"></i>';
        } else {
            $html .= '<i class="far fa-star rating-star"></i>';
        }
    }
    $html .= '</div>';
    return $html;
}
?>

<div class="card" id="reviews-ratings">
    <div class="card-header">
        <h2 class="card-title">Recent Reviews</h2>
        <a href="../reviews/view-reviews.php">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($row['is_anonymous']): ?>
                                            <span class="initials rounded-circle bg-secondary text-white fw-semibold">AN</span>
                                            <div>
                                                <div class="fw-semibold">Anonymous</div>
                                            </div>
                                        <?php else: ?>
                                            <span class="initials rounded-circle bg-primary text-white fw-semibold">
                                                <?php echo $row['first_initial'] . $row['last_initial']; ?>
                                            </span>
                                            <div>
                                                <div class="fw-semibold">
                                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo generateStarRating($row['rating']); ?>
                                </td>
                                <td>
                                    <div class="review-content">
                                        <?php if (!empty($row['title'])): ?>
                                            <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($row['content']); ?>
                                    </div>
                                </td>
                                <td><?php echo formatReviewDate($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-3">
                                <p class="text-muted mb-0">No reviews yet</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>