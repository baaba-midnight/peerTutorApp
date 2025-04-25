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
    $role = 'student';
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
                            <!-- Sample Contacts -->
                            <div class="contact-item active">
                                <div class="d-flex align-items-center">
                                    <img src="../../assets/images/avatar.png" alt="John Smith" class="contact-avatar">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">John Smith</h6>
                                        <p class="mb-0 text-muted small">Mathematics Tutor</p>
                                    </div>
                                    <span class="online-indicator online"></span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="d-flex align-items-center">
                                    <img src="../../assets/images/avatar.png" alt="Sarah Johnson" class="contact-avatar">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Sarah Johnson</h6>
                                        <p class="mb-0 text-muted small">Physics Tutor</p>
                                    </div>
                                    <span class="online-indicator offline"></span>
                                </div>
                            </div>
                            <!-- Add more contact items here -->
                        </div>

                        <!-- Chat Messages -->
                        <div class="chat-messages">
                            <!-- Message Header -->
                            <div class="message-header">
                                <div class="d-flex align-items-center">
                                    <img src="../../assets/images/avatar.png" alt="John Smith" class="contact-avatar">
                                    <div>
                                        <h5 class="mb-0">John Smith</h5>
                                        <small class="text-muted">Mathematics Tutor</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Message List -->
                            <div class="message-list">
                                <!-- Sample Messages -->
                                <div class="message-bubble message-received">
                                    <p class="mb-1">Hi! How can I help you with mathematics today?</p>
                                    <div class="message-time">10:00 AM</div>
                                </div>

                                <div class="message-bubble message-sent">
                                    <p class="mb-1">Hello! I'm having trouble with calculus derivatives.</p>
                                    <div class="message-time">10:02 AM</div>
                                </div>

                                <div class="message-bubble message-received">
                                    <p class="mb-1">No problem! Let's start with the basics. Can you tell me which specific concepts are giving you trouble?</p>
                                    <div class="message-time">10:03 AM</div>
                                </div>

                                <div class="message-bubble message-sent">
                                    <p class="mb-1">The chain rule is confusing me. Could you explain it with an example?</p>
                                    <div class="message-time">10:05 AM</div>
                                </div>
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

    <?php include('../../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle contact selection
        document.querySelectorAll('.contact-item').forEach(contact => {
            contact.addEventListener('click', () => {
                document.querySelectorAll('.contact-item').forEach(c => c.classList.remove('active'));
                contact.classList.add('active');
                // Add AJAX call to load conversation
            });
        });

        // Handle message form submission
        document.getElementById('messageForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const message = document.getElementById('messageInput').value;
            if (!message.trim()) return;

            // Add message to UI
            const messageList = document.querySelector('.message-list');
            const messageElement = document.createElement('div');
            messageElement.className = 'message-bubble message-sent';
            messageElement.innerHTML = `
                <p class="mb-1">${message}</p>
                <div class="message-time">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
            `;
            messageList.appendChild(messageElement);
            messageList.scrollTop = messageList.scrollHeight;

            // Clear input
            document.getElementById('messageInput').value = '';

            // Add AJAX call to send message
        });

        // Auto-scroll to bottom of message list
        const messageList = document.querySelector('.message-list');
        messageList.scrollTop = messageList.scrollHeight;
    </script>
</body>
</html>