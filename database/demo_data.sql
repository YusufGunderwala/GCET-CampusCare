-- Demo Data for GCET CampusConnect
USE gcet_campusconnect;

-- Insert Departments
INSERT INTO departments (department_name, department_code, description) VALUES
('IT Infrastructure', 'IT01', 'Manages computer labs, network, and WiFi issues.'),
('Electrical Maintenance', 'EL01', 'Handles power, lighting, and AC issues.'),
('Estate & Maintenance', 'ES01', 'Handles building repairs, plumbing, and sanitation.'),
('Library Services', 'LIB01', 'Manages library resources and access.'),
('Hostel Administration', 'HOS01', 'Manages student accommodation issues.');

-- Insert Categories
INSERT INTO categories (category_name, description, default_department_id) VALUES
('Computer Laboratory', 'Hardware or software issues in labs', 1),
('Network and Wi-Fi', 'Internet connectivity issues', 1),
('Electrical and Maintenance', 'Lights, fans, AC, power sockets', 2),
('Cleanliness and Sanitation', 'Washrooms, classrooms cleaning', 3),
('Library', 'Book issues, reading room complaints', 4),
('Hostel', 'Hostel room, mess, and water issues', 5);

-- Insert Staff Users (Password: staff123)
INSERT INTO users (full_name, email, department_id, password_hash, role) VALUES 
('IT Staff Member', 'itstaff@gcet.ac.in', 1, '$2y$10$IBoOhaSRM2KKkhg9vq9IS.owzfwleKFo85ObmnaIFrjQsngM8x6Qi', 'staff'),
('Estate Manager', 'estate@gcet.ac.in', 3, '$2y$10$IBoOhaSRM2KKkhg9vq9IS.owzfwleKFo85ObmnaIFrjQsngM8x6Qi', 'staff')
ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);

-- Insert Student (Password: student123)
INSERT INTO users (full_name, student_id, email, password_hash, role) VALUES 
('John Doe', '12001011001', 'johndoe@student.gcet.ac.in', '$2y$10$3Mn/WhMfuUujj0WoLHPPiO2am.j/xrxQPqu1Z4WulM4b09ysLJyfC', 'student')
ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);

-- Insert Demo Request
INSERT INTO requests (reference_number, user_id, request_type, title, description, category_id, department_id, location, status) VALUES
('GCET-CMP-2026-0001', (SELECT id FROM users WHERE role='student' LIMIT 1), 'complaint', 'Wi-Fi not working in Lab 4', 'Unable to connect to the GCET_Student network in Lab 4 since morning.', 2, 1, 'Lab 4', 'submitted');
