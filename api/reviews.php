<?php
require_once('../config/database.php');
require_once('../views/reviews/view-reviews.php');

// Get database connection
$conn = getConnection();

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single review
            getReview($conn, $_GET['id']);
        } else {
            // Get list of reviews with filters
            $filters = [
                'reviewer_id' => $_GET['reviewer_id'] ?? null,
                'reviewee_id' => $_GET['reviewee_id'] ?? null,
                'rating' => $_GET['rating'] ?? null,
                'appointment_id' => $_GET['appointment_id'] ?? null
            ];
            getReviews($conn, $filters);
        }
        break;

    case 'POST':
        // Create new review
        $data = json_decode(file_get_contents('php://input'), true);
        createReview($conn, $data);
        break;

    case 'PUT':
        // Update review
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Review ID is required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        updateReview($conn, $_GET['id'], $data);
        break;

    case 'DELETE':
        // Delete review
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Review ID is required']);
            exit;
        }
        deleteReview($conn, $_GET['id']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Function to get a single review
function getReview($conn, $id) {
    $sql = "SELECT r.*, 
            CONCAT(u1.first_name, ' ', u1.last_name) as reviewer_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as reviewee_name,
            a.start_datetime as appointment_date,
            s.name as subject_name
            FROM Reviews r
            JOIN Users u1 ON r.reviewer_id = u1.user_id
            JOIN Users u2 ON r.reviewee_id = u2.user_id
            JOIN Appointments a ON r.appointment_id = a.appointment_id
            JOIN Subjects s ON a.subject_id = s.subject_id
            WHERE r.review_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$review) {
        http_response_code(404);
        echo json_encode(['error' => 'Review not found']);
        exit;
    }

    echo json_encode($review);
}
// Function to get filtered list of reviews
function getReviews($conn, $filters) {
    $sql = "SELECT r.*, 
            CONCAT(u1.first_name, ' ', u1.last_name) as reviewer_name,
            CONCAT(u2.first_name, ' ', u2.last_name) as reviewee_name,
            a.start_datetime as appointment_date,
            s.name as subject_name
            FROM Reviews r
            JOIN Users u1 ON r.reviewer_id = u1.user_id
            JOIN Users u2 ON r.reviewee_id = u2.user_id
            JOIN Appointments a ON r.appointment_id = a.appointment_id
            JOIN Subjects s ON a.subject_id = s.subject_id
            WHERE 1=1";
    
    $params = [];

    if ($filters['reviewer_id']) {
        $sql .= " AND r.reviewer_id = :reviewer_id";
        $params[':reviewer_id'] = $filters['reviewer_id'];
    }
    if ($filters['reviewee_id']) {
        $sql .= " AND r.reviewee_id = :reviewee_id";
        $params[':reviewee_id'] = $filters['reviewee_id'];
    }
    if ($filters['rating']) {
        $sql .= " AND r.rating = :rating";
        $params[':rating'] = $filters['rating'];
    }
    if ($filters['appointment_id']) {
        $sql .= " AND r.appointment_id = :appointment_id";
        $params[':appointment_id'] = $filters['appointment_id'];
    }

    $sql .= " ORDER BY r.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reviews as &$row) {
        if ($row['is_anonymous']) {
            $row['reviewer_name'] = 'Anonymous';
        }
    }

    echo json_encode($reviews);
}

// Function to create a new review
function createReview($conn, $data) {
    // Validate required fields
    $required = ['reviewer_id', 'reviewee_id', 'appointment_id', 'rating', 'title', 'content'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Missing required field: $field"]);
            exit;
        }
    }

    // Validate rating range
    if ($data['rating'] < 1 || $data['rating'] > 5) {
        http_response_code(400);
        echo json_encode(['error' => 'Rating must be between 1 and 5']);
        exit;
    }

    // Check if review already exists for this appointment
    $sql = "SELECT review_id FROM Reviews WHERE appointment_id = ? AND reviewer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $data['appointment_id'], $data['reviewer_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Review already exists for this appointment']);
        exit;
    }

    // Insert review
    $sql = "INSERT INTO Reviews (reviewer_id, reviewee_id, appointment_id, rating, title, content, is_anonymous) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $is_anonymous = isset($data['is_anonymous']) ? $data['is_anonymous'] : false;
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiissi', 
        $data['reviewer_id'],
        $data['reviewee_id'],
        $data['appointment_id'],
        $data['rating'],
        $data['title'],
        $data['content'],
        $is_anonymous
    );

    if ($stmt->execute()) {
        $review_id = $conn->insert_id;

        // Create notification for reviewee
        $sql = "INSERT INTO Notifications (user_id, type, title, content) 
                VALUES (?, 'review', 'New Review Received', 
                'Someone has left you a new review!')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $data['reviewee_id']);
        $stmt->execute();

        // Calculate and update average rating for reviewee
        updateAverageRating($conn, $data['reviewee_id']);

        http_response_code(201);
        echo json_encode([
            'message' => 'Review created successfully',
            'review_id' => $review_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create review']);
    }
}

// Function to update a review
function updateReview($conn, $id, $data) {
    $allowed_fields = ['rating', 'title', 'content', 'is_anonymous'];
    $updates = [];
    $params = [];
    $types = '';

    foreach ($data as $field => $value) {
        if (in_array($field, $allowed_fields)) {
            $updates[] = "$field = ?";
            $params[] = $value;
            $types .= ($field === 'rating' ? 'i' : 's');
        }
    }

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        exit;
    }

    $params[] = $id;
    $types .= 'i';

    $sql = "UPDATE Reviews SET " . implode(', ', $updates) . " WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // If rating was updated, recalculate average rating
        if (isset($data['rating'])) {
            $sql = "SELECT reviewee_id FROM Reviews WHERE review_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $review = $result->fetch_assoc();
            updateAverageRating($conn, $review['reviewee_id']);
        }

        echo json_encode(['message' => 'Review updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update review']);
    }
}

// Function to delete a review
function deleteReview($conn, $id) {
    // Get reviewee_id before deleting
    $sql = "SELECT reviewee_id FROM Reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $review = $result->fetch_assoc();

    if (!$review) {
        http_response_code(404);
        echo json_encode(['error' => 'Review not found']);
        exit;
    }

    $sql = "DELETE FROM Reviews WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Update average rating after deletion
        updateAverageRating($conn, $review['reviewee_id']);
        echo json_encode(['message' => 'Review deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete review']);
    }
}

// Helper function to update average rating
function updateAverageRating($conn, $user_id) {
    $sql = "SELECT AVG(rating) as avg_rating FROM Reviews WHERE reviewee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $avg = $result->fetch_assoc()['avg_rating'];

    // Store the average rating in the Users table or a separate ratings table
    // This implementation depends on your database structure
}

$conn = null; // Close the connection