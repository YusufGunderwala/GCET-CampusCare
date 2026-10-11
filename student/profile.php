<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_login();
$user_id = get_user_id();

$stmt = $pdo->prepare("SELECT full_name, student_id, email, phone, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('CSRF validation failed');
    }
    
    $phone = sanitize_input($_POST['phone']);
    $update = $pdo->prepare("UPDATE users SET phone = ? WHERE id = ?");
    $update->execute([$phone, $user_id]);
    
    set_flash_message('success', 'Profile updated successfully.');
    redirect(get_user_role() . '/profile.php');
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">My Profile</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST">
                                <?php csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                </div>
                                <?php if($user['student_id']): ?>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Student ID</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['student_id']) ?>" disabled>
                                </div>
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
