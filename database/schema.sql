<<<<<<< HEAD
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
=======
-- Drop database if it exists
DROP DATABASE IF EXISTS PeerTutor;

-- Create a new database
CREATE DATABASE PeerTutor;

-- Use the newly created database
USE PeerTutor;

-- Users table
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'tutor', 'admin') NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    verification_status BOOLEAN DEFAULT FALSE
);

-- Profiles table
CREATE TABLE Profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
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
    tutor_profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hourly_rate DECIMAL(10,2),
    availability_schedule JSON,
    overall_rating DECIMAL(3,2),
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Courses table
CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    description TEXT,
>>>>>>> 95ea66a859590aff608529076fdee37c9c56d356
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

<<<<<<< HEAD
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
=======
-- TutorCourses table
CREATE TABLE TutorCourses (
    tutor_course_id INT AUTO_INCREMENT PRIMARY KEY,
    tutor_id INT NOT NULL,
    course_id INT NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tutor_id) REFERENCES TutorProfiles(tutor_profile_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE,
    UNIQUE KEY (tutor_id, course_id)
);

-- Sessions table
CREATE TABLE Sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    tutor_id INT NOT NULL,
    course_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('requested', 'confirmed', 'cancelled', 'completed') NOT NULL,
    location VARCHAR(255),
    session_type ENUM('in-person', 'online') NOT NULL,
    session_notes TEXT,
    cancellation_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tutor_id) REFERENCES TutorProfiles(tutor_profile_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE
);

-- Availability table
CREATE TABLE Availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 95ea66a859590aff608529076fdee37c9c56d356
    tutor_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id)
);

<<<<<<< HEAD
-- Create Appointments table
CREATE TABLE Appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
=======
-- Appointments table
CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 95ea66a859590aff608529076fdee37c9c56d356
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
<<<<<<< HEAD
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

-- Create indexes for better performance
CREATE INDEX idx_appointments_student ON Appointments(student_id);
CREATE INDEX idx_appointments_tutor ON Appointments(tutor_id);
CREATE INDEX idx_appointments_datetime ON Appointments(start_datetime);
CREATE INDEX idx_messages_sender ON Messages(sender_id);
CREATE INDEX idx_messages_receiver ON Messages(receiver_id);
CREATE INDEX idx_notifications_user ON Notifications(user_id);
CREATE INDEX idx_reviews_reviewee ON Reviews(reviewee_id);
=======
    FOREIGN KEY (subject_id) REFERENCES Courses(course_id)
);

-- Reviews table
CREATE TABLE Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    tutor_id INT NOT NULL,
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
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Conversations table
CREATE TABLE Conversations (
    conversation_id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    last_message_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (last_message_id) REFERENCES Messages(message_id) ON DELETE SET NULL,
    UNIQUE KEY (user1_id, user2_id)
);

-- Notifications table
CREATE TABLE Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('session_request', 'session_confirmed', 'session_cancelled', 'session_reminder', 'message', 'review', 'system') NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    related_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- System Logs table
CREATE TABLE system_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    severity ENUM('critical', 'error', 'warning', 'info', 'debug') NOT NULL,
    module VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    user_id INT,
    ip_address VARCHAR(45),
    request_id VARCHAR(50),
    stack_trace TEXT,
    additional_data JSON,
    INDEX idx_timestamp (timestamp),
    INDEX idx_severity (severity),
    INDEX idx_module (module),
    INDEX idx_user (user_id)
);

-- Backup Records table
CREATE TABLE backup_records (
    backup_id INT AUTO_INCREMENT PRIMARY KEY,
    backup_name VARCHAR(255) NOT NULL,
    backup_type ENUM('full', 'database', 'incremental') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(512) NOT NULL,
    file_size BIGINT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'failed') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME,
    created_by INT NOT NULL,
    notes TEXT,
    checksum VARCHAR(64),
    INDEX idx_type (backup_type),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Backup Logs table
CREATE TABLE backup_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    backup_id INT,
    operation ENUM('create', 'restore', 'delete', 'download', 'verify') NOT NULL,
    status ENUM('started', 'completed', 'failed') NOT NULL,
    timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    details TEXT,
    FOREIGN KEY (backup_id) REFERENCES backup_records(backup_id) ON DELETE SET NULL,
    INDEX idx_operation (operation),
    INDEX idx_status (status),
    INDEX idx_timestamp (timestamp)
);

-- Create indexes for optimized queries

-- Users table indexes
CREATE INDEX idx_users_email ON Users(email);
CREATE INDEX idx_users_role ON Users(role);

-- TutorProfiles table indexes
CREATE INDEX idx_tutors_rating ON TutorProfiles(overall_rating);
CREATE INDEX idx_tutors_verified ON TutorProfiles(is_verified);

-- Courses table indexes
CREATE INDEX idx_Courses_dept ON Courses(department);
CREATE INDEX idx_Courses_code ON Courses(course_code);

-- Sessions table indexes
CREATE INDEX idx_sessions_tutor ON Sessions(tutor_id);
CREATE INDEX idx_sessions_student ON Sessions(student_id);
CREATE INDEX idx_sessions_course ON Sessions(course_id);
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
>>>>>>> 95ea66a859590aff608529076fdee37c9c56d356
