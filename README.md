# 🎓 GCET CampusConnect — College Complaint & Service Portal

<p align="center">
  <strong>A centralized digital platform for campus complaints, service requests, and communication.</strong>
</p>

<p align="center">
  A web-based campus management solution designed for G. H. Patel College of Engineering & Technology (GCET), Anand, Gujarat.
</p>

---

## 📌 Project Overview

**GCET CampusConnect** is a web-based campus management platform designed to simplify campus services, improve communication, and enhance the student experience.

The platform enables students to submit complaints, request campus services, communicate with departments, and track request progress through a centralized interface.

Department staff can review and manage assigned requests, while administrators oversee departments, users, categories, and campus service operations.

### 🎯 Project Objectives

- Centralize campus complaints and service requests.
- Improve communication between students and college departments.
- Provide transparent request tracking and status updates.
- Streamline complaint assignment and resolution.
- Maintain a history of request-related activities.
- Provide role-based access to campus services.

---

## ✨ Key Features

### 👨‍🎓 Student Portal
- Submit complaints and campus service requests.
- Track request status and progress.
- Attach supporting files to requests.
- Communicate with staff through request messages.
- Receive in-app notifications.

### 🧑‍💻 Staff Portal
- Review assigned requests.
- Update request status.
- Communicate with students.
- Access relevant request information.
- Maintain request resolution history.

### 🛡️ Administrator Portal
- Manage users and departments.
- Configure complaint and service categories.
- Monitor campus requests.
- Assign requests to appropriate staff.
- Oversee campus service operations.

### 🔔 Communication and Tracking
- In-app notifications.
- Public and internal request messages.
- Request status history.
- Attachment management.
- Department-based request routing.

*The features above describe the documented project functionality. Availability depends on the implementation in the current codebase.*

---

## 👥 User Roles

| Role | Responsibilities |
|---|---|
| Administrator | Manages users, departments, categories, and overall operations. |
| IT Staff | Handles IT-related complaints and service requests. |
| Estate Manager | Handles infrastructure and estate-related requests. |
| Student | Submits complaints, requests services, and tracks progress. |

---

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| HTML5 | Web page structure |
| CSS3 | Styling and layouts |
| JavaScript | Client-side interactions |
| Bootstrap 5 | Responsive user interface |
| Bootstrap Icons | Interface icons |
| Chart.js | Charts and data visualization |
| PHP 8.x | Server-side application logic |
| MySQL / MariaDB | Relational database |
| PDO | Database connectivity and prepared statements |
| Apache | Web server |
| XAMPP | Local development environment |
| Git and GitHub | Version control and source code hosting |

---

## 🏗️ Application Architecture

The application uses a modular PHP structure that separates configuration, shared components, API functionality, and role-specific features.

```text
GCET-CampusConnect/
│
├── config/                    # Application and database configuration
├── includes/                  # Shared components and utilities
├── api/                       # API endpoints, if present
├── admin/                     # Administrator functionality
├── student/                   # Student functionality
├── staff/                     # Staff functionality, if present
├── assets/                    # CSS, JavaScript, images, and other assets
├── uploads/                   # Uploaded files, if configured
├── index.php                  # Application entry point, if present
└── README.md                  # Project documentation
```

**Note:** This is an illustrative structure. Actual folder names may differ in the repository.

---

## 🗄️ Database Design

The application documentation describes the following database tables.

| Table | Purpose |
|---|---|
| `users` | Stores student, staff, and administrator information. |
| `departments` | Stores department information and assignments. |
| `categories` | Defines complaint and service categories. |
| `requests` | Stores complaints and service requests. |
| `request_attachments` | Stores metadata for request attachments. |
| `request_status_history` | Records request status changes. |
| `request_messages` | Stores public and internal request messages. |
| `notifications` | Stores in-app notification records. |

### 🔗 Database Relationships

- Users can be associated with departments.
- Categories can be associated with default departments.
- Requests reference their submitters and relevant categories.
- Requests can be assigned to staff members.
- Requests can have attachments, messages, and status history.
- Notifications are associated with relevant users.

These relationships help maintain organized request processing and communication.

---

## ⚙️ Installation and Setup

Follow these steps to run the application locally.

### Prerequisites

Install:

- [XAMPP](https://www.apachefriends.org/)
- [Git](https://git-scm.com/)
- A modern web browser

### Step 1: Clone the Repository

```bash
git clone https://github.com/jayparikh-12/GCET-CampusConnect.git
```

Navigate into the project directory:

```bash
cd GCET-CampusConnect
```

### Step 2: Configure XAMPP

1. Open the XAMPP Control Panel.
2. Start Apache.
3. Start MySQL.
4. Ensure the project is located inside the XAMPP `htdocs` directory.

Expected location:

```text
C:\xampp\htdocs\GCET-CampusConnect
```

### Step 3: Configure the Database

The documented database name is:

```text
gcet_campusconnect
```

1. Open phpMyAdmin.
2. Verify whether the database has already been created.
3. If necessary, create the database.
4. Import the SQL schema or database dump if one is provided.
5. Verify the connection settings in the application's configuration file.
6. Confirm that the configured database credentials match your local MySQL setup.

The project documentation indicates that database connectivity is configured by the application. Verify the actual setup before running it.

### Step 4: Launch the Application

Open:

```text
http://localhost/GCET-CampusConnect/
```

If Apache, MySQL, and the database are configured correctly, the application should load.

---

## 🔐 Demo Accounts

The existing project documentation lists the following local demonstration accounts.

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@gcet.ac.in` | `admin123` |
| IT Staff | `itstaff@gcet.ac.in` | `staff123` |
| Estate Manager | `estate@gcet.ac.in` | `staff123` |
| Student | `johndoe@student.gcet.ac.in` | `student123` |

**Security warning:** These are demonstration credentials, not production credentials. Use them only if they are configured in your local installation. Never use default passwords in a production environment.

Before publishing this README publicly, ensure these accounts are fictional test accounts and that no real user credentials are exposed.

---

## 🔒 Security Considerations

Security practices recommended for this application include:

- Use PDO prepared statements for database queries.
- Hash passwords securely.
- Validate and sanitize user input.
- Enforce role-based authorization on the server.
- Validate uploaded files and restrict file types and sizes.
- Protect sessions and sensitive operations.
- Keep database credentials and API keys out of source control.
- Avoid exposing sensitive information in error messages.

---

## 🚀 Future Enhancements

Potential enhancements include:

- Email notifications for request updates.
- Real-time communication.
- Advanced analytics and reporting dashboards.
- Improved request prioritization.
- Mobile-friendly interface enhancements.
- Service performance monitoring.
- Integration with additional campus services.

---

## 🤝 Contributing

Contributions and suggestions are welcome.

1. Fork the repository.
2. Create a feature branch.
3. Implement and test your changes.
4. Commit your changes with a descriptive message.
5. Open a pull request.

---

## 👨‍💻 Project Information

| Property | Details |
|---|---|
| Project Name | GCET CampusConnect |
| Institution | G. H. Patel College of Engineering & Technology |
| Location | Anand, Gujarat, India |
| Category | Web Development |
| Application Type | Campus Complaint and Service Portal |
| Backend | PHP |
| Database | MySQL / MariaDB |
| Repository | [GCET-CampusConnect](https://github.com/jayparikh-12/GCET-CampusConnect) |

---

## 📄 License

No license is specified in this documentation. Add a `LICENSE` file if you intend to distribute the project under an open-source license.

---

<p align="center">
  <strong>GCET CampusConnect</strong><br>
  Simplifying Campus Services Through Technology.
</p>