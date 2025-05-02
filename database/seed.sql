-- 2. Insert users (with plain text passwords for testing)
INSERT INTO Users (email, password_hash, role, first_name, last_name, phone_number, is_active) VALUES
('admin@tutoring.edu', 'admin123', 'admin', 'John', 'Smith', '555-0101', 1),
('tutor1@tutoring.edu', 'tutor1pass', 'tutor', 'Sarah', 'Johnson', '555-0102', 1),
('student1@tutoring.edu', 'student1pass', 'student', 'Emily', 'Davis', '555-0103', 1);

-- 3. Insert system logs
INSERT INTO system_logs (timestamp, severity, module, message, user_id, ip_address) VALUES
(NOW() - INTERVAL 2 DAY, 'error', 'database', 'Failed to connect to MySQL server', 1, '192.168.1.10'),
(NOW() - INTERVAL 1 DAY, 'warning', 'auth', 'Invalid login attempt', NULL, '10.0.1.15'),
(NOW() - INTERVAL 12 HOUR, 'info', 'backup', 'Nightly backup completed', 1, '192.168.1.10'),
(NOW() - INTERVAL 3 HOUR, 'debug', 'api', 'GET /api/tutors called', 2, '192.168.1.20');

-- 4. Insert backup records
INSERT INTO backup_records (backup_name, backup_type, filename, filepath, file_size, status, created_at, completed_at, created_by) VALUES
('Nightly DB Backup', 'database', 'backup_20230615.sql', '/backups/db/backup_20230615.sql', 1024000, 'completed', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY + INTERVAL 5 MINUTE, 1),
('Manual Full Backup', 'full', 'full_backup.zip', '/backups/full/full_backup.zip', 2048000, 'completed', NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 2 HOUR, 1);

-- 5. Insert backup logs
INSERT INTO backup_logs (backup_id, operation, status, user_id, details) VALUES
(1, 'create', 'completed', 1, 'Automated nightly backup'),
(1, 'download', 'completed', 1, 'Downloaded for verification'),
(2, 'create', 'completed', 1, 'Manual backup before update');