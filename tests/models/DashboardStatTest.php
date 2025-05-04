<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/dashboardStat.php';

/**
 * Unit tests for the DashboardStat model
 */
class DashboardStatTest extends BaseTestCase
{
    /**
     * @var DashboardStat The DashboardStat model instance being tested
     */
    private $dashboardStat;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardStat = new DashboardStat($this->db);
    }

    /**
     * Test getting the count of active tutors
     */
    public function testGetActiveTutors()
    {
        // Mock active tutors count - there are 3 active tutors in the SQL schema
        $mockActiveTutors = ['active_tutors' => 3];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockActiveTutors,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting active tutors count
        $activeTutors = $this->dashboardStat->getActiveTutors();

        // Assert the count matches the expected value
        $this->assertEquals(3, $activeTutors);
    }

    /**
     * Test getting the count of active students
     */
    public function testGetActiveStudents()
    {
        // Mock active students count - there are 3 active students in the SQL schema
        $mockActiveStudents = ['active_students' => 3];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockActiveStudents,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting active students count
        $activeStudents = $this->dashboardStat->getActiveStudents();

        // Assert the count matches the expected value
        $this->assertEquals(3, $activeStudents);
    }

    /**
     * Test getting the count of completed sessions
     */
    public function testGetCompletedSessions()
    {
        // Mock completed sessions count from the schema
        $mockCompletedSessions = ['completed_sessions' => 1];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockCompletedSessions,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting completed sessions count
        $completedSessions = $this->dashboardStat->getCompletedSessions();

        // Assert the count matches the expected value
        $this->assertEquals(1, $completedSessions);
    }

    /**
     * Test getting the average rating of all tutors
     */
    public function testGetAverageRating()
    {
        // Mock average rating based on the SQL schema
        $mockAverageRating = ['average_rating' => 2.5];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockAverageRating,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting average rating
        $averageRating = $this->dashboardStat->getAverageRating();

        // Assert the average rating matches the expected value
        $this->assertEquals(2.5, $averageRating);
    }

    /**
     * Test getting the top rated tutors
     */
    public function testGetTopRatedTutors()
    {
        // Mock top rated tutors data based on the SQL schema
        $mockTopRatedTutors = [
            [
                'user_id' => 4,
                'first_name' => 'Grace',
                'last_name' => 'Lee',
                'overall_rating' => 4.0
            ],
            [
                'user_id' => 3,
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'overall_rating' => 1.0
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockTopRatedTutors,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting top rated tutors
        $topRatedTutors = $this->dashboardStat->getTopRatedTutors(2);

        // Assert the top rated tutors data was retrieved correctly
        $this->assertCount(2, $topRatedTutors);
        $this->assertEquals('Grace', $topRatedTutors[0]['first_name']);
        $this->assertEquals(4.0, $topRatedTutors[0]['overall_rating']);
        $this->assertEquals('Bob', $topRatedTutors[1]['first_name']);
    }
}