<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/SystemLog.php';

/**
 * Unit tests for the SystemLog model
 */
class SystemLogTest extends BaseTestCase
{
    /**
     * @var SystemLog The SystemLog model instance being tested
     */
    private $systemLog;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->systemLog = new SystemLog();
        
        // Set the db property manually since we're not using the constructor
        $this->setPrivateProperty($this->systemLog, 'db', $this->db);
    }
    
    /**
     * Helper method to set private/protected properties
     */
    protected function setPrivateProperty($object, $propertyName, $value)
    {
        $reflection = new ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Test fetching filtered logs
     */
    public function testGetFilteredLogs()
    {
        // Mock log data for the main query
        $mockLogs = [
            [
                'log_id' => 1,
                'user_id' => 1,
                'timestamp' => '2025-05-01 10:00:00',
                'severity' => 'INFO',
                'module' => 'Authentication',
                'message' => 'User login successful',
                'ip_address' => '192.168.1.1'
            ],
            [
                'log_id' => 2,
                'user_id' => 2,
                'timestamp' => '2025-05-01 11:30:00',
                'severity' => 'WARNING',
                'module' => 'User Management',
                'message' => 'Password reset requested',
                'ip_address' => '192.168.1.2'
            ]
        ];
        
        // Mock count result
        $countResult = [['total' => 2]];
        
        // Mock user email result
        $userResult = [['email' => 'test@example.com']];
        
        // Create a mock method for the query function
        $this->systemLog = $this->getMockBuilder(SystemLog::class)
            ->setMethods(['query'])
            ->getMock();
            
        // Set expectations for the query method
        $this->systemLog->expects($this->exactly(4))
            ->method('query')
            ->will($this->returnCallback(function($sql, $params = []) use ($mockLogs, $countResult, $userResult) {
                if (strpos($sql, 'COUNT(*)') !== false) {
                    return $countResult;
                } else if (strpos($sql, 'SELECT email') !== false) {
                    return $userResult;
                } else {
                    return $mockLogs;
                }
            }));
        
        // Test getting filtered logs
        $result = $this->systemLog->getFilteredLogs(
            0,      // start
            10,     // length
            'INFO', // severity
            null,   // module
            '2025-05-01', // dateFrom
            '2025-05-02'  // dateTo
        );
        
        // Assert the results
        $this->assertEquals(2, $result['total']);
        $this->assertEquals(2, $result['filtered']);
        $this->assertCount(2, $result['data']);
        $this->assertEquals('test@example.com', $result['data'][0]['user_email']);
        $this->assertEquals('test@example.com', $result['data'][1]['user_email']);
    }
}