<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/User.php';

/**
 * Unit tests for the User model
 */
class UserTest extends BaseTestCase
{
    /**
     * @var User The User model instance being tested
     */
    private $user;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User($this->db);
    }

    /**
     * Test login method with valid credentials
     */
    public function testLoginWithValidCredentials()
    {
        // Mock user data for a successful login with data from the SQL schema
        $mockUserData = [
            'user_id' => 1,
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'password_hash' => password_hash('@Kc0553176080', PASSWORD_DEFAULT),
            'email' => 'student1@example.com',
            'role' => 'student',
            'profile_picture_url' => 'uploads\avatars\7_1745937146.jpg',
            'is_active' => 1
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'rowCount' => 1,
            'fetch' => $mockUserData,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test login with correct credentials
        $result = $this->user->login('student1@example.com', '@Kc0553176080');

        // Assert the login was successful
        $this->assertEquals('success', $result['status']);
        $this->assertEquals(1, $result['user']['user_id']);
        $this->assertEquals('student1@example.com', $result['user']['email']);
        $this->assertEquals('student', $result['user']['role']);
    }

    /**
     * Test login method with invalid credentials
     */
    public function testLoginWithInvalidCredentials()
    {
        // Mock user data with incorrect password, using actual data from the SQL schema
        $mockUserData = [
            'user_id' => 1,
            'password_hash' => password_hash('@Kc0553176080', PASSWORD_DEFAULT),
            'email' => 'student1@example.com',
            'is_active' => 1
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'rowCount' => 1,
            'fetch' => $mockUserData,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test login with incorrect password
        $result = $this->user->login('student1@example.com', 'wrongpassword');

        // Assert the login failed
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Incorrect password.', $result['message']);
    }

    /**
     * Test login method with non-existent user
     */
    public function testLoginWithNonExistentUser()
    {
        // Create a mock PDOStatement that returns no rows
        $stmt = $this->createStatementMock([
            'rowCount' => 0,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test login with a non-existent user
        $result = $this->user->login('nonexistent@example.com', 'password123');

        // Assert the login failed
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('User not found or account is inactive.', $result['message']);
    }

    /**
     * Test user registration method
     */
    public function testRegisterNewUser()
    {
        // Create mock PDOStatement for user check that returns no existing user
        $checkStmt = $this->createStatementMock([
            'rowCount' => 0,
            'execute' => true
        ]);

        // Create mock PDOStatement for insert operations
        $insertStmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure database mock behavior for transaction operations
        $this->db->method('beginTransaction')->willReturn(true);
        $this->db->method('commit')->willReturn(true);
        $this->db->method('lastInsertId')->willReturn(13);

        // Configure the mock database to return different statements based on query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($checkStmt, $insertStmt) {
                if (strpos($sql, 'SELECT user_id FROM Users') !== false) {
                    return $checkStmt;
                }
                return $insertStmt;
            }));

        // Test registration with valid data and role matching the schema
        $result = $this->user->register(
            'David',
            'Miller',
            'david.miller@example.com',
            'SecurePass123!',
            'student',
            '5556667777'
        );

        // Assert registration was successful
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Registration successful.', $result['message']);
        $this->assertEquals(13, $result['user_id']);
    }

    /**
     * Test registration with an existing email
     */
    public function testRegisterWithExistingEmail()
    {
        // Create mock PDOStatement that indicates the email already exists
        $checkStmt = $this->createStatementMock([
            'rowCount' => 1,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($checkStmt);
        $this->db->method('beginTransaction')->willReturn(true);

        // Test registration with an existing email from the schema
        $result = $this->user->register(
            'Alice',
            'Johnson',
            'student1@example.com',  // This email already exists in the schema
            'SecurePass123!',
            'student',
            '1234567890'
        );

        // Assert registration failed due to existing email
        $this->assertEquals('error', $result['status']);
        $this->assertEquals('User already exists with this email.', $result['message']);
    }

    /**
     * Test getUserById method
     */
    public function testGetUserById()
    {
        // Mock user data based on the SQL schema
        $mockUserData = [
            'user_id' => 1,
            'email' => 'student1@example.com',
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'phone_number' => '1234567890',
            'role' => 'student',
            'is_active' => 1,
            'bio' => 'CS student with interest in AI.',
            'profile_picture_url' => 'uploads/avatars/7_1745937146.jpg',
            'department' => 'Computer Science',
            'year_of_study' => 2
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockUserData,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test get user by ID
        $user = $this->user->getUserById(1);

        // Assert user data was retrieved correctly
        $this->assertEquals(1, $user['user_id']);
        $this->assertEquals('student1@example.com', $user['email']);
        $this->assertEquals('Alice', $user['first_name']);
        $this->assertEquals('Johnson', $user['last_name']);
        $this->assertEquals('Computer Science', $user['department']);
        $this->assertEquals('CS student with interest in AI.', $user['bio']);
    }

    /**
     * Test updateUser method
     */
    public function testUpdateUser()
    {
        // Mock successful statement execution
        $stmt = $this->createStatementMock([
            'rowCount' => 1,
            'execute' => true
        ]);

        // Configure database mock behavior for transaction operations
        $this->db->method('beginTransaction')->willReturn(true);
        $this->db->method('commit')->willReturn(true);
        $this->db->method('prepare')->willReturn($stmt);

        // Test updating user data
        $updateData = [
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'bio' => 'Updated bio for Alice, focusing on machine learning.',
            'phone_number' => '1234567890'
        ];

        $result = $this->user->updateUser(1, $updateData);

        // Assert update was successful
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Profile updated successfully', $result['message']);
    }
    

}