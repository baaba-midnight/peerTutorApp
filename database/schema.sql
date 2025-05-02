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
    last_login TIMESTAMP,
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TutorCourses table
CREATE TABLE TutorCourses (
    tutor_course_id INT AUTO_INCREMENT PRIMARY KEY,
    tutor_id INT NOT NULL,
    course_id INT NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id) ON DELETE CASCADE,
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
    tutor_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (tutor_id) REFERENCES Users(user_id)
);

-- Appointments table
CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
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

-- AcademicResources table
CREATE TABLE AcademicResources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
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
    lecturer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    office_location VARCHAR(255),
    office_hours TEXT,
    phone_number VARCHAR(20),
    Courses_taught TEXT,
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
