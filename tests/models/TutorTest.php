<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/Tutor.php';

/**
 * Unit tests for the Tutor model
 */
class TutorTest extends BaseTestCase
{
    /**
     * @var Tutor The Tutor model instance being tested
     */
    private $tutor;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tutor = new Tutor($this->db);
    }

    /**
     * Test getting a tutor profile by user ID
     */
    public function testGetTutorByUserId()
    {
        // Mock tutor data
        $mockTutor = [
            'user_id' => 3,
            'email' => 'tutor1@example.com',
            'first_name' => 'Bob',
            'last_name' => 'Smith',
            'phone_number' => '0987654321',
            'is_active' => 1,
            'bio' => 'Experienced mathematics tutor',
            'profile_picture_url' => 'uploads/avatars/7_1745937146.jpg',
            'hourly_rate' => 20.00,
            'availability_schedule' => '{"Monday":"11:00 AM - 12:00 PM","Tuesday":"10:00 AM - 2:00 PM","Wednesday":"10:00 AM - 2:00 PM","Thursday":"9:00 AM - 12:00 PM","Friday":"2:00 PM - 5:00 PM"}',
            'overall_rating' => 4.8,
            'is_verified' => 1
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockTutor,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting a tutor by user ID
        $tutor = $this->tutor->getTutorByUserId(3);

        // Assert tutor data was retrieved correctly
        $this->assertEquals(3, $tutor['user_id']);
        $this->assertEquals('tutor1@example.com', $tutor['email']);
        $this->assertEquals('Bob', $tutor['first_name']);
        $this->assertEquals('Experienced mathematics tutor', $tutor['bio']);
        $this->assertEquals(20.00, $tutor['hourly_rate']);
        $this->assertEquals(4.8, $tutor['overall_rating']);
    }

    /**
     * Test getting all tutors
     */
    public function testGetAllTutors()
    {
        // Mock tutors data
        $mockTutors = [
            [
                'user_id' => 3,
                'email' => 'tutor1@example.com',
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'bio' => 'Experienced mathematics tutor',
                'overall_rating' => 4.8
            ],
            [
                'user_id' => 4,
                'email' => 'tutor2@example.com',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'bio' => 'Expert in calculus and geometry',
                'overall_rating' => 4.9
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockTutors,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting all tutors
        $tutors = $this->tutor->getAllTutors();

        // Assert tutors data was retrieved correctly
        $this->assertCount(2, $tutors);
        $this->assertEquals('Bob', $tutors[0]['first_name']);
        $this->assertEquals('tutor2@example.com', $tutors[1]['email']);
    }

    /**
     * Test updating a tutor profile
     */
    public function testUpdateTutorProfile()
    {
        // Create mock PDOStatements for both queries
        $stmt1 = $this->createStatementMock([
            'execute' => true
        ]);
        
        $stmt2 = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return different statements based on the query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($stmt1, $stmt2) {
                if (strpos($sql, 'INSERT INTO Profiles') !== false) {
                    return $stmt1;
                }
                return $stmt2;
            }));

        // Test updating a tutor profile with properly formatted availability schedule
        $result = $this->tutor->updateTutorProfile(
            3,
            'Updated bio for Bob Smith',
            'uploads/avatars/3_updated.jpg',
            30.00,
            '{"Monday":"9:00 AM - 12:00 PM, 1:00 PM - 4:00 PM","Tuesday":"10:00 AM - 2:00 PM","Wednesday":"","Thursday":"","Friday":""}'
        );

        // Assert the profile was updated successfully
        $this->assertTrue($result);
    }

    /**
     * Test getting tutor courses
     */
    public function testGetTutorCourses()
    {
        // Mock courses data
        $mockCourses = [
            [
                'course_id' => 1,
                'course_name' => 'Calculus I',
                'course_code' => 'MATH101',
                'department' => 'Mathematics'
            ],
            [
                'course_id' => 2,
                'course_name' => 'Linear Algebra',
                'course_code' => 'MATH201',
                'department' => 'Mathematics'
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockCourses,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting tutor courses
        $courses = $this->tutor->getTutorCourses(3);

        // Assert courses data was retrieved correctly
        $this->assertCount(2, $courses);
        $this->assertEquals('Calculus I', $courses[0]['course_name']);
        $this->assertEquals('MATH201', $courses[1]['course_code']);
    }

    /**
     * Test updating tutor courses
     */
    public function testUpdateTutorCourses()
    {
        // Create mock PDOStatements for both queries
        $stmt1 = $this->createStatementMock([
            'execute' => true
        ]);
        
        $stmt2 = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return different statements based on the query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($stmt1, $stmt2) {
                if (strpos($sql, 'DELETE FROM') !== false) {
                    return $stmt1;
                }
                return $stmt2;
            }));

        // Test updating tutor courses with new course IDs
        $result = $this->tutor->updateTutorCourses(3, [1, 2, 3]);

        // Assert the courses were updated successfully
        $this->assertTrue($result);
    }

    /**
     * Test getting tutor reviews
     */
    public function testGetTutorReviews()
    {
        // Mock reviews data based on the SQL schema
        $mockReviews = [
            [
                'review_id' => 1,
                'session_id' => 1,
                'tutor_id' => 1,
                'student_id' => 1,
                'rating' => 5,
                'comment' => 'Excellent tutor!',
                'is_anonymous' => 0,
                'created_at' => '2025-05-01 10:00:00',
                'student_first_name' => 'Alice',
                'student_last_name' => 'Johnson'
            ],
            [
                'review_id' => 2,
                'session_id' => 2,
                'tutor_id' => 1,
                'student_id' => 2,
                'rating' => 4,
                'comment' => 'Very helpful',
                'is_anonymous' => 1,
                'created_at' => '2025-05-02 11:00:00',
                'student_first_name' => 'Eve',
                'student_last_name' => 'Williams'
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockReviews,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting tutor reviews
        $reviews = $this->tutor->getTutorReviews(1);

        // Assert reviews data was retrieved correctly
        $this->assertCount(2, $reviews);
        $this->assertEquals('Excellent tutor!', $reviews[0]['comment']);
        $this->assertEquals(1, $reviews[1]['is_anonymous']);
    }

    /**
     * Test deleting a tutor account
     */
    public function testDeleteTutor()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test deleting a tutor
        $result = $this->tutor->deleteTutor(3);

        // Assert the deletion was successful
        $this->assertTrue($result);
    }
}