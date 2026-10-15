# GCET CampusConnect — College Complaint & Service Portal

## Project Overview
GCET CampusConnect is a digital platform designed for G. H. Patel College of Engineering & Technology (GCET), Anand. It provides a centralized web application where students can submit complaints, request campus services, and track their request status, while department staff and administrators can review, assign, and resolve these requests efficiently.

## Architecture & Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Bootstrap Icons, Chart.js.
- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB (using PDO for secure, prepared statements)
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

---

### Setup Instructions
1. Run Apache and MySQL in your XAMPP installation (supports default port 3306 or 3307).
2. The database `gcet_campusconnect` is automatically configured and connected.
3. Visit `http://localhost/GCET-CampusConnect/` in your browser.
4. Default Demo Accounts:
   - **Administrator:** `admin@gcet.ac.in` / Password: `admin123`
   - **IT Staff:** `itstaff@gcet.ac.in` / Password: `staff123`
   - **Estate Manager:** `estate@gcet.ac.in` / Password: `staff123`
   - **Student:** `johndoe@student.gcet.ac.in` / Password: `student123`
