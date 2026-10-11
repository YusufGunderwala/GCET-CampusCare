# GCET CampusCare — College Complaint & Service Portal

## Project Overview
GCET CampusCare is a digital platform designed for G. H. Patel College of Engineering & Technology (GCET), Anand. It provides a centralized web application where students can submit complaints, request campus services, and track their request status, while department staff and administrators can review, assign, and resolve these requests efficiently.

## Architecture & Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Chart.js.
- **Backend:** PHP 8.x
- **Database:** MySQL (using PDO for secure, prepared statements)
- **Architecture Pattern:** A lightweight, secure, procedural/modular PHP structure (MVC-lite approach separating config, includes, API, and role-specific folders).

## Database Relationships
- `users`: Stores all students, staff, and admins. Linked to `departments`.
- `departments`: Managed by admins. Staff are assigned here. Categories map here.
- `categories`: Complaint/Service categories with a default department routing.
- `requests`: The main table storing complaints/services. Linked to `users` (submitter), `categories`, `departments`, and `users` (assigned staff).
- `request_attachments`: Files attached to requests.
- `request_status_history`: Audit trail of status changes.
- `request_messages`: Public/internal clarifications and replies.
- `notifications`: In-app alerts for users.

## Implementation Plan

### Phase 1: Foundation (Current)
- Initialize directory structure and configuration (`config/app.php`, `config/database.php`).
- Define the complete MySQL schema (`database/database.sql`).
- Set up core utility functions and CSRF protection (`includes/functions.php`, `includes/csrf.php`).
- Establish Authentication logic (`includes/auth.php`).

### Phase 2: Core Functionality & Public Views
- Build public pages (`index.php`, `about.php`, `track.php`).
- Create user authentication flows (`login.php`, `register.php`, `logout.php`).
- Implement Student Dashboard and Complaint/Service request submission forms.

### Phase 3: Staff & Administrator Roles
- Develop Staff Dashboard for handling assigned department requests.
- Develop Administrator Dashboard for system-wide metrics.
- Build Admin management modules for Users, Departments, and Categories.

### Phase 4: Advanced Features & Refinement
- Add AJAX form submissions and basic REST APIs for live filtering.
- Implement dashboard analytics using Chart.js.
- Ensure thorough input validation, XSS prevention, and access controls.

---

### Setup Instructions
1. Copy the project folder into your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\Complaint System`).
2. Create a MySQL database named `gcet_campuscare` using phpMyAdmin.
3. Import the `database/database.sql` file.
4. Ensure Apache and MySQL are running.
5. Visit `http://localhost/Complaint System/` in your browser.
6. Login with the default admin account:
   - **Email:** `admin@gcet.ac.in`
   - **Password:** `admin123`
