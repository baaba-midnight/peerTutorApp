<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/Review.php';

/**
 * Unit tests for the Review model
 */
class ReviewTest extends BaseTestCase
{
    /**
     * @var Review The Review model instance being tested
     */
    private $review;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->review = new Review($this->db);
    }

    /**
     * Test updating a tutor's overall rating
     */
    public function testUpdateTutorRating()
    {
        // Mock result for average rating query
        $avgRatingResult = ['average_rating' => 4.0];
        
        // Create first statement mock for fetching average rating
        $stmt1 = $this->createStatementMock([
            'fetch' => $avgRatingResult,
            'execute' => true
        ]);
        
        // Create second statement mock for update operation
        $stmt2 = $this->createStatementMock([
            'execute' => true
        ]);
        
        // Configure the mock database to return different statements based on the query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($stmt1, $stmt2) {
                if (strpos($sql, 'SELECT AVG') !== false) {
                    return $stmt1;
                }
                return $stmt2;
            }));
        
        // Call the method under test with a tutor ID from the schema
        $this->review->updateTutorRating(1); 
        
        // This test passes if no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Test getting reviews by tutor ID
     */
    public function testGetReviewsByTutor()
    {
        // Mock review data based on the SQL schema
        $mockReviews = [
            [
                'review_id' => 2,
                'session_id' => 2,
                'student_id' => 2,
                'tutor_id' => 2,
                'rating' => 4,
                'comment' => 'Very helpful!',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 19:43:05'
            ],
            [
                'review_id' => 4,
                'session_id' => 1,
                'student_id' => 1,
                'tutor_id' => 1,
                'rating' => 1,
                'comment' => 'Magna voluptate face',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 22:03:36'
            ]
        ];
        
        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockReviews,
            'execute' => true
        ]);
        
        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);
        
        // Test getting reviews for a tutor
        $reviews = $this->review->getReviewsByTutor(1);
        
        // Assert reviews were retrieved correctly
        $this->assertCount(2, $reviews);
        $this->assertEquals(4, $reviews[0]['rating']);
        $this->assertEquals('Magna voluptate face', $reviews[1]['comment']);
        $this->assertEquals(1, $reviews[1]['is_anonymous']);
    }

    /**
     * Test getting reviews by student ID
     */
    public function testGetReviewsByStudent()
    {
        // Mock review data matching the SQL schema
        $mockReviews = [
            [
                'review_id' => 4,
                'session_id' => 1,
                'student_id' => 1,
                'tutor_id' => 1,
                'rating' => 1,
                'comment' => 'Magna voluptate face',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 22:03:36'
            ]
        ];
        
        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockReviews,
            'execute' => true
        ]);
        
        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);
        
        // Test getting reviews by a student
        $reviews = $this->review->getReviewsByStudent(1);
        
        // Assert reviews were retrieved correctly
        $this->assertCount(1, $reviews);
        $this->assertEquals(1, $reviews[0]['rating']);
        $this->assertEquals('Magna voluptate face', $reviews[0]['comment']);
    }

    /**
     * Test getting all reviews
     */
    public function testGetAllReviews()
    {
        // Mock review data
        $mockReviews = [
            [
                'review_id' => 1,
                'student_id' => 2,
                'tutor_id' => 3,
                'rating' => 5,
                'comment' => 'Excellent tutor!'
            ],
            [
                'review_id' => 2,
                'student_id' => 4,
                'tutor_id' => 3,
                'rating' => 4,
                'comment' => 'Very helpful'
            ],
            [
                'review_id' => 3,
                'student_id' => 2,
                'tutor_id' => 5,
                'rating' => 3,
                'comment' => 'Good but could improve'
            ]
        ];
        
        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockReviews,
            'execute' => true
        ]);
        
        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);
        
        // Test getting all reviews
        $reviews = $this->review->getAllReviews();
        
        // Assert all reviews were retrieved
        $this->assertCount(3, $reviews);
    }
    
    /**
     * Test getting a tutor's average rating
     */
    public function testGetTutorAvgRating()
    {
        // Mock result for average rating query
        $avgRating = ['average_rating' => 1.0];
        
        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $avgRating,
            'execute' => true
        ]);
        
        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);
        
        // Test getting a tutor's average rating with a tutor ID from the schema
        $rating = $this->review->getTutorAvgRating(1);
        
        // Assert the correct average rating was retrieved
        $this->assertEquals(1.0, $rating);
    }
    
    /**
     * Test getting filtered reviews
     */
    public function testGetFilteredReviews()
    {
        // Mock for count query
        $countResult = ['total' => 2];
        $stmt1 = $this->createStatementMock([
            'fetch' => $countResult,
            'execute' => true
        ]);
        
        // Mock review data for filtered query matching the schema
        $mockReviews = [
            [
                'review_id' => 2,
                'session_id' => 2,
                'student_id' => 2,
                'tutor_id' => 2,
                'rating' => 4,
                'comment' => 'Very helpful!',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 19:43:05'
            ],
            [
                'review_id' => 4,
                'session_id' => 1,
                'student_id' => 1,
                'tutor_id' => 1,
                'rating' => 1,
                'comment' => 'Magna voluptate face',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 22:03:36'
            ]
        ];
        
        // Create a mock for the second query
        $stmt2 = $this->createStatementMock([
            'fetchAll' => $mockReviews,
            'execute' => true
        ]);
        
        // Configure the mock database to return different statements based on the query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($stmt1, $stmt2) {
                if (strpos($sql, 'COUNT(*)') !== false) {
                    return $stmt1;
                }
                return $stmt2;
            }));
        
        // Test getting all reviews (no filter)
        $result = $this->review->getFilteredReviews(null, 'all', 10, 0);
        
        // Assert filtered reviews were retrieved correctly
        $this->assertEquals(2, $result['total']);
        $this->assertEquals(2, $result['filtered']);
        $this->assertCount(2, $result['data']);
        $this->assertEquals(4, $result['data'][0]['rating']);
        $this->assertEquals(1, $result['data'][1]['is_anonymous']);
    }
    
    /**
     * Test getting total reviews for a tutor
     */
    public function testGetTotalReviews()
    {
        // Mock result for total reviews query
        $totalResult = ['total' => 1];
        
        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $totalResult,
            'execute' => true
        ]);
        
        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);
        
        // Test getting total reviews for a tutor
        $total = $this->review->getTotalReviews(1);
        
        // Assert the correct total was retrieved
        $this->assertEquals(1, $total);
    }
}