-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 10:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peertutor`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `meeting_link` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `student_id`, `tutor_id`, `subject_id`, `start_datetime`, `end_datetime`, `status`, `meeting_link`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 1, '2025-05-10 10:00:00', '2025-05-10 11:00:00', 'completed', 'https://meet.example.com/session1', 'Intro to Calculus', '2025-05-04 13:45:43', '2025-05-04 18:04:22'),
(10, 8, 2, 3, '2025-05-04 13:51:58', '2025-05-04 14:51:58', 'confirmed', NULL, NULL, '2025-05-04 13:53:24', '2025-05-04 13:53:24'),
(11, 28, 27, 5, '2025-05-12 09:00:00', '2025-05-12 10:00:00', 'completed', 'https://www.youtube.com/playlist?list=PL5vZ49dm2gshlo1EIxFNcQFBUfLFoHPfp', '', '2025-05-05 00:45:14', '2025-05-05 12:35:05'),
(12, 28, 27, 2, '2025-05-06 11:00:00', '2025-05-06 12:00:00', 'confirmed', 'https://www.youtube.com/playlist?list=PL4cUxeGkcC9gC88BEo9czgyS72A3doDeM', 'Yeah', '2025-05-05 12:02:29', '2025-05-05 19:00:15'),
(13, 28, 27, 7, '2025-05-12 09:00:00', '2025-05-12 10:00:00', 'pending', 'https://www.youtube.com/playlist?list=PL5vZ49dm2gshlo1EIxFNcQFBUfLFoHPfp', 'I want practice questions as well', '2025-05-05 12:21:09', '2025-05-05 12:21:09'),
(14, 28, 27, 1, '2025-05-09 14:00:00', '2025-05-09 15:00:00', 'pending', 'https://www.youtube.com/playlist?list=PL4cUxeGkcC9gC88BEo9czgyS72A3doDeM', '', '2025-05-05 19:06:28', '2025-05-05 19:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `availability_id` int(11) NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backup_logs`
--

CREATE TABLE `backup_logs` (
  `log_id` int(11) NOT NULL,
  `backup_id` int(11) DEFAULT NULL,
  `operation` enum('create','restore','delete','download','verify') NOT NULL,
  `status` enum('started','completed','failed') NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `backup_logs`
--

INSERT INTO `backup_logs` (`log_id`, `backup_id`, `operation`, `status`, `timestamp`, `user_id`, `details`) VALUES
(1, 1, 'create', 'completed', '2025-04-28 18:56:29', 1, 'Automated nightly backup'),
(2, 1, 'download', 'completed', '2025-04-28 18:56:29', 1, 'Downloaded for verification'),
(3, 2, 'create', 'completed', '2025-04-28 18:56:29', 1, 'Manual backup before update');

-- --------------------------------------------------------

--
-- Table structure for table `backup_records`
--

CREATE TABLE `backup_records` (
  `backup_id` int(11) NOT NULL,
  `backup_name` varchar(255) NOT NULL,
  `backup_type` enum('full','database','incremental') NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(512) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `status` enum('pending','in_progress','completed','failed') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `checksum` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `backup_records`
--

INSERT INTO `backup_records` (`backup_id`, `backup_name`, `backup_type`, `filename`, `filepath`, `file_size`, `status`, `created_at`, `completed_at`, `created_by`, `notes`, `checksum`) VALUES
(1, 'Nightly DB Backup', 'database', 'backup_20230615.sql', '/backups/db/backup_20230615.sql', 1024000, 'completed', '2025-04-27 18:56:29', '2025-04-27 19:01:29', 1, NULL, NULL),
(2, 'Manual Full Backup', 'full', 'full_backup.zip', '/backups/full/full_backup.zip', 2048000, 'completed', '2025-04-28 15:56:29', '2025-04-28 16:56:29', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `last_message_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `department`, `description`, `created_at`, `updated_at`) VALUES
(1, 'MATH101', 'Calculus I', 'Mathematics', 'Introductory calculus', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(2, 'PHYS201', 'Physics II', 'Physics', 'Electricity and magnetism', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(3, 'CS105', 'Intro to Programming', 'Computer Science', 'Basics of coding', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(4, 'ENG102', 'English Literature', 'Humanities', 'Poetry and prose', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(5, 'BIO150', 'Cell Biology', 'Biology', 'Foundations of cell biology', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(6, 'CHEM120', 'General Chemistry', 'Chemistry', 'Chemical principles', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(7, 'MATH201', 'Linear Algebra', 'Mathematics', 'Matrices and vector spaces', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(8, 'CS210', 'Data Structures', 'Computer Science', 'Efficient algorithms', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(9, 'ENG205', 'Creative Writing', 'Humanities', 'Fiction and storytelling', '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(10, 'PHYS101', 'Classical Mechanics', 'Physics', 'Newtonian physics', '2025-05-01 20:08:43', '2025-05-01 20:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `recipient_id`, `content`, `is_read`, `created_at`) VALUES
(1, 2, 27, 'Hello, are you free for a session?', 0, '2025-05-04 19:41:35'),
(2, 28, 27, 'Hello Adrian. I\'ll see you', 0, '2025-05-05 00:50:00'),
(3, 27, 28, 'Well, hello Ingrid, I\'ll see you as well', 0, '2025-05-05 00:51:11'),
(4, 27, 28, 'What\'s up', 0, '2025-05-05 19:00:33'),
(5, 28, 27, 'Yup', 0, '2025-05-05 19:06:53');

--
-- Triggers `messages`
--
DELIMITER $$
CREATE TRIGGER `mark_message_notifications` AFTER UPDATE ON `messages` FOR EACH ROW BEGIN
    IF NEW.is_read = TRUE AND OLD.is_read = FALSE THEN
        UPDATE Notifications
        SET is_read = TRUE
        WHERE related_id = NEW.message_id AND type = 'message';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('session_request','session_confirmed','session_cancelled','session_reminder','message','review','system') NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `related_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `year_of_study` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`profile_id`, `user_id`, `bio`, `profile_picture_url`, `department`, `year_of_study`, `created_at`, `updated_at`) VALUES
(2, 7, NULL, NULL, NULL, NULL, '2025-05-01 09:29:53', '2025-05-01 09:29:53'),
(23, 8, 'Math enthusiast.', NULL, 'Mathematics', 2, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(24, 9, 'Aspiring engineer.', NULL, 'Engineering', 3, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(25, 10, 'Experienced math tutor.', NULL, 'Mathematics', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(26, 11, 'Physics tutor with PhD.', NULL, 'Physics', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(27, 12, 'Loves learning.', NULL, 'Computer Science', 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(28, 13, 'Expert in coding.', NULL, 'Computer Science', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(29, 14, 'Mechanical engineer.', NULL, 'Engineering', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(30, 7, 'Keen on biology.', NULL, 'Biology', 2, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(31, 1, 'System admin.', NULL, 'IT', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(32, 2, 'Admin user.', NULL, 'Admin Dept', NULL, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(33, 18, NULL, NULL, NULL, NULL, '2025-05-02 20:11:10', '2025-05-02 20:11:10'),
(34, 19, 'My name is Jack and I love coding. Join me and let\'s code to our hearts content', NULL, NULL, NULL, '2025-05-02 20:15:04', '2025-05-02 21:33:36'),
(35, 20, NULL, NULL, NULL, NULL, '2025-05-02 20:36:56', '2025-05-02 20:36:56'),
(36, 21, NULL, NULL, NULL, NULL, '2025-05-02 20:40:15', '2025-05-02 20:40:15'),
(37, 22, NULL, NULL, NULL, NULL, '2025-05-03 00:34:24', '2025-05-03 00:34:24'),
(38, 23, '5 years', 'uploads/avatars/23_1746307863.png', NULL, NULL, '2025-05-03 21:23:03', '2025-05-03 21:31:03'),
(39, 24, NULL, NULL, NULL, NULL, '2025-05-03 21:49:28', '2025-05-03 21:49:28'),
(40, 25, 'Hehehehehe', 'uploads/avatars/25_1746382722.jpg', NULL, NULL, '2025-05-04 18:16:07', '2025-05-04 18:18:42'),
(41, 26, NULL, NULL, NULL, NULL, '2025-05-04 18:24:51', '2025-05-04 18:24:51'),
(42, 27, 'My name is Adrian Kerr. I love teaching and giving back to people.', 'uploads/avatars/27_1746471666.jpg', NULL, NULL, '2025-05-04 19:01:20', '2025-05-05 19:01:07'),
(43, 28, '', 'uploads/avatars/28_1746472045.jpg', NULL, NULL, '2025-05-05 00:43:24', '2025-05-05 19:07:25'),
(44, 29, '', 'uploads/avatars/1746469048_wallpaperflare.com_wallpaper (1).jpg', NULL, NULL, '2025-05-05 18:17:28', '2025-05-05 18:17:28'),
(45, 30, NULL, NULL, NULL, NULL, '2025-05-05 18:18:54', '2025-05-05 18:18:54'),
(46, 31, '', 'uploads/avatars/1746471871_wallpaperflare.com_wallpaper (5).jpg', NULL, NULL, '2025-05-05 19:04:31', '2025-05-05 19:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `session_id`, `student_id`, `tutor_id`, `rating`, `comment`, `is_anonymous`, `created_at`, `updated_at`) VALUES
(8, 8, 3, 2, 5, 'Great session! Very clear and helpful.', 0, '2025-04-20 10:00:00', '2025-05-04 15:05:28'),
(9, 9, 8, 8, 4, 'The tutor explained well but rushed a bit.', 0, '2025-04-21 11:30:00', '2025-05-04 15:05:28'),
(11, 11, 13, 8, 5, 'Amazing teaching style! Will book again.', 0, '2025-04-23 13:45:00', '2025-05-04 15:06:01'),
(12, 12, 16, 2, 2, 'The tutor was not prepared.', 1, '2025-04-24 08:20:00', '2025-05-04 15:06:12'),
(14, 14, 20, 11, 5, 'Fantastic tutor. Broke down complex topics.', 0, '2025-04-26 16:10:00', '2025-05-04 15:06:38'),
(31, 11, 28, 27, 1, 'Incididunt et dolore', 0, '2025-05-05 17:09:31', '2025-05-05 17:09:31');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `update_tutor_rating` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    UPDATE TutorProfiles
    SET overall_rating = (
        SELECT AVG(rating)
        FROM Reviews
        WHERE tutor_id = NEW.tutor_id
    )
    WHERE tutor_profile_id = NEW.tutor_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('requested','confirmed','cancelled','completed') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `session_type` enum('in-person','online') NOT NULL,
  `session_notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `student_id`, `tutor_id`, `course_id`, `start_time`, `end_time`, `status`, `location`, `session_type`, `session_notes`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(8, 3, 2, 1, '2025-04-15 10:00:00', '2025-04-15 11:00:00', 'completed', 'Room A1', 'in-person', 'Reviewed algebra basics.', NULL, '2025-04-14 09:00:00', '2025-05-04 18:46:24'),
(9, 8, 10, 2, '2025-04-16 14:00:00', '2025-04-16 15:00:00', 'completed', 'Online', '', 'Covered calculus limits.', NULL, '2025-04-15 12:30:00', '2025-05-04 18:46:24'),
(11, 13, 11, 4, '2025-04-18 13:00:00', '2025-04-18 14:30:00', 'completed', 'Room C2', 'in-person', 'Reviewed for upcoming test.', NULL, '2025-04-17 10:00:00', '2025-05-04 18:46:24'),
(12, 16, 14, 5, '2025-04-19 15:00:00', '2025-04-19 16:00:00', 'cancelled', 'Online', '', NULL, 'Tutor was unavailable.', '2025-04-18 14:00:00', '2025-05-04 18:46:24'),
(14, 20, 15, 7, '2025-04-21 10:00:00', '2025-04-21 11:00:00', 'completed', 'Online', '', 'Focused on exam techniques.', NULL, '2025-04-20 10:00:00', '2025-05-04 18:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `severity` enum('critical','error','warning','info','debug') NOT NULL,
  `module` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `request_id` varchar(50) DEFAULT NULL,
  `stack_trace` text DEFAULT NULL,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `timestamp`, `severity`, `module`, `message`, `user_id`, `ip_address`, `request_id`, `stack_trace`, `additional_data`) VALUES
(1, '2025-04-26 18:56:29', 'error', 'database', 'Failed to connect to MySQL server', 1, '192.168.1.10', NULL, NULL, NULL),
(2, '2025-04-27 18:56:29', 'warning', 'auth', 'Invalid login attempt', 3, '10.0.1.15', NULL, NULL, NULL),
(3, '2025-04-28 06:56:29', 'info', 'backup', 'Nightly backup completed', 1, '192.168.1.10', NULL, NULL, NULL),
(4, '2025-04-28 15:56:29', 'debug', 'api', 'GET /api/tutors called', 2, '192.168.1.20', NULL, NULL, NULL),
(5, '2023-05-15 08:30:45', 'info', 'Authentication', 'User login successful', 0, '192.168.1.100', 'req-abc123', NULL, '{\"browser\":\"Chrome\",\"os\":\"Windows 10\"}'),
(6, '2023-05-15 09:15:22', 'warning', 'Payment', 'Failed payment attempt', 0, '203.0.113.42', 'req-def456', NULL, '{\"amount\":29.99,\"method\":\"credit_card\"}'),
(7, '2023-05-15 10:02:33', 'error', 'Database', 'Connection timeout', NULL, '10.0.0.1', 'req-ghi789', 'java.sql.SQLTimeoutException...', '{\"retry_count\":3,\"server\":\"db01\"}'),
(8, '2023-05-15 11:45:12', 'debug', 'API', 'Request received', 0, '198.51.100.75', 'req-jkl012', NULL, '{\"endpoint\":\"/api/users\",\"method\":\"GET\"}'),
(9, '2023-05-15 13:20:05', 'critical', 'Backup', 'Backup failed - disk full', 0, '192.168.1.200', 'req-mno345', 'java.io.IOException: No space left on device...', '{\"backup_size\":\"15GB\",\"disk_space\":\"0MB\"}'),
(10, '2023-05-15 14:33:18', 'info', 'Inventory', 'Product stock updated', 0, '172.16.0.55', 'req-pqr678', NULL, '{\"product_id\":42,\"old_stock\":15,\"new_stock\":12}'),
(11, '2023-05-15 15:47:29', 'error', 'Email', 'Failed to send email', NULL, '192.168.1.150', 'req-stu901', 'javax.mail.MessagingException...', '{\"recipient\":\"user@example.com\",\"subject\":\"Order Confirmation\"}'),
(12, '2023-05-15 16:55:41', 'warning', 'Security', 'Multiple failed login attempts', 0, '203.0.113.89', 'req-vwx234', NULL, '{\"attempts\":5,\"locked\":false}'),
(13, '2023-05-15 17:12:07', 'info', 'Order', 'New order placed', 0, '198.51.100.33', 'req-yza567', NULL, '{\"order_id\":10042,\"total\":89.99,\"items\":3}'),
(14, '2023-05-15 18:30:55', 'debug', 'Cache', 'Cache cleared', 0, '10.0.0.2', 'req-bcd890', NULL, '{\"cache_type\":\"user_sessions\",\"size\":\"2.5MB\"}');

-- --------------------------------------------------------

--
-- Table structure for table `tutorcourses`
--

CREATE TABLE `tutorcourses` (
  `tutor_course_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `proficiency_level` enum('beginner','intermediate','advanced','expert') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorcourses`
--

INSERT INTO `tutorcourses` (`tutor_course_id`, `tutor_id`, `course_id`, `proficiency_level`, `created_at`) VALUES
(1, 2, 1, 'expert', '2025-05-01 20:08:43'),
(2, 10, 2, 'advanced', '2025-05-01 20:08:43'),
(3, 11, 3, 'expert', '2025-05-01 20:08:43'),
(4, 8, 4, 'intermediate', '2025-05-01 20:08:43'),
(5, 14, 5, 'advanced', '2025-05-01 20:08:43'),
(6, 15, 6, 'expert', '2025-05-01 20:08:43'),
(7, 19, 7, 'advanced', '2025-05-01 20:08:43'),
(8, 26, 8, 'intermediate', '2025-05-01 20:08:43'),
(9, 22, 9, 'expert', '2025-05-01 20:08:43'),
(10, 23, 10, 'advanced', '2025-05-01 20:08:43'),
(19, 27, 1, 'beginner', '2025-05-05 00:55:17'),
(20, 27, 5, 'beginner', '2025-05-05 00:55:17'),
(21, 27, 7, 'beginner', '2025-05-05 00:55:17'),
(22, 27, 2, 'beginner', '2025-05-05 00:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `tutorprofiles`
--

CREATE TABLE `tutorprofiles` (
  `tutor_profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `availability_schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`availability_schedule`)),
  `overall_rating` decimal(3,2) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorprofiles`
--

INSERT INTO `tutorprofiles` (`tutor_profile_id`, `user_id`, `hourly_rate`, `availability_schedule`, `overall_rating`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 1, 25.00, '{\"Monday\": [\"10:00-12:00\", \"14:00-16:00\"]}', 4.50, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(2, 2, 30.00, '{\"Tuesday\": [\"09:00-11:00\"]}', 4.80, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(3, 7, 20.00, '{\"Wednesday\": [\"13:00-15:00\"]}', 4.20, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(4, 8, 18.50, '{\"Friday\": [\"08:00-10:00\"]}', 4.00, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(5, 1, 26.00, '{\"Thursday\": [\"10:00-12:00\"]}', 4.75, 1, '2025-05-01 20:08:43', '2025-05-03 13:03:49'),
(7, 7, 21.00, '{\"Tuesday\": [\"10:00-12:00\"]}', 2.00, 1, '2025-05-01 20:08:43', '2025-05-03 13:03:49'),
(8, 8, 19.00, '{\"Wednesday\": [\"09:00-11:00\"]}', 4.00, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(9, 3, 27.00, '{\"Friday\": [\"10:00-12:00\"]}', 4.50, 1, '2025-05-01 20:08:43', '2025-05-01 20:08:43'),
(11, 19, NULL, NULL, NULL, 0, '2025-05-02 20:15:04', '2025-05-02 20:15:04'),
(12, 22, NULL, NULL, NULL, 0, '2025-05-03 00:34:24', '2025-05-03 00:34:24'),
(13, 23, NULL, '{\"Monday\":\"9:00 AM - 12:00 PM, 1:00 PM - 4:00 PM\",\"Tuesday\":\"10:00 AM - 2:00 PM\",\"Wednesday\":\"Not Available\",\"Thursday\":\"9:00 AM - 12:00 PM\",\"Friday\":\"2:00 PM - 5:00 PM\"}', NULL, 0, '2025-05-03 21:23:03', '2025-05-03 21:24:29'),
(14, 26, NULL, NULL, NULL, 0, '2025-05-04 18:24:51', '2025-05-04 18:24:51'),
(15, 27, NULL, '{\"Monday\":\"9:00 AM - 12:00 PM, 1:00 PM - 4:00 PM\",\"Tuesday\":\"10:00 AM - 2:00 PM\",\"Wednesday\":\"Not Available\",\"Thursday\":\"9:00 AM - 12:00 PM\",\"Friday\":\"2:00 PM - 5:00 PM\"}', NULL, 0, '2025-05-04 19:01:20', '2025-05-04 19:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','tutor','admin') NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `verification_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `role`, `first_name`, `last_name`, `phone_number`, `created_at`, `updated_at`, `last_login`, `is_active`, `verification_status`) VALUES
(1, 'admin@tutoring.edu', 'admin123', 'admin', 'John', 'Smith', '555-0101', '2025-04-29 13:38:46', '2025-04-29 13:38:46', '2025-04-29 13:38:46', 1, 0),
(2, 'tutor1@tutoring.edu', 'tutor1pass', 'tutor', 'Sarah', 'Johnson', '555-0102', '2025-04-28 18:56:29', '2025-04-28 18:56:29', '2025-04-28 18:56:29', 1, 0),
(3, 'student1@tutoring.edu', 'student1pass', 'student', 'Emily', 'Davis', '555-0103', '2025-04-28 18:56:29', '2025-04-28 18:56:29', '2025-04-28 18:56:29', 1, 0),
(7, 'baaba.amosah@gmail.com', '$2y$10$cdJCn./WcCZvcggs1FcK..GfsMlIduOXkYYJpY5TfXR3PgQVo1nda', 'admin', 'Baaba', 'Amosah', '233559914844', '2025-05-01 09:29:53', '2025-05-01 09:32:49', '2025-05-01 09:29:53', 1, 0),
(8, 'alice@student.com', 'hash1', 'student', 'Alice', 'Johnson', '1234567890', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(9, 'bob@student.com', 'hash2', 'student', 'Bob', 'Smith', '2345678901', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(10, 'carla@tutor.com', 'hash3', 'tutor', 'Carla', 'Nguyen', '3456789012', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(11, 'david@tutor.com', 'hash4', 'tutor', 'David', 'Lee', '4567890123', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(12, 'emily@admin.com', 'hash5', 'admin', 'Emily', 'Brown', NULL, '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(13, 'frank@student.com', 'hash6', 'student', 'Frank', 'Mills', '5678901234', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 0),
(14, 'grace@tutor.com', 'hash7', 'tutor', 'Grace', 'Hopper', '6789012345', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(15, 'henry@tutor.com', 'hash8', 'tutor', 'Henry', 'Ford', '7890123456', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(16, 'ivy@student.com', 'hash9', 'student', 'Ivy', 'Walker', '8901234567', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 0),
(17, 'jack@admin.com', 'hash10', 'admin', 'Jack', 'Ryan', '9012345678', '2025-05-01 20:04:01', '2025-05-01 20:04:01', '2025-05-01 20:04:01', 1, 1),
(18, 'budimu@mailinator.com', '$2y$10$Tii.Hzf8ys.pyv1YsiOemOkqurz7muaB3qlGim0f3NPBRyQWPH.VC', 'student', 'Ali', 'Everett', '+1 (507) 898-6682', '2025-05-02 20:11:10', '2025-05-02 20:11:10', '2025-05-02 20:11:10', 1, 0),
(19, 'puzohi@mailinator.com', '$2y$10$DDyGP1HHny7gnwRVgYS.4upuTY2uUxTSaf.EMX.AVxST5rY8rwTx2', 'tutor', 'Jescie', 'Haley', '+1 (692) 251-4066', '2025-05-02 20:15:04', '2025-05-02 20:15:04', '2025-05-02 20:15:04', 1, 0),
(20, 'cenyhu@mailinator.com', '$2y$10$FhtvkwoJgFzRqV07yczwUeplWfc/GcRFS0c9O3Zf/LB8q//sbaXpO', 'student', 'Eric', 'Watkins', '+1 (245) 898-4837', '2025-05-02 20:36:56', '2025-05-02 20:36:56', '2025-05-02 20:36:56', 1, 0),
(21, 'venyleb@mailinator.com', '$2y$10$xGhQOjzl165cwPFjvNi6qOUrCXNuUc.rHQukq8sQkQZmjXzEN1862', 'admin', 'Yvette', 'Woodward', '+1 (682) 249-9861', '2025-05-02 20:40:15', '2025-05-02 20:40:15', '2025-05-02 20:40:15', 1, 0),
(22, 'jojekepu@mailinator.com', '$2y$10$8dLLUbsGrKcw7hxhWqhwNuRzu1Z.aAgRswM4IFzevIZGpUEEbpxaK', 'tutor', 'Fritz', 'Holden', '+1 (701) 456-6473', '2025-05-03 00:34:24', '2025-05-03 00:34:24', '2025-05-03 00:34:24', 1, 0),
(23, 'lasehifysy@mailinator.com', '$2y$10$gkrUJrdOVxeBCSSe9/WmweNjVkLLbZpXSwt9iOnh0Ezo3TJfTapIq', 'tutor', 'Amy', 'Cooke', '+1 (977) 498-4769', '2025-05-03 21:23:03', '2025-05-04 18:14:17', '2025-05-04 18:14:17', 1, 0),
(24, 'bigoli@mailinator.com', '$2y$10$ezRA2.hcontqhdRUn.gk4e.54Z5j.bvNK7yxVTfz6RlQuDSmMXDWS', 'student', 'Whoopi', 'Sanford', '+1 (257) 394-8846', '2025-05-03 21:49:28', '2025-05-03 21:49:28', '2025-05-03 21:49:28', 1, 0),
(25, 'nogusu@mailinator.com', '$2y$10$95fvCCy6NzwLg3Q13cKsB.f6vk72K1f6A6cLauvfV5AlzLDK6lZmK', 'student', 'Tana', 'Campos', '+1 (721) 729-2056', '2025-05-04 18:16:07', '2025-05-04 18:50:47', '2025-05-04 18:50:47', 1, 0),
(26, 'dibazi@mailinator.com', '$2y$10$0WGCjl5rQoz.h9P0M10nvOUgrwtMXaWpKl4gBSG3PiAtuYngPGtKu', 'student', 'Sage', 'Lang', '+1 (756) 985-4232', '2025-05-04 18:24:51', '2025-05-04 18:48:40', '2025-05-04 18:24:51', 1, 0),
(27, 'tyjyca@mailinator.com', '$2y$10$hyGYFR0mPpnEFga9qZ36/eY4poBnBcdp8bb9D1VV/5K.T1FZpoMme', 'tutor', 'Adrian', 'Kerr', '+1 (914) 949-2633', '2025-05-04 19:01:20', '2025-05-05 18:57:23', '2025-05-05 18:57:23', 1, 0),
(28, 'rufihaju@mailinator.com', '$2y$10$u2Xmzi1tl/jQdVg0viGMp.gNKksFKx7NO4rX6H3J.lP7TUIzD3M4K', 'student', 'Ingrid', 'Lancaster', '+1 (269) 457-7715', '2025-05-05 00:43:24', '2025-05-05 19:05:23', '2025-05-05 19:05:23', 1, 0),
(29, 'vapavuregi@mailinator.com', '$2y$10$rs70vdWcVqrYlfkQtRo7QuOcDZ6rzG.UN.0EGx9FIvtd8Ef1YlqVG', 'admin', 'Herman', 'Suarez', '+1 (416) 946-3971', '2025-05-05 18:17:28', '2025-05-05 19:08:34', '2025-05-05 19:08:34', 1, 0),
(30, 'gaqytire@mailinator.com', '$2y$10$5yHeAsfTDQ8RcRpMu9tGkOXkJ./hiFle2RcJSVndMJ0dBIbyNJV2K', 'admin', 'Aristotle', 'Short', '+1 (405) 374-1866', '2025-05-05 18:18:54', '2025-05-05 18:18:54', '2025-05-05 18:18:54', 1, 0),
(31, 'zityq@mailinator.com', '$2y$10$u2Xmzi1tl/jQdVg0viGMp.gNKksFKx7NO4rX6H3J.lP7TUIzD3M4K', 'student', 'Dacey', 'Richard', '+1 (145) 642-7764', '2025-05-05 19:04:31', '2025-05-05 19:04:31', '2025-05-05 19:04:31', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `backup_id` (`backup_id`),
  ADD KEY `idx_operation` (`operation`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indexes for table `backup_records`
--
ALTER TABLE `backup_records`
  ADD PRIMARY KEY (`backup_id`),
  ADD KEY `idx_type` (`backup_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`),
  ADD UNIQUE KEY `user1_id` (`user1_id`,`user2_id`),
  ADD KEY `user2_id` (`user2_id`),
  ADD KEY `last_message_id` (`last_message_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `idx_Courses_dept` (`department`),
  ADD KEY `idx_Courses_code` (`course_code`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_messages_sender` (`sender_id`),
  ADD KEY `idx_messages_recipient` (`recipient_id`),
  ADD KEY `idx_messages_read` (`is_read`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_notifications_user` (`user_id`),
  ADD KEY `idx_notifications_read` (`is_read`),
  ADD KEY `idx_notifications_type` (`type`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `session_id` (`session_id`,`student_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_sessions_tutor` (`tutor_id`),
  ADD KEY `idx_sessions_student` (`student_id`),
  ADD KEY `idx_sessions_course` (`course_id`),
  ADD KEY `idx_sessions_status` (`status`),
  ADD KEY `idx_sessions_date` (`start_time`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_timestamp` (`timestamp`),
  ADD KEY `idx_severity` (`severity`),
  ADD KEY `idx_module` (`module`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `tutorcourses`
--
ALTER TABLE `tutorcourses`
  ADD PRIMARY KEY (`tutor_course_id`),
  ADD UNIQUE KEY `tutor_id` (`tutor_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  ADD PRIMARY KEY (`tutor_profile_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_tutors_rating` (`overall_rating`),
  ADD KEY `idx_tutors_verified` (`is_verified`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backup_logs`
--
ALTER TABLE `backup_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `backup_records`
--
ALTER TABLE `backup_records`
  MODIFY `backup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tutorcourses`
--
ALTER TABLE `tutorcourses`
  MODIFY `tutor_course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  MODIFY `tutor_profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD CONSTRAINT `backup_logs_ibfk_1` FOREIGN KEY (`backup_id`) REFERENCES `backup_records` (`backup_id`) ON DELETE SET NULL;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_3` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`message_id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_4` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutorcourses`
--
ALTER TABLE `tutorcourses`
  ADD CONSTRAINT `tutorcourses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorcourses_ibfk_3` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  ADD CONSTRAINT `tutorprofiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
