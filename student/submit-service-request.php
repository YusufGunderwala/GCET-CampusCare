<?php
// We will duplicate submit-complaint logic for submit-service-request but adjust the request_type
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_role('student');

$cat_stmt = $pdo->query("SELECT id, category_name FROM categories WHERE status = 'active' ORDER BY category_name");
$categories = $cat_stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        set_flash_message('error', 'Invalid request form submission.');
        redirect('submit-service-request.php');
    }

    $title = sanitize_input($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $location = sanitize_input($_POST['location']);
    $description = sanitize_input($_POST['description']);
    $user_id = get_user_id();

    if (empty($title) || empty($category_id) || empty($description)) {
        set_flash_message('error', 'Required fields are missing.');
        redirect('submit-service-request.php');
    }

    $dept_id = null;
    $stmt_dept = $pdo->prepare("SELECT default_department_id FROM categories WHERE id = ?");
    $stmt_dept->execute([$category_id]);
    if ($row = $stmt_dept->fetch()) {
        $dept_id = $row['default_department_id'];
    }

    $ref_num = generate_reference_number($pdo, 'SRV');

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO requests (reference_number, user_id, request_type, title, description, category_id, department_id, location, priority) 
            VALUES (?, ?, 'service', ?, ?, ?, ?, ?, 'low')
        ");
        $stmt->execute([$ref_num, $user_id, $title, $description, $category_id, $dept_id, $location]);
        $request_id = $pdo->lastInsertId();

        $hist_stmt = $pdo->prepare("INSERT INTO request_status_history (request_id, new_status, changed_by, public_note) VALUES (?, 'submitted', ?, 'Service request submitted.')");
        $hist_stmt->execute([$request_id, $user_id]);

        $pdo->commit();
        set_flash_message('success', "Service Request submitted successfully. Ref: $ref_num");
        redirect("complaint-details.php?id=$request_id");

    } catch (Exception $e) {
        $pdo->rollBack();
        set_flash_message('error', 'Error submitting request: ' . $e->getMessage());
    }
}
?>
<?php require_once '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Request a Campus Service</h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-5">
                        <div class="card-body p-4">
                            <form action="submit-service-request.php" method="POST">
                                <?php csrf_field(); ?>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Service Required <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required placeholder="e.g. Need ID card renewal, Equipment for project">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Service Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category...</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Location (If applicable)</label>
                                        <input type="text" class="form-control" name="location" placeholder="e.g. Library Desk">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="4" required placeholder="Describe what service you need..."></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="dashboard.php" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-tools me-2"></i> Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
