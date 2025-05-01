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

-- Create indexes for better performance
CREATE INDEX idx_appointments_student ON Appointments(student_id);
CREATE INDEX idx_appointments_tutor ON Appointments(tutor_id);
CREATE INDEX idx_appointments_datetime ON Appointments(start_datetime);
CREATE INDEX idx_messages_sender ON Messages(sender_id);
CREATE INDEX idx_messages_receiver ON Messages(receiver_id);
CREATE INDEX idx_notifications_user ON Notifications(user_id);
CREATE INDEX idx_reviews_reviewee ON Reviews(reviewee_id);



-- Add upcoming appointments with tutor Kelvin (user_id 29)
INSERT INTO Appointments (student_id, tutor_id, subject_id, start_datetime, end_datetime, status, meeting_link, notes) 
VALUES 
(2, 29, 1, '2025-05-03 10:00:00', '2025-05-03 11:00:00', 'confirmed', 'https://meet.link/session1', 'Reviewing algebra'),
(3, 29, 1, '2025-05-04 15:00:00', '2025-05-04 16:00:00', 'pending', 'https://meet.link/session2', 'Geometry basics');


UPDATE Appointments SET status = 'completed' WHERE appointment_id = 1;

-- Add reviews for completed sessions
INSERT INTO Reviews (appointment_id, reviewer_id, reviewee_id, rating, title, content, is_anonymous) 
VALUES 
(1, 3, 29, 5, 'Excellent tutor!', 'Kelvin explained everything clearly and was very patient.', FALSE),
(1, 3, 29, 4, 'Very helpful', 'I understood algebra better after the session.', TRUE);
