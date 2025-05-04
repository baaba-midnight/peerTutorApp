<?php
use PHPUnit\Framework\TestCase;

/**
 * Base TestCase class for all model tests
 * 
 * This class provides common functionality for testing models
 */
class BaseTestCase extends TestCase
{
    /**
     * @var PDO $db Test database connection
     */
    protected $db;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        // Create a mock database connection for testing
        $this->db = $this->createMock(PDO::class);
    }

    /**
     * Create a PDOStatement mock with predefined behaviors
     * 
     * @param array $methods Methods to configure 
     * @return PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStatementMock(array $methods = [])
    {
        $stmt = $this->createMock(PDOStatement::class);
        
        foreach ($methods as $method => $returnValue) {
            $stmt->method($method)->willReturn($returnValue);
        }
        
        return $stmt;
    }
}