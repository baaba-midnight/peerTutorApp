<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - PeerEd</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <style>
        .chat-container {
            height: calc(100vh - 200px);
            display: flex;
        }
        .contacts-list {
            width: 300px;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
        }
        .chat-messages {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .message-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            background: #fff;
        }
        .message-list {
            flex-grow: 1;
            padding: 1rem;
            overflow-y: auto;
            background: #f8f9fa;
        }
        .message-input {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background: #fff;
        }
        .contact-item {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .contact-item:hover {
            background-color: #f8f9fa;
        }
        .contact-item.active {
            background-color: #e9ecef;
        }
        .message-bubble {
            max-width: 75%;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
        }
        .message-sent {
            margin-left: auto;
            background-color: #000;
            color: #fff;
        }
        .message-received {
            margin-right: auto;
            background-color: #e9ecef;
        }
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .contact-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .online-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        .online {
            background-color: #28a745;
        }
        .offline {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once '../../config/database.php';
    require_once '../../models/Message.php';
    require_once '../../models/User.php';

    if (!isset($_SESSION['id'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    $currentUserId = $_SESSION['id'];
    $database = new Database();
    $conn = $database->connect();
    $messageModel = new Message($conn);
    $userModel = new User($conn);

    // Fetch all user IDs that the current user has a meeting with
    $meetingUserIds = [];
    $meetingQuery = "SELECT DISTINCT 
        CASE WHEN student_id = :uid THEN tutor_id ELSE student_id END AS other_user_id
    FROM Appointments
    WHERE (student_id = :uid OR tutor_id = :uid) AND status IN ('confirmed', 'completed')";
    $stmt = $conn->prepare($meetingQuery);
    $stmt->bindParam(':uid', $currentUserId);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $meetingUserIds[] = $row['other_user_id'];
    }

    // Fetch all conversations (contacts) but only if they are in meetingUserIds
    $conversations = $messageModel->getUserConversations($currentUserId);
    $contacts = [];
    $contactIds = [];
    foreach ($conversations as $conv) {
        $otherId = $conv['sender_id'] == $currentUserId ? $conv['recipient_id'] : $conv['sender_id'];
        if (in_array($otherId, $meetingUserIds) && !in_array($otherId, $contactIds)) {
            $contactIds[] = $otherId;
            $contacts[] = $userModel->getUserById($otherId);
        }
    }

    // Default: show messages with the first contact
    $activeContactId = isset($contacts[0]['user_id']) ? $contacts[0]['user_id'] : null;
    $messages = $activeContactId ? $messageModel->getConversation($currentUserId, $activeContactId) : [];
    ?>

    <?php 
    $role =  $_SESSION['role'];
    include('../../includes/header.php'); 
    ?>

    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class="chat-container">
                        <!-- Contacts List -->
                        <div class="contacts-list">
                            <div class="p-3 border-bottom">
                                <input type="text" class="form-control" placeholder="Search contacts...">
                            </div>
                            <?php foreach ($contacts as $i => $contact): ?>
                            <div class="contact-item<?php if ($contact['user_id'] == $activeContactId) echo ' active'; ?>" data-contact-id="<?php echo $contact['user_id']; ?>">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $contact['profile_picture_url'] ? '../../' . $contact['profile_picture_url'] : '../../assets/images/avatar.png'; ?>" alt="<?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?>" class="contact-avatar">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?></h6>
                                        <p class="mb-0 text-muted small"><?php echo ucfirst($contact['role']); ?></p>
                                    </div>
                                    <span class="online-indicator offline"></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Chat Messages -->
                        <div class="chat-messages">
                            <!-- Message Header -->
                            <div class="message-header">
                                <?php if ($activeContactId): $activeContact = $userModel->getUserById($activeContactId); ?>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $activeContact['profile_picture_url'] ? '../../' . $activeContact['profile_picture_url'] : '../../assets/images/avatar.png'; ?>" alt="<?php echo htmlspecialchars($activeContact['first_name'] . ' ' . $activeContact['last_name']); ?>" class="contact-avatar">
                                    <div>
                                        <h5 class="mb-0"><?php echo htmlspecialchars($activeContact['first_name'] . ' ' . $activeContact['last_name']); ?></h5>
                                        <small class="text-muted"><?php echo ucfirst($activeContact['role']); ?></small>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <!-- Message List -->
                            <div class="message-list">
                                <?php foreach ($messages as $msg): ?>
                                <div class="message-bubble <?php echo $msg['sender_id'] == $currentUserId ? 'message-sent' : 'message-received'; ?>">
                                    <p class="mb-1"><?php echo htmlspecialchars($msg['content']); ?></p>
                                    <div class="message-time"><?php echo date('g:i A', strtotime($msg['created_at'])); ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Message Input -->
                            <div class="message-input">
                                <form id="messageForm" class="d-flex gap-2">
                                    <input type="text" class="form-control" placeholder="Type your message..." id="messageInput">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle contact selection
        document.querySelectorAll('.contact-item').forEach(contact => {
            contact.addEventListener('click', function() {
                document.querySelectorAll('.contact-item').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                const contactId = this.getAttribute('data-contact-id');
                fetchMessages(contactId);
            });
        });

        function fetchMessages(contactId) {
            fetch(`../../api/messages.php?contact_id=${contactId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const messageList = document.querySelector('.message-list');
                        messageList.innerHTML = '';
                        data.messages.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = 'message-bubble ' + (msg.sender_id == <?php echo json_encode($currentUserId); ?> ? 'message-sent' : 'message-received');
                            div.innerHTML = `<p class='mb-1'>${msg.content}</p><div class='message-time'>${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>`;
                            messageList.appendChild(div);
                        });
                        messageList.scrollTop = messageList.scrollHeight;
                    }
                });
        }

        // Handle message form submission
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            if (!message) return;
            const activeContact = document.querySelector('.contact-item.active');
            if (!activeContact) return;
            const contactId = activeContact.getAttribute('data-contact-id');
            fetch('../../api/messages.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contact_id: contactId, content: message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    fetchMessages(contactId);
                    input.value = '';
                } else {
                    alert(data.message || 'Failed to send message.');
                }
            });
        });

        // Auto-scroll to bottom of message list
        const messageList = document.querySelector('.message-list');
        messageList.scrollTop = messageList.scrollHeight;
    </script>
</body>
</html>