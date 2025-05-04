<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/Appointment.php';

/**
 * Unit tests for the Appointment model
 */
class AppointmentTest extends BaseTestCase
{
    /**
     * @var Appointment The Appointment model instance being tested
     */
    private $appointment;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->appointment = new Appointment($this->db);
    }

    /**
     * Test creating a new appointment successfully
     */
    public function testCreateAppointmentSuccess()
    {
        // Set up mock statement
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure database mock behavior
        $this->db->method('beginTransaction')->willReturn(true);
        $this->db->method('commit')->willReturn(true);
        $this->db->method('prepare')->willReturn($stmt);
        $this->db->method('lastInsertId')->willReturn(9);

        // Test creating a new appointment with data matching the schema
        $result = $this->appointment->create(
            12, // student_id
            3, // tutor_id
            1, // subject_id
            '2025-05-10 14:00:00', // start_datetime
            '2025-05-10 15:00:00', // end_datetime
            'https://zoom.us/j/123456789', // meeting_link
            'Help with recursion' // notes
        );

        // Assert appointment creation was successful
        $this->assertEquals('success', $result['status']);
        $this->assertEquals(9, $result['appointment_id']);
    }

    /**
     * Test creating an appointment with database error
     */
    public function testCreateAppointmentFailure()
    {
        // Simulate database error during insert
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willThrowException(new PDOException('Database error'));

        // Configure database mock behavior
        $this->db->method('beginTransaction')->willReturn(true);
        $this->db->method('prepare')->willReturn($stmt);
        $this->db->method('rollBack')->willReturn(true);

        // Test creating a new appointment with an error
        $result = $this->appointment->create(
            12, // student_id 
            3, // tutor_id
            1, // subject_id
            '2025-05-10 14:00:00', // start_datetime
            '2025-05-10 15:00:00' // end_datetime
        );

        // Assert appointment creation failed
        $this->assertEquals('error', $result['status']);
        $this->assertStringContainsString('Failed to create appointment', $result['message']);
    }

    /**
     * Test retrieving an appointment by ID
     */
    public function testGetAppointmentById()
    {
        // Mock appointment data matching the schema
        $mockAppointmentData = [
            'appointment_id' => 7,
            'student_id' => 12,
            'tutor_id' => 4,
            'subject_id' => 2,
            'start_datetime' => '2025-05-03 22:26:00',
            'end_datetime' => '2025-05-03 23:26:00',
            'status' => 'confirmed',
            'meeting_link' => 'zoom.url',
            'notes' => 'Stacks are my major concern.',
            'student_first_name' => 'Kevin',
            'student_last_name' => 'Cudjoe',
            'student_email' => 'kevin13cudjoe@gmail.com',
            'tutor_first_name' => 'Grace',
            'tutor_last_name' => 'Lee',
            'tutor_email' => 'tutor2@example.com'
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetch' => $mockAppointmentData,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test retrieving an appointment by ID
        $appointment = $this->appointment->getById(7);

        // Assert appointment data was retrieved correctly
        $this->assertEquals(7, $appointment['appointment_id']);
        $this->assertEquals(12, $appointment['student_id']);
        $this->assertEquals(4, $appointment['tutor_id']);
        $this->assertEquals('Kevin', $appointment['student_first_name']);
        $this->assertEquals('Grace', $appointment['tutor_first_name']);
        $this->assertEquals('confirmed', $appointment['status']);
    }

    /**
     * Test getting appointments for a user
     */
    public function testGetAppointmentsForUser()
    {
        // Mock appointment data matching the actual data in the SQL file
        $mockAppointments = [
            [
                'appointment_id' => 7,
                'student_id' => 12,
                'tutor_id' => 4,
                'start_datetime' => '2025-05-03 22:26:00',
                'end_datetime' => '2025-05-03 23:26:00',
                'status' => 'confirmed',
                'course_name' => 'Data Structures',
                'meeting_link' => 'zoom.url',
                'notes' => 'Stacks are my major concern.',
                'student_first_name' => 'Kevin',
                'student_last_name' => 'Cudjoe',
                'tutor_first_name' => 'Grace',
                'tutor_last_name' => 'Lee'
            ],
            [
                'appointment_id' => 8,
                'student_id' => 12,
                'tutor_id' => 3,
                'start_datetime' => '2025-05-07 11:00:00',
                'end_datetime' => '2025-05-07 12:00:00',
                'status' => 'pending',
                'course_name' => 'Intro to Programming',
                'meeting_link' => 'googlemeet.url',
                'notes' => 'I didn\'t listen in class',
                'student_first_name' => 'Kevin',
                'student_last_name' => 'Cudjoe',
                'tutor_first_name' => 'Bob',
                'tutor_last_name' => 'Smith'
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockAppointments,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting appointments for a student
        $appointments = $this->appointment->getForUser(12, 'student');

        // Assert appointments were retrieved correctly
        $this->assertCount(2, $appointments);
        $this->assertEquals(7, $appointments[0]['appointment_id']);
        $this->assertEquals('confirmed', $appointments[0]['status']);
        $this->assertEquals('Data Structures', $appointments[0]['course_name']);
        $this->assertEquals('I didn\'t listen in class', $appointments[1]['notes']);
    }

    /**
     * Test updating appointment status
     */
    public function testUpdateAppointmentStatus()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test updating appointment status using values from the schema
        $result = $this->appointment->updateStatus(7, 'completed', 'Session completed successfully');

        // Assert the update was successful
        $this->assertTrue($result);
    }

    /**
     * Test deleting an appointment
     */
    public function testDeleteAppointment()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test deleting an appointment
        $result = $this->appointment->delete(8);

        // Assert the deletion was successful
        $this->assertTrue($result);
    }
}