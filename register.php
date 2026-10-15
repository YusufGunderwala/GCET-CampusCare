<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

// Only guests can register
if (is_logged_in()) {
    redirect(get_user_role() . '/dashboard.php');
}

// Ensure all official GCET academic departments are present in the database
$official_gcet_departments = [
    ['Chemical Engineering', 'CHE', 'Department of Chemical Engineering'],
    ['Information Technology', 'IT', 'Department of Information Technology'],
    ['Computer Science and Engineering (IOT)', 'CSE-IOT', 'Department of Computer Science and Engineering (IOT)'],
    ['Mechanical Engineering', 'ME', 'Department of Mechanical Engineering'],
    ['Electronics & Communication', 'EC', 'Department of Electronics & Communication Engineering'],
    ['Information & Communication Technology', 'ICT', 'Department of Information & Communication Technology'],
    ['Mechatronics Engineering', 'MC', 'Department of Mechatronics Engineering'],
    ['Computer Engineering', 'CP', 'Department of Computer Engineering'],
    ['Computer Science and Design', 'CSD', 'Department of Computer Science and Design'],
    ['Electrical Engineering', 'EE', 'Department of Electrical Engineering'],
    ['Civil Engineering', 'CL', 'Department of Civil Engineering'],
    ['Applied Science & Humanities', 'ASH', 'Department of Applied Science & Humanities']
];

foreach ($official_gcet_departments as $dept) {
    try {
        $chk = $pdo->prepare("SELECT id FROM departments WHERE department_name = ?");
        $chk->execute([$dept[0]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO departments (department_name, department_code, description, status) VALUES (?, ?, ?, 'active')");
            $ins->execute([$dept[0], $dept[1], $dept[2]]);
        }
    } catch (Exception $e) {
        // Fallback silently if table not ready
    }
}

// Fetch all active departments for registration dropdown
$dept_stmt = $pdo->query("SELECT id, department_name FROM departments WHERE status = 'active' ORDER BY department_name ASC");
$departments = $dept_stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        set_flash_message('error', 'Invalid security token. Please try again.');
        redirect('register.php');
    }

    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $student_id = sanitize_input($_POST['student_id'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $department_id = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null;
    $semester = !empty($_POST['semester']) ? (int)$_POST['semester'] : null;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    if (empty($full_name) || empty($student_id) || empty($email) || empty($password)) {
        $errors[] = 'Please fill in all mandatory fields marked with an asterisk (*).';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    // Check if email or student ID exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR student_id = ?");
        $stmt->execute([$email, $student_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'An account with this Email or Student ID already exists.';
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $insert = $pdo->prepare("
            INSERT INTO users (full_name, student_id, email, phone, department_id, semester, password_hash, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'student')
        ");
        
        if ($insert->execute([$full_name, $student_id, $email, $phone, $department_id, $semester, $password_hash])) {
            set_flash_message('success', 'Registration successful! You may now sign in with your credentials.');
            redirect('login.php');
        } else {
            set_flash_message('error', 'Unable to complete registration. Please try again.');
        }
    } else {
        foreach ($errors as $error) {
            set_flash_message('error', $error);
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-page-wrapper py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                
                <div class="card auth-card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="auth-card-header text-center pt-5 pb-3 px-4">
                        <div class="auth-icon-badge mb-3 mx-auto">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: var(--brand-navy); font-size: 1.7rem;">Student Registration</h3>
                        <p class="text-muted small mb-0">Join G. H. Patel College of Engineering & Technology CampusConnect</p>
                    </div>

                    <div class="card-body p-4 p-md-5 pt-3">
                        <form method="POST" action="register.php">
                            <?php csrf_field(); ?>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="full_name" placeholder="e.g. Rahul Patel" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Student ID / Enrollment <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-card-heading"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="student_id" placeholder="e.g. 12001011001" required value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        College / Student Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0" name="email" placeholder="e.g. student@gcet.ac.in" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Phone Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" class="form-control border-start-0 ps-0" name="phone" placeholder="+91 98765 43210" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                        <select class="form-select border-start-0 ps-0" name="department_id" required>
                                            <option value="">Select Your Department...</option>
                                            <?php foreach ($departments as $dept): ?>
                                                <option value="<?= $dept['id'] ?>" <?= (isset($_POST['department_id']) && $_POST['department_id'] == $dept['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($dept['department_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Semester (1 - 8) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                                        <select class="form-select border-start-0 ps-0" name="semester" required>
                                            <option value="">Select Current Semester...</option>
                                            <option value="1" <?= (isset($_POST['semester']) && $_POST['semester'] == '1') ? 'selected' : '' ?>>Semester 1 (1st Year)</option>
                                            <option value="2" <?= (isset($_POST['semester']) && $_POST['semester'] == '2') ? 'selected' : '' ?>>Semester 2 (1st Year)</option>
                                            <option value="3" <?= (isset($_POST['semester']) && $_POST['semester'] == '3') ? 'selected' : '' ?>>Semester 3 (2nd Year)</option>
                                            <option value="4" <?= (isset($_POST['semester']) && $_POST['semester'] == '4') ? 'selected' : '' ?>>Semester 4 (2nd Year)</option>
                                            <option value="5" <?= (isset($_POST['semester']) && $_POST['semester'] == '5') ? 'selected' : '' ?>>Semester 5 (3rd Year)</option>
                                            <option value="6" <?= (isset($_POST['semester']) && $_POST['semester'] == '6') ? 'selected' : '' ?>>Semester 6 (3rd Year)</option>
                                            <option value="7" <?= (isset($_POST['semester']) && $_POST['semester'] == '7') ? 'selected' : '' ?>>Semester 7 (4th Year)</option>
                                            <option value="8" <?= (isset($_POST['semester']) && $_POST['semester'] == '8') ? 'selected' : '' ?>>Semester 8 (4th Year)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0" name="password" placeholder="At least 6 characters" required minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0" name="confirm_password" placeholder="Repeat your password" required minlength="6">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm py-3">
                                    <i class="bi bi-person-check-fill me-2"></i> Register Student Account
                                </button>
                            </div>
                            
                            <div class="text-center pt-2 border-top">
                                <p class="mb-0 text-muted small">
                                    Already have a student account? <a href="login.php" class="fw-bold text-decoration-none" style="color: var(--brand-blue);">Sign In Here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3 mb-4">
                    <a href="index.php" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
