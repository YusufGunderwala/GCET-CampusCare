<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

// If already logged in, redirect to respective dashboard
if (is_logged_in()) {
    $role = get_user_role();
    redirect("$role/dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        set_flash_message('error', 'Invalid request session. Please try again.');
        redirect('login.php');
    }

    $login_id = sanitize_input($_POST['login_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login_id) || empty($password)) {
        set_flash_message('error', 'Please enter your Email / Student ID and password.');
    } else {
        // Find user by email or student_id
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR student_id = ? LIMIT 1");
        $stmt->execute([$login_id, $login_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['account_status'] === 'inactive') {
                set_flash_message('warning', 'Your account is pending administrator activation.');
            } elseif ($user['account_status'] === 'suspended') {
                set_flash_message('error', 'Your account has been suspended. Contact administrator.');
            } else {
                // Login successful
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                set_flash_message('success', 'Welcome back, ' . $user['full_name'] . '!');
                redirect("{$user['role']}/dashboard.php");
            }
        } else {
            set_flash_message('error', 'Invalid email/ID or password. Please verify your credentials.');
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-page-wrapper py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                
                <!-- Main Login Card -->
                <div class="card auth-card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="auth-card-header text-center pt-5 pb-3 px-4">
                        <div class="auth-icon-badge mb-3 mx-auto">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: var(--brand-navy); font-size: 1.6rem;">Welcome Back</h3>
                        <p class="text-muted small mb-0">Sign in to GCET CampusCare Portal</p>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <form method="POST" action="login.php" id="loginForm">
                            <?php csrf_field(); ?>
                            
                            <div class="mb-3">
                                <label for="login_id" class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: var(--text-main);">
                                    Email or Student ID
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="login_id" name="login_id" placeholder="e.g. admin@gcet.ac.in or 12001011001" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label fw-semibold small text-uppercase mb-0" style="letter-spacing: 0.05em; color: var(--text-main);">
                                        Password
                                    </label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary border-start-0 bg-light text-muted" type="button" id="togglePassword" title="Show/Hide Password">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                                </button>
                            </div>
                            
                            <div class="text-center pt-2 border-top">
                                <p class="mb-0 text-muted small">
                                    Don't have an account? <a href="register.php" class="fw-bold text-decoration-none" style="color: var(--brand-blue);">Register as Student</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Evaluator / Demo Quick-Login Helper -->
                <div class="card mt-4 border-0 shadow-sm rounded-4 bg-white p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Demo Logins
                        </span>
                        <span class="badge bg-light text-secondary border">Click to auto-fill</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill fw-semibold py-2" onclick="fillLogin('admin@gcet.ac.in', 'admin123')">
                            <i class="bi bi-shield-check me-1"></i> Admin
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info flex-fill fw-semibold py-2" onclick="fillLogin('itstaff@gcet.ac.in', 'staff123')">
                            <i class="bi bi-person-badge me-1"></i> Staff
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success flex-fill fw-semibold py-2" onclick="fillLogin('johndoe@student.gcet.ac.in', 'student123')">
                            <i class="bi bi-mortarboard me-1"></i> Student
                        </button>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

function fillLogin(email, pass) {
    document.getElementById('login_id').value = email;
    document.getElementById('password').value = pass;
    document.getElementById('login_id').focus();
}
</script>

<?php require_once 'includes/footer.php'; ?>
