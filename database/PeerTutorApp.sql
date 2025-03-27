-- Drop database if it exists
DROP DATABASE IF EXISTS PeerTutor;

-- Create a new database
CREATE DATABASE PeerTutor;

-- Use the newly created database
USE PeerTutor;

-- Users table
CREATE TABLE Users (
    user_id VARCHAR(36) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'tutor', 'admin') NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    verification_status BOOLEAN DEFAULT FALSE
);

-- Profiles table
CREATE TABLE Profiles (
    profile_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    bio TEXT,
    profile_picture_url VARCHAR(255),
    department VARCHAR(100),
    year_of_study INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- TutorProfiles table
CREATE TABLE TutorProfiles (
    tutor_profile_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    hourly_rate DECIMAL(10,2),
    availability_schedule JSON, -- Stores JSON object with available time slots
    overall_rating DECIMAL(3,2),
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Subjects table
CREATE TABLE Subjects (
    subject_id VARCHAR(36) PRIMARY KEY,
    subject_code VARCHAR(20) NOT NULL UNIQUE,
    subject_name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TutorSubjects table
CREATE TABLE TutorSubjects (
    tutor_subject_id VARCHAR(36) PRIMARY KEY,
    tutor_id VARCHAR(36) NOT NULL,
    subject_id VARCHAR(36) NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tutor_id) REFERENCES TutorProfiles(tutor_profile_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES Subjects(subject_id) ON DELETE CASCADE,
    UNIQUE KEY (tutor_id, subject_id)
);

-- Sessions table
CREATE TABLE Sessions (
    session_id VARCHAR(36) PRIMARY KEY,
    student_id VARCHAR(36) NOT NULL,
    tutor_id VARCHAR(36) NOT NULL,
    subject_id VARCHAR(36) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('requested', 'confirmed', 'cancelled', 'completed') NOT NULL,
    location VARCHAR(255), -- Can be physical location or virtual meeting link
    session_type ENUM('in-person', 'online') NOT NULL,
    session_notes TEXT,
    cancellation_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES TutorProfiles(tutor_profile_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES Subjects(subject_id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE Reviews (
    review_id VARCHAR(36) PRIMARY KEY,
    session_id VARCHAR(36) NOT NULL,
    student_id VARCHAR(36) NOT NULL,
    tutor_id VARCHAR(36) NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    is_anonymous BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES Sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES TutorProfiles(tutor_profile_id) ON DELETE CASCADE,
    UNIQUE KEY (session_id, student_id)
);

-- Messages table
CREATE TABLE Messages (
    message_id VARCHAR(36) PRIMARY KEY,
    sender_id VARCHAR(36) NOT NULL,
    recipient_id VARCHAR(36) NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Conversations table
CREATE TABLE Conversations (
    conversation_id VARCHAR(36) PRIMARY KEY,
    user1_id VARCHAR(36) NOT NULL,
    user2_id VARCHAR(36) NOT NULL,
    last_message_id VARCHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (last_message_id) REFERENCES Messages(message_id) ON DELETE SET NULL,
    UNIQUE KEY (user1_id, user2_id)
);

-- Notifications table
CREATE TABLE Notifications (
    notification_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    type ENUM('session_request', 'session_confirmed', 'session_cancelled', 'session_reminder', 'message', 'review', 'system') NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    related_id VARCHAR(36), -- Can reference sessions, messages, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- AcademicResources table
CREATE TABLE AcademicResources (
    resource_id VARCHAR(36) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    resource_type ENUM('faculty', 'library', 'online_resource', 'study_material') NOT NULL,
    description TEXT,
    contact_info VARCHAR(255),
    department VARCHAR(100),
    url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- LecturerContacts table
CREATE TABLE LecturerContacts (
    lecturer_id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    office_location VARCHAR(255),
    office_hours TEXT,
    phone_number VARCHAR(20),
    subjects_taught TEXT,
    profile_picture_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create indexes for optimized queries

-- Users table indexes
CREATE INDEX idx_users_email ON Users(email);
CREATE INDEX idx_users_role ON Users(role);

-- TutorProfiles table indexes
CREATE INDEX idx_tutors_rating ON TutorProfiles(overall_rating);
CREATE INDEX idx_tutors_verified ON TutorProfiles(is_verified);

-- Subjects table indexes
CREATE INDEX idx_subjects_dept ON Subjects(department);
CREATE INDEX idx_subjects_code ON Subjects(subject_code);

-- Sessions table indexes
CREATE INDEX idx_sessions_tutor ON Sessions(tutor_id);
CREATE INDEX idx_sessions_student ON Sessions(student_id);
CREATE INDEX idx_sessions_subject ON Sessions(subject_id);
CREATE INDEX idx_sessions_status ON Sessions(status);
CREATE INDEX idx_sessions_date ON Sessions(start_time);

-- Messages table indexes
CREATE INDEX idx_messages_sender ON Messages(sender_id);
CREATE INDEX idx_messages_recipient ON Messages(recipient_id);
CREATE INDEX idx_messages_read ON Messages(is_read);

-- Notifications table indexes
CREATE INDEX idx_notifications_user ON Notifications(user_id);
CREATE INDEX idx_notifications_read ON Notifications(is_read);
CREATE INDEX idx_notifications_type ON Notifications(type);

-- Create triggers

-- Trigger to update overall tutor rating when a new review is added
DELIMITER //
CREATE TRIGGER update_tutor_rating
AFTER INSERT ON Reviews
FOR EACH ROW
BEGIN
    UPDATE TutorProfiles
    SET overall_rating = (
        SELECT AVG(rating)
        FROM Reviews
        WHERE tutor_id = NEW.tutor_id
    )
    WHERE tutor_profile_id = NEW.tutor_id;
END //
DELIMITER ;

-- Trigger to mark related notifications as read when a message is read
DELIMITER //
CREATE TRIGGER mark_message_notifications
AFTER UPDATE ON Messages
FOR EACH ROW
BEGIN
    IF NEW.is_read = TRUE AND OLD.is_read = FALSE THEN
        UPDATE Notifications
        SET is_read = TRUE
        WHERE related_id = NEW.message_id AND type = 'message';
    END IF;
END //
DELIMITER ;
