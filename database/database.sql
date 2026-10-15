CREATE DATABASE IF NOT EXISTS gcet_campusconnect;
USE gcet_campusconnect;

-- Departments Table
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    department_code VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(50) UNIQUE NULL, -- Nullable for staff/admin
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    department_id INT NULL,
    semester INT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'staff', 'admin') DEFAULT 'student',
    account_status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    default_department_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (default_department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Requests Table (Complaints & Service Requests)
CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    request_type ENUM('complaint', 'service') NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NULL,
    department_id INT NULL,
    assigned_staff_id INT NULL,
    location VARCHAR(150),
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('submitted', 'under_review', 'in_progress', 'awaiting_response', 'resolved', 'closed', 'rejected') DEFAULT 'submitted',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_staff_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Request Attachments
CREATE TABLE IF NOT EXISTS request_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100),
    file_size INT,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Request Status History
CREATE TABLE IF NOT EXISTS request_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    previous_status VARCHAR(50) NULL,
    new_status VARCHAR(50) NOT NULL,
    changed_by INT NOT NULL,
    public_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Request Messages / Clarifications
CREATE TABLE IF NOT EXISTS request_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    visibility ENUM('public', 'internal') DEFAULT 'public',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_id INT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE
);

-- Audit Logs
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actor_id INT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value) VALUES 
('college_name', 'G. H. Patel College of Engineering & Technology'),
('college_abbreviation', 'GCET'),
('college_location', 'Anand, Gujarat, India'),
('contact_email', 'info@gcet.ac.in'),
('contact_phone', '+91-2692-231651');

-- Insert Default GCET Departments
INSERT INTO departments (department_name, department_code, description) VALUES
('Chemical Engineering', 'CHE', 'Department of Chemical Engineering'),
('Information Technology', 'IT', 'Department of Information Technology'),
('Computer Science and Engineering (IOT)', 'CSE-IOT', 'Department of Computer Science and Engineering (IOT)'),
('Mechanical Engineering', 'ME', 'Department of Mechanical Engineering'),
('Electronics & Communication', 'EC', 'Department of Electronics & Communication Engineering'),
('Information & Communication Technology', 'ICT', 'Department of Information & Communication Technology'),
('Mechatronics Engineering', 'MC', 'Department of Mechatronics Engineering'),
('Computer Engineering', 'CP', 'Department of Computer Engineering'),
('Computer Science and Design', 'CSD', 'Department of Computer Science and Design'),
('Electrical Engineering', 'EE', 'Department of Electrical Engineering'),
('Civil Engineering', 'CL', 'Department of Civil Engineering'),
('Applied Science & Humanities', 'ASH', 'Department of Applied Science & Humanities'),
('IT Infrastructure & Labs', 'IT-INFRA', 'Campus Wi-Fi, Computer Labs & Network Infrastructure'),
('Estate & Maintenance', 'ESTATE', 'Campus Civil, Electrical & Plumbing Maintenance'),
('Library Services', 'LIB', 'GCET Central Library & Digital Resources'),
('Hostel & Campus Life', 'HOSTEL', 'GCET Hostel & Residential Facilities');

-- Insert Initial Admin Account (Password: admin123)
INSERT INTO users (full_name, email, password_hash, role) VALUES 
('System Administrator', 'admin@gcet.ac.in', '$2y$10$yEw39WLeKM2wytN/hMicnuUYFxBziL.c0fVlJYcdC9yWodRGotd06', 'admin');
