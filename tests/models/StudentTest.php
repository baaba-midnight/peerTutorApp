<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/Student.php';

/**
 * Unit tests for the Student model
 */
class StudentTest extends BaseTestCase
{
    /**
     * @var Student The Student model instance being tested
     */
    private $student;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->student = new Student($this->db);
    }

    /**
     * Test getting a student profile by user ID
     */
    public function testGetStudentByUserId()
    {
        // Mock student data from the SQL schema
        $mockStudent = [
            'user_id' => 1,
            'email' => 'student1@example.com',
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'phone_number' => '1234567890',
            'is_active' => 1,
            'bio' => 'CS student with interest in AI.',
            'profile_picture_url' => 'uploads\\avatars\\7_1745937146.jpg',
            'department' => 'Computer Science',
            'year_of_study' => 2
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockStudent,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting a student by user ID
        $student = $this->student->getStudentByUserId(1);

        // Assert student data was retrieved correctly
        $this->assertEquals(1, $student['user_id']);
        $this->assertEquals('student1@example.com', $student['email']);
        $this->assertEquals('Alice', $student['first_name']);
        $this->assertEquals('CS student with interest in AI.', $student['bio']);
        $this->assertEquals('Computer Science', $student['department']);
        $this->assertEquals(2, $student['year_of_study']);
    }

    /**
     * Test getting all students
     */
    public function testGetAllStudents()
    {
        // Mock students data from the SQL schema
        $mockStudents = [
            [
                'user_id' => 1,
                'email' => 'student1@example.com',
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'department' => 'Computer Science',
                'year_of_study' => 2
            ],
            [
                'user_id' => 2,
                'email' => 'student2@example.com',
                'first_name' => 'Eve',
                'last_name' => 'Williams',
                'department' => 'Mathematics',
                'year_of_study' => 1
            ],
            [
                'user_id' => 12,
                'email' => 'kevin13cudjoe@gmail.com',
                'first_name' => 'Kevin',
                'last_name' => 'Cudjoe',
                'department' => null,
                'year_of_study' => null
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockStudents,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting all students
        $students = $this->student->getAllStudents();

        // Assert students data was retrieved correctly
        $this->assertCount(3, $students);
        $this->assertEquals('Alice', $students[0]['first_name']);
        $this->assertEquals('Mathematics', $students[1]['department']);
        $this->assertEquals('Kevin', $students[2]['first_name']);
    }

    /**
     * Test updating a student profile
     */
    public function testUpdateStudentProfile()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test updating a student profile with data that matches the schema
        $result = $this->student->updateStudentProfile(
            12,
            'Updated bio for Kevin Cudjoe',
            'uploads/avatars/12_updated.jpg',
            'Computer Science',
            3
        );

        // Assert the profile was updated successfully
        $this->assertTrue($result);
    }

    /**
     * Test getting student sessions
     */
    public function testGetStudentSessions()
    {
        // Mock sessions data matching the SQL schema
        $mockSessions = [
            [
                'session_id' => 1,
                'start_time' => '2025-05-01 10:00:00',
                'end_time' => '2025-05-01 11:00:00',
                'status' => 'completed',
                'course_name' => 'Intro to Programming',
                'tutor_id' => 1,
                'tutor_first_name' => 'Bob',
                'tutor_last_name' => 'Smith'
            ],
            [
                'session_id' => 3,
                'start_time' => '2025-05-03 09:00:00',
                'end_time' => '2025-05-03 10:00:00',
                'status' => 'cancelled',
                'course_name' => 'Data Structures',
                'tutor_id' => 1,
                'tutor_first_name' => 'Bob',
                'tutor_last_name' => 'Smith'
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockSessions,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting student sessions
        $sessions = $this->student->getStudentSessions(1);

        // Assert sessions data was retrieved correctly
        $this->assertCount(2, $sessions);
        $this->assertEquals('Intro to Programming', $sessions[0]['course_name']);
        $this->assertEquals('cancelled', $sessions[1]['status']);
    }

    /**
     * Test leaving a review for a tutor
     */
    public function testLeaveReview()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test leaving a review with schema-matching data
        $result = $this->student->leaveReview(
            1,  // session_id
            1,  // student_id
            1,  // tutor_id
            5,  // rating
            'Excellent tutor, very helpful and knowledgeable!', // comment
            true // is_anonymous
        );

        // Assert the review was submitted successfully
        $this->assertTrue($result);
    }

    /**
     * Test deleting a student account
     */
    public function testDeleteStudent()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test deleting a student account
        $result = $this->student->deleteStudent(12);

        // Assert the deletion was successful
        $this->assertTrue($result);
    }
}