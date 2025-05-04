<?php
require_once dirname(__DIR__) . '/BaseTestCase.php';
require_once dirname(dirname(__DIR__)) . '/models/Message.php';

/**
 * Unit tests for the Message model
 */
class MessageTest extends BaseTestCase
{
    /**
     * @var Message The Message model instance being tested
     */
    private $message;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->message = new Message($this->db);
    }

    /**
     * Test sending a message
     */
    public function testSendMessage()
    {
        // Create mock PDOStatements for message insertion and conversation check/update
        $stmtMessage = $this->createStatementMock([
            'execute' => true
        ]);
        
        $stmtConvCheck = $this->createStatementMock([
            'rowCount' => 1,
            'fetch' => ['conversation_id' => 1],
            'execute' => true
        ]);
        
        $stmtConvUpdate = $this->createStatementMock([
            'execute' => true
        ]);
        
        // Last insert ID for the message
        $this->db->method('lastInsertId')->willReturn(6);

        // Configure the mock database to return different statements based on the query
        $this->db->method('prepare')
            ->will($this->returnCallback(function($sql) use ($stmtMessage, $stmtConvCheck, $stmtConvUpdate) {
                if (strpos($sql, 'INSERT INTO Messages') !== false) {
                    return $stmtMessage;
                } elseif (strpos($sql, 'SELECT conversation_id') !== false) {
                    return $stmtConvCheck;
                } else {
                    return $stmtConvUpdate;
                }
            }));

        // Test sending a message
        $result = $this->message->sendMessage(1, 3, 'Hello, this is a test message.');

        // Assert the message was sent successfully
        $this->assertTrue($result);
    }

    /**
     * Test getting a conversation between two users
     */
    public function testGetConversation()
    {
        // Mock conversation data
        $mockConversation = [
            [
                'message_id' => 1,
                'sender_id' => 1,
                'recipient_id' => 3,
                'content' => 'Can we meet tomorrow?',
                'created_at' => '2025-05-02 19:43:06',
                'is_read' => 0
            ],
            [
                'message_id' => 2,
                'sender_id' => 3,
                'recipient_id' => 1,
                'content' => 'Sure, 10am works.',
                'created_at' => '2025-05-02 19:43:06',
                'is_read' => 1
            ],
            [
                'message_id' => 5,
                'sender_id' => 1,
                'recipient_id' => 3,
                'content' => 'Thanks!',
                'created_at' => '2025-05-02 19:43:06',
                'is_read' => 1
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockConversation,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting the conversation between users 1 and 3
        $conversation = $this->message->getConversation(1, 3);

        // Assert conversation data was retrieved correctly
        $this->assertCount(3, $conversation);
        $this->assertEquals('Can we meet tomorrow?', $conversation[0]['content']);
        $this->assertEquals(3, $conversation[1]['sender_id']);
        $this->assertEquals('Thanks!', $conversation[2]['content']);
    }

    /**
     * Test getting all conversations for a user
     */
    public function testGetUserConversations()
    {
        // Mock conversations data based on the conversations table structure
        $mockConversations = [
            [
                'conversation_id' => 1,
                'user1_id' => 1,
                'user2_id' => 3,
                'last_message_id' => 6,
                'message_content' => "You're welcome.",
                'sender_id' => 3,
                'created_at' => '2025-05-02 19:43:06',
                'other_user_first_name' => 'Bob',
                'other_user_last_name' => 'Smith',
                'other_user_profile_pic' => 'uploads/avatars/7_1745937146.jpg',
                'unread_count' => 0
            ],
            [
                'conversation_id' => 3,
                'user1_id' => 1,
                'user2_id' => 4,
                'last_message_id' => null,
                'message_content' => null,
                'sender_id' => null,
                'created_at' => '2025-05-02 19:43:06',
                'other_user_first_name' => 'Grace',
                'other_user_last_name' => 'Lee',
                'other_user_profile_pic' => 'uploads/avatars/7_1745937146.jpg',
                'unread_count' => 0
            ]
        ];

        // Create a mock PDOStatement with expected behavior
        $stmt = $this->createStatementMock([
            'fetchAll' => $mockConversations,
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test getting all conversations for user 1
        $conversations = $this->message->getUserConversations(1);

        // Assert conversations data was retrieved correctly
        $this->assertCount(2, $conversations);
        $this->assertEquals(1, $conversations[0]['conversation_id']);
        $this->assertEquals('Bob', $conversations[0]['other_user_first_name']);
        $this->assertEquals(4, $conversations[1]['user2_id']);
        $this->assertNull($conversations[1]['last_message_id']);
    }

    /**
     * Test marking messages as read
     */
    public function testMarkAsRead()
    {
        // Create a mock PDOStatement that indicates successful execution
        $stmt = $this->createStatementMock([
            'execute' => true
        ]);

        // Configure the mock database to return our statement mock
        $this->db->method('prepare')->willReturn($stmt);

        // Test marking messages as read
        $result = $this->message->markAsRead(3, 1);

        // Assert the messages were marked as read successfully
        $this->assertTrue($result);
    }
    
}