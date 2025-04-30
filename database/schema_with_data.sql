-- =========================================
-- PeerTutorApp Complete Schema with Sample Data
-- =========================================

-- Disable foreign key checks for initial setup
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS Notifications;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Appointments;
DROP TABLE IF EXISTS UserSubjects;
DROP TABLE IF EXISTS Availability;
DROP TABLE IF EXISTS TutorSubjects;
DROP TABLE IF EXISTS Subjects;
DROP TABLE IF EXISTS Users;

-- Create Users table
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('student', 'tutor', 'admin') NOT NULL,
    profile_image VARCHAR(255),
    phone VARCHAR(20),
    location VARCHAR(100),
    bio TEXT,
    reset_token VARCHAR(64) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Subjects table
CREATE TABLE Subjects (
    subject_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    parent_subject_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_subject_id) REFERENCES Subjects(subject_id)
);

-- Create TutorSubjects table (junction table for tutors and their subjects)
CREATE TABLE TutorSubjects (
    tutor_id INT,
    subject_id INT,
    hourly_rate DECIMAL(10,2) NOT NULL,
    experience_years DECIMAL(3,1),
    PRIMARY KEY (tutor_id, subject_id),
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id),
    FOREIGN KEY (subject_id) REFERENCES Subjects(subject_id)
);

-- Create Availability table for tutors
CREATE TABLE Availability (
    availability_id INT PRIMARY KEY AUTO_INCREMENT,
    tutor_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id)
);

-- Create Appointments table
CREATE TABLE Appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    tutor_id INT,
    subject_id INT,
    start_datetime DATETIME,
    end_datetime DATETIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    meeting_link VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(user_id),
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id),
    FOREIGN KEY (subject_id) REFERENCES Subjects(subject_id)
);

-- Create Reviews table
CREATE TABLE Reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT,
    reviewer_id INT,
    reviewee_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    content TEXT,
    is_anonymous BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id),
    FOREIGN KEY (reviewer_id) REFERENCES Users(user_id),
    FOREIGN KEY (reviewee_id) REFERENCES Users(user_id)
);

-- Create Messages table
CREATE TABLE Messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    receiver_id INT,
    content TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id)
);

-- Create Notifications table
CREATE TABLE Notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('appointment', 'message', 'review', 'system') NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Create UserSubjects table (for storing user subject preferences)
CREATE TABLE UserSubjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Create indexes for better performance
CREATE INDEX idx_appointments_student ON Appointments(student_id);
CREATE INDEX idx_appointments_tutor ON Appointments(tutor_id);
CREATE INDEX idx_appointments_datetime ON Appointments(start_datetime);
CREATE INDEX idx_messages_sender ON Messages(sender_id);
CREATE INDEX idx_messages_receiver ON Messages(receiver_id);
CREATE INDEX idx_notifications_user ON Notifications(user_id);
CREATE INDEX idx_reviews_reviewee ON Reviews(reviewee_id);

-- =========================================
-- SAMPLE DATA POPULATION
-- =========================================

-- -----------------------------------------------------
-- Users (Passwords are all 'password123' - hashed with bcrypt)
-- -----------------------------------------------------
INSERT INTO Users (username, email, password_hash, first_name, last_name, role, profile_image, phone, location, bio) VALUES
-- Admins
('admin1', 'admin@peered.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', NULL, '555-123-4567', 'New York, NY', 'System administrator for PeerEd platform'),

-- Students
('jsmith', 'john.smith@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Smith', 'student', 'assets/images/man-1.jpg', '555-111-2222', 'Boston, MA', 'Computer Science sophomore looking for help with advanced algorithms and data structures.'),
('ajohnson', 'amy.johnson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amy', 'Johnson', 'student', 'assets/images/woman-1.jpg', '555-222-3333', 'Chicago, IL', 'Pre-med student struggling with organic chemistry. Looking for patient tutors who can explain complex concepts clearly.'),
('mlee', 'michael.lee@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Michael', 'Lee', 'student', 'assets/images/man-2.jpg', '555-333-4444', 'San Francisco, CA', 'Business major interested in finance and economics. Seeking help with statistical models and financial forecasting.'),
('sgonzalez', 'sophia.gonzalez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sophia', 'Gonzalez', 'student', 'assets/images/woman-2.jpg', '555-444-5555', 'Miami, FL', 'English literature major who needs help with essay writing and literary analysis.'),
('dkim', 'david.kim@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'David', 'Kim', 'student', NULL, '555-555-6666', 'Seattle, WA', 'Physics major looking for help with quantum mechanics and electromagnetism.'),

-- Tutors
('ppatel', 'priya.patel@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Priya', 'Patel', 'tutor', 'assets/images/woman-3.jpg', '555-666-7777', 'Austin, TX', 'PhD candidate in Computer Science with 5+ years of teaching experience. I specialize in algorithms, data structures, and machine learning. My teaching philosophy is to build a strong foundation of concepts and then apply them to real-world problems.'),
('rjackson', 'robert.jackson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Robert', 'Jackson', 'tutor', NULL, '555-777-8888', 'Denver, CO', 'Chemistry professor with 10+ years of experience teaching organic and inorganic chemistry. I believe in making complex chemical concepts accessible through visual aids and practical examples.'),
('owilliams', 'olivia.williams@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Olivia', 'Williams', 'tutor', NULL, '555-888-9999', 'Portland, OR', 'Finance professional with MBA and 8 years of industry experience. I make economics and finance concepts practical and relatable with real-world case studies.'),
('tbrown', 'thomas.brown@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Thomas', 'Brown', 'tutor', NULL, '555-999-0000', 'Atlanta, GA', 'Published author and English professor. I can help with all aspects of writing, from grammar basics to advanced literary analysis.'),
('lnguyen', 'liam.nguyen@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Liam', 'Nguyen', 'tutor', NULL, '555-000-1111', 'Philadelphia, PA', 'Theoretical physicist with research focus on quantum computing. I can explain complex physics concepts with simple analogies and step-by-step problem solving.'),
('jrodriguez', 'jasmine.rodriguez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jasmine', 'Rodriguez', 'tutor', NULL, '555-111-3333', 'San Diego, CA', 'Mathematics professor specializing in calculus and differential equations. My approach involves breaking down complex problems into manageable steps.'),
('asingh', 'amir.singh@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amir', 'Singh', 'tutor', NULL, '555-333-5555', 'Houston, TX', 'Software engineer with 15 years of industry experience. I teach programming with a focus on practical applications and industry best practices.');

-- -----------------------------------------------------
-- Subjects and parent subjects
-- -----------------------------------------------------
INSERT INTO Subjects (subject_id, name, description, parent_subject_id) VALUES
-- Main subject categories
(1, 'Mathematics', 'Mathematical sciences, theories, and applications', NULL),
(2, 'Computer Science', 'Study of computation, programming, and algorithms', NULL),
(3, 'Science', 'Natural and physical sciences', NULL),
(4, 'Language Arts', 'Reading, writing, and communications', NULL),
(5, 'Business', 'Business, economics, and finance', NULL),

-- Math subjects
(10, 'Algebra', 'Study of mathematical symbols and the rules for manipulating these symbols', 1),
(11, 'Calculus', 'Study of continuous change and its applications', 1),
(12, 'Statistics', 'Study of collection, analysis, interpretation, and presentation of data', 1),
(13, 'Linear Algebra', 'Study of linear equations and their representations in vector spaces', 1),
(14, 'Discrete Mathematics', 'Study of mathematical structures that are fundamentally discrete', 1),
(15, 'Differential Equations', 'Study of relationships between functions and their derivatives', 1),

-- Computer Science subjects
(20, 'Programming', 'Writing computer programs using programming languages', 2),
(21, 'Data Structures', 'Organization of data for efficient usage', 2),
(22, 'Algorithms', 'Step-by-step procedures for solving problems', 2),
(23, 'Database Systems', 'Storage, retrieval, and manipulation of data', 2),
(24, 'Web Development', 'Building and maintaining websites', 2),
(25, 'Machine Learning', 'Study of algorithms that improve through experience', 2),
(26, 'Operating Systems', 'Software that manages computer hardware and software resources', 2),

-- Science subjects
(30, 'Physics', 'Study of matter, energy, and their interactions', 3),
(31, 'Chemistry', 'Study of substances and their interactions', 3),
(32, 'Biology', 'Study of life and living organisms', 3),
(33, 'Earth Science', 'Study of the earth and its phenomena', 3),
(34, 'Astronomy', 'Study of celestial objects and phenomena', 3),
(35, 'Organic Chemistry', 'Study of carbon-containing compounds', 31),
(36, 'Quantum Physics', 'Study of atomic and subatomic systems', 30);

INSERT INTO Subjects (subject_id, name, description, parent_subject_id) VALUES
-- Language Arts subjects
(40, 'Essay Writing', 'Organizing and expressing ideas in essay format', 4),
(41, 'Grammar', 'Rules for speaking and writing a language', 4),
(42, 'Literature Analysis', 'Critical examination of literary works', 4),
(43, 'Creative Writing', 'Creating original written works', 4),
(44, 'Technical Writing', 'Writing technical documents and instructions', 4),
(45, 'Public Speaking', 'Oral communication before an audience', 4),
(46, 'Academic Writing', 'Writing for academic purposes and publications', 4),

-- Business subjects
(50, 'Economics', 'Study of production, distribution, and consumption of goods and services', 5),
(51, 'Finance', 'Management of money and other assets', 5),
(52, 'Accounting', 'Measuring, processing, and communicating financial information', 5),
(53, 'Marketing', 'Promoting and selling products or services', 5),
(54, 'Business Management', 'Overseeing business operations', 5),
(55, 'Microeconomics', 'Study of individuals and businesses making decisions regarding allocation of resources', 50),
(56, 'Macroeconomics', 'Study of economy-wide phenomena', 50),
(57, 'Financial Accounting', 'Recording, summarizing, and reporting financial transactions', 52);

-- -----------------------------------------------------
-- TutorSubjects (tutors with their subjects and rates)
-- -----------------------------------------------------
INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Priya Patel (CS Tutor)
(6, 2, 50.00, 5.0),   -- Computer Science (general)
(6, 20, 55.00, 7.0),  -- Programming
(6, 21, 60.00, 6.0),  -- Data Structures
(6, 22, 60.00, 5.0),  -- Algorithms
(6, 25, 75.00, 4.0);  -- Machine Learning

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Robert Jackson (Chemistry Tutor)
(7, 3, 45.00, 10.0),   -- Science (general)
(7, 31, 50.00, 12.0),  -- Chemistry
(7, 35, 60.00, 10.0);  -- Organic Chemistry

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Olivia Williams (Finance Tutor)
(8, 5, 65.00, 8.0),    -- Business (general)
(8, 50, 70.00, 6.0),   -- Economics
(8, 51, 75.00, 8.0),   -- Finance
(8, 55, 80.00, 5.0),   -- Microeconomics
(8, 56, 80.00, 5.0),   -- Macroeconomics
(8, 12, 65.00, 7.0);   -- Statistics (cross-discipline)

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Thomas Brown (English Tutor)
(9, 4, 40.00, 15.0),   -- Language Arts (general)
(9, 40, 45.00, 12.0),  -- Essay Writing
(9, 42, 50.00, 15.0),  -- Literature Analysis
(9, 43, 55.00, 10.0),  -- Creative Writing
(9, 46, 60.00, 12.0);  -- Academic Writing

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Liam Nguyen (Physics Tutor)
(10, 3, 60.00, 7.0),    -- Science (general)
(10, 30, 65.00, 7.0),   -- Physics
(10, 36, 80.00, 6.0),   -- Quantum Physics
(10, 11, 60.00, 8.0),   -- Calculus (cross-discipline)
(10, 15, 70.00, 6.0);   -- Differential Equations (cross-discipline)

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Jasmine Rodriguez (Math Tutor)
(11, 1, 45.00, 10.0),   -- Mathematics (general)
(11, 10, 50.00, 12.0),  -- Algebra
(11, 11, 55.00, 10.0),  -- Calculus
(11, 13, 60.00, 8.0),   -- Linear Algebra
(11, 15, 65.00, 9.0);   -- Differential Equations

INSERT INTO TutorSubjects (tutor_id, subject_id, hourly_rate, experience_years) VALUES
-- Amir Singh (Programming Tutor)
(12, 2, 70.00, 15.0),   -- Computer Science (general)
(12, 20, 75.00, 15.0),  -- Programming
(12, 23, 80.00, 12.0),  -- Database Systems
(12, 24, 85.00, 10.0);  -- Web Development

-- -----------------------------------------------------
-- Tutor Availability
-- -----------------------------------------------------
INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Priya Patel (ID: 6)
(6, 'Monday', '09:00:00', '12:00:00'),
(6, 'Monday', '13:00:00', '17:00:00'),
(6, 'Wednesday', '09:00:00', '12:00:00'),
(6, 'Wednesday', '13:00:00', '17:00:00'),
(6, 'Friday', '13:00:00', '18:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Robert Jackson (ID: 7)
(7, 'Tuesday', '10:00:00', '15:00:00'),
(7, 'Thursday', '10:00:00', '15:00:00'),
(7, 'Saturday', '09:00:00', '13:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Olivia Williams (ID: 8)
(8, 'Monday', '16:00:00', '20:00:00'),
(8, 'Wednesday', '16:00:00', '20:00:00'),
(8, 'Friday', '10:00:00', '14:00:00'),
(8, 'Saturday', '14:00:00', '18:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Thomas Brown (ID: 9)
(9, 'Tuesday', '09:00:00', '13:00:00'),
(9, 'Thursday', '09:00:00', '13:00:00'),
(9, 'Friday', '15:00:00', '19:00:00'),
(9, 'Sunday', '13:00:00', '17:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Liam Nguyen (ID: 10)
(10, 'Monday', '14:00:00', '19:00:00'),
(10, 'Wednesday', '14:00:00', '19:00:00'),
(10, 'Thursday', '16:00:00', '20:00:00'),
(10, 'Saturday', '10:00:00', '15:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Jasmine Rodriguez (ID: 11)
(11, 'Tuesday', '14:00:00', '18:00:00'),
(11, 'Wednesday', '09:00:00', '13:00:00'),
(11, 'Friday', '09:00:00', '13:00:00'),
(11, 'Sunday', '10:00:00', '15:00:00');

INSERT INTO Availability (tutor_id, day_of_week, start_time, end_time) VALUES
-- Amir Singh (ID: 12)
(12, 'Monday', '18:00:00', '21:00:00'),
(12, 'Tuesday', '18:00:00', '21:00:00'),
(12, 'Thursday', '18:00:00', '21:00:00'),
(12, 'Saturday', '16:00:00', '20:00:00');

-- -----------------------------------------------------
-- Student Subject preferences (UserSubjects)
-- -----------------------------------------------------
INSERT INTO UserSubjects (user_id, subject) VALUES
-- John Smith (CS Student)
(2, 'Programming'),
(2, 'Algorithms'),
(2, 'Data Structures');

INSERT INTO UserSubjects (user_id, subject) VALUES
-- Amy Johnson (Pre-med Student)
(3, 'Biology'),
(3, 'Chemistry'),
(3, 'Organic Chemistry');

INSERT INTO UserSubjects (user_id, subject) VALUES
-- Michael Lee (Business Student)
(4, 'Finance'),
(4, 'Economics'),
(4, 'Statistics');

INSERT INTO UserSubjects (user_id, subject) VALUES
-- Sophia Gonzalez (English Literature Student)
(5, 'Literature Analysis'),
(5, 'Essay Writing'),
(5, 'Academic Writing');

INSERT INTO UserSubjects (user_id, subject) VALUES
-- David Kim (Physics Student)
(6, 'Physics'),
(6, 'Quantum Physics'),
(6, 'Calculus'),
(6, 'Differential Equations');

-- -----------------------------------------------------
-- Appointments (Past, Present, and Future)
-- -----------------------------------------------------
INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status, meeting_link, notes) VALUES
-- Past completed appointments
(2, 6, 22, '2025-04-10 14:00:00', '2025-04-10 15:00:00', 'completed', 'https://zoom.us/j/meeting1', 'Need help with sorting algorithms analysis.'),
(3, 7, 35, '2025-04-12 11:00:00', '2025-04-12 12:30:00', 'completed', 'https://zoom.us/j/meeting2', 'Having trouble with organic chemistry nomenclature.'),
(4, 8, 51, '2025-04-15 17:00:00', '2025-04-15 18:00:00', 'completed', 'https://zoom.us/j/meeting3', 'Need help understanding derivatives and options trading.'),
(5, 9, 42, '2025-04-18 10:00:00', '2025-04-18 11:00:00', 'completed', 'https://zoom.us/j/meeting4', 'Analysis help for Shakespeare\'s Hamlet.'),
(2, 12, 20, '2025-04-20 19:00:00', '2025-04-20 20:00:00', 'completed', 'https://zoom.us/j/meeting5', 'Need help with JavaScript promises and async functions.');

INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status, meeting_link, notes) VALUES
-- Past cancelled appointments
(3, 10, 30, '2025-04-22 15:00:00', '2025-04-22 16:00:00', 'cancelled', NULL, 'Questions about wave-particle duality.'),
(4, 11, 12, '2025-04-23 11:00:00', '2025-04-23 12:00:00', 'cancelled', NULL, 'Need help with regression analysis for finance project.');

INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status, meeting_link, notes) VALUES
-- Current/upcoming confirmed appointments (relative to April 30, 2025)
(5, 9, 40, '2025-04-30 16:00:00', '2025-04-30 17:00:00', 'confirmed', 'https://zoom.us/j/meeting8', 'Help structuring my comparative literature essay.'),
(2, 6, 21, '2025-05-02 10:00:00', '2025-05-02 11:30:00', 'confirmed', 'https://zoom.us/j/meeting9', 'Need help implementing a complex linked list structure.'),
(3, 7, 31, '2025-05-03 12:00:00', '2025-05-03 13:00:00', 'confirmed', 'https://zoom.us/j/meeting10', 'Preparing for chemistry midterm on molecular bonding.');

INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status, meeting_link, notes) VALUES
-- Pending appointments (future)
(4, 8, 56, '2025-05-05 17:00:00', '2025-05-05 18:00:00', 'pending', NULL, 'Macroeconomic policy analysis questions.'),
(5, 9, 43, '2025-05-06 11:00:00', '2025-05-06 12:00:00', 'pending', NULL, 'Need feedback on my short story draft.'),
(2, 12, 24, '2025-05-07 19:00:00', '2025-05-07 20:30:00', 'pending', NULL, 'Help setting up a React/Node.js project.'),
(3, 10, 36, '2025-05-08 15:00:00', '2025-05-08 16:00:00', 'pending', NULL, 'Questions about Schrödinger equation applications.');

-- -----------------------------------------------------
-- Reviews (for completed appointments)
-- -----------------------------------------------------
INSERT INTO Reviews (appointment_id, reviewer_id, reviewee_id, rating, title, content, is_anonymous) VALUES
-- Student reviews of tutors
(1, 2, 6, 5, 'Excellent Algorithms Tutor', 'Priya explained the time complexity analysis so clearly. I finally understand Big O notation!', FALSE),
(2, 3, 7, 4, 'Very Helpful Chemistry Session', 'Robert is patient and knowledgeable. He broke down organic chemistry naming conventions in a way that finally clicked for me.', FALSE),
(3, 4, 8, 5, 'Amazing Finance Tutor', 'Olivia knows her stuff! She used real-world examples to explain complex financial concepts.', FALSE),
(4, 5, 9, 4, 'Insightful Literary Analysis', 'Thomas provided great insights into Shakespearean themes and helped me structure my analysis paper.', FALSE),
(5, 2, 12, 5, 'Excellent JavaScript Help', 'Amir is a JavaScript wizard. He helped me understand async programming in just one session!', FALSE);

INSERT INTO Reviews (appointment_id, reviewer_id, reviewee_id, rating, title, content, is_anonymous) VALUES
-- Tutor reviews of students
(1, 6, 2, 5, 'Engaged and Prepared Student', 'John came prepared with specific questions and was actively engaged throughout our session.', FALSE),
(2, 7, 3, 4, 'Dedicated Student', 'Amy is committed to improving her understanding of organic chemistry. A pleasure to teach.', FALSE),
(3, 8, 4, 5, 'Quick Learner', 'Michael grasps financial concepts rapidly and asks thoughtful questions.', FALSE),
(4, 9, 5, 5, 'Excellent Literary Mind', 'Sophia has strong analytical skills and a genuine passion for literature.', FALSE);

-- -----------------------------------------------------
-- Messages between users
-- -----------------------------------------------------
INSERT INTO Messages (sender_id, receiver_id, content, is_read) VALUES
-- Conversation between John (student) and Priya (tutor)
(2, 6, 'Hello Priya, I\'m interested in scheduling a session to discuss advanced data structures. Are you available this week?', TRUE),
(6, 2, 'Hi John, I\'d be happy to help. I have availability on Wednesday afternoon. What specific data structures are you working with?', TRUE),
(2, 6, 'I\'m implementing a red-black tree for a project and having some issues with the balancing algorithm.', TRUE),
(6, 2, 'That\'s a complex topic! Let\'s schedule for Wednesday at 2 PM. Could you send me your code before then so I can review it?', TRUE),
(2, 6, 'Great, I\'ll book that time and send you my code tonight. Thanks!', FALSE);

INSERT INTO Messages (sender_id, receiver_id, content, is_read) VALUES
-- Conversation between Amy (student) and Robert (tutor)
(3, 7, 'Dr. Jackson, I\'m struggling with stereochemistry concepts. Do you have any availability for tutoring?', TRUE),
(7, 3, 'Hello Amy, I can help with stereochemistry. I have slots open on Thursday between 10 AM and 2 PM.', TRUE),
(3, 7, 'Thursday at 11 AM would work perfectly. Thank you!', TRUE),
(7, 3, 'Great! I\'ll see you then. Please bring your specific questions and any course materials you\'re working with.', FALSE);

INSERT INTO Messages (sender_id, receiver_id, content, is_read) VALUES
-- Conversation between Michael (student) and Olivia (tutor)
(4, 8, 'Hi Professor Williams, I need help understanding option pricing models for my finance project.', TRUE),
(8, 4, 'Hello Michael, I specialize in those models. What specific aspects are you struggling with?', TRUE),
(4, 8, 'I\'m having trouble with the Black-Scholes equation and its assumptions.', TRUE),
(8, 4, 'That\'s a common challenge. Let\'s schedule a session where I can walk you through it step by step.', TRUE),
(4, 8, 'That would be great. Are you free Monday evening?', TRUE),
(8, 4, 'Yes, I can do Monday at 6 PM. I\'ll prepare some examples that make the concepts clearer.', FALSE);

-- -----------------------------------------------------
-- Notifications
-- -----------------------------------------------------
INSERT INTO Notifications (user_id, type, title, content, is_read) VALUES
-- Student notifications
(2, 'appointment', 'Session Confirmed', 'Your tutoring session with Priya Patel on May 2nd at 10:00 AM has been confirmed.', FALSE),
(2, 'message', 'New Message', 'You have a new message from Priya Patel.', TRUE),
(3, 'appointment', 'Session Reminder', 'Your tutoring session with Robert Jackson is scheduled to start in 1 hour.', FALSE),
(4, 'review', 'New Review', 'Olivia Williams has left you a review for your recent session.', FALSE),
(5, 'system', 'Payment Processed', 'Your payment for the session with Thomas Brown has been processed successfully.', TRUE);

INSERT INTO Notifications (user_id, type, title, content, is_read) VALUES
-- Tutor notifications
(6, 'appointment', 'New Booking Request', 'John Smith has requested a tutoring session on May 2nd at 10:00 AM.', TRUE),
(7, 'message', 'New Message', 'You have a new message from Amy Johnson.', FALSE),
(8, 'appointment', 'Session Cancelled', 'Michael Lee has cancelled the session scheduled for April 23rd.', TRUE),
(9, 'review', 'New Review', 'Sophia Gonzalez has left a review for your tutoring session.', FALSE),
(10, 'system', 'Payment Sent', 'Payment for your recent tutoring session has been processed.', TRUE);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;