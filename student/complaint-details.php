<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_login();
$user_id = get_user_id();
$role = get_user_role();
$request_id = $_GET['id'] ?? null;

if (!$request_id) {
    redirect("$role/dashboard.php");
}

// Fetch request with authorization check
$query = "
    SELECT r.*, c.category_name, d.department_name, u.full_name as student_name, s.full_name as staff_name
    FROM requests r
    LEFT JOIN categories c ON r.category_id = c.id
    LEFT JOIN departments d ON r.department_id = d.id
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN users s ON r.assigned_staff_id = s.id
    WHERE r.id = ?
";

// If student, they can only see their own requests
if ($role === 'student') {
    $query .= " AND r.user_id = ?";
}

$stmt = $pdo->prepare($query);
if ($role === 'student') {
    $stmt->execute([$request_id, $user_id]);
} else {
    $stmt->execute([$request_id]);
}

$request = $stmt->fetch();

if (!$request) {
    set_flash_message('error', 'Request not found or access denied.');
    redirect("$role/dashboard.php");
}

// Fetch history
$hist_stmt = $pdo->prepare("
    SELECT h.*, u.full_name, u.role
    FROM request_status_history h
    LEFT JOIN users u ON h.changed_by = u.id
    WHERE h.request_id = ?
    ORDER BY h.created_at ASC
");
$hist_stmt->execute([$request_id]);
$history = $hist_stmt->fetchAll();

// Fetch attachments
$att_stmt = $pdo->prepare("SELECT * FROM request_attachments WHERE request_id = ?");
$att_stmt->execute([$request_id]);
$attachments = $att_stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Request Details</h1>
                <?php if ($role === 'student'): ?>
                    <a href="my-requests.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold">
                                <?= htmlspecialchars($request['reference_number']) ?>
                            </h5>
                            <?= get_status_badge($request['status']) ?>
                        </div>
                        <div class="card-body">
                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($request['title']) ?></h4>
                            <p class="text-muted" style="white-space: pre-wrap;"><?= htmlspecialchars($request['description']) ?></p>
                            
                            <?php if (!empty($attachments)): ?>
                                <h6 class="fw-bold mt-4 mb-2"><i class="bi bi-paperclip"></i> Attachments</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach($attachments as $att): ?>
                                        <li class="list-group-item px-0">
                                            <!-- Note: Direct access to uploads might be blocked by .htaccess, ideally use a proxy script, but keeping simple for demo -->
                                            <a href="<?= base_url('uploads/' . $att['stored_filename']) ?>" target="_blank" class="text-decoration-none">
                                                <i class="bi bi-file-earmark-text me-2"></i><?= htmlspecialchars($att['original_filename']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Status History Timeline -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Status History</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($history as $h): ?>
                                    <li class="list-group-item px-0 py-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                Status changed to: <?= get_status_badge($h['new_status']) ?>
                                            </h6>
                                            <small class="text-muted"><?= format_date($h['created_at']) ?></small>
                                        </div>
                                        <p class="mb-1 text-muted small">Updated by <?= htmlspecialchars($h['full_name']) ?> (<?= ucfirst($h['role']) ?>)</p>
                                        <?php if (!empty($h['public_note'])): ?>
                                            <div class="alert alert-light border mt-2 mb-0 py-2 small">
                                                <strong>Note:</strong> <?= htmlspecialchars($h['public_note']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4 bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Metadata</h6>
                            
                            <dl class="row mb-0 small">
                                <dt class="col-sm-4 text-muted">Type</dt>
                                <dd class="col-sm-8 fw-bold">
                                    <?= ucfirst($request['request_type']) ?>
                                </dd>

                                <dt class="col-sm-4 text-muted">Category</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($request['category_name'] ?? 'N/A') ?></dd>

                                <dt class="col-sm-4 text-muted">Department</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($request['department_name'] ?? 'Unassigned') ?></dd>
                                
                                <dt class="col-sm-4 text-muted">Priority</dt>
                                <dd class="col-sm-8">
                                    <span class="text-uppercase fw-bold"><?= htmlspecialchars($request['priority']) ?></span>
                                </dd>

                                <dt class="col-sm-4 text-muted">Location</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($request['location'] ?? 'N/A') ?></dd>

                                <hr class="my-2">

                                <dt class="col-sm-4 text-muted">Submitted By</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($request['student_name']) ?></dd>
                                
                                <dt class="col-sm-4 text-muted">Submitted</dt>
                                <dd class="col-sm-8"><?= format_date($request['submitted_at']) ?></dd>
                                
                                <dt class="col-sm-4 text-muted">Assigned To</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($request['staff_name'] ?? 'Pending') ?></dd>
                            </dl>
                        </div>
                    </div>
                    
                    <?php if ($role === 'staff' || $role === 'admin'): ?>
                        <div class="card shadow-sm border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0 fw-bold">Update Status</h6>
                            </div>
                            <div class="card-body">
                                <form action="<?= base_url('api/update_status.php') ?>" method="POST">
                                    <?php csrf_field(); ?>
                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                    <div class="mb-3">
                                        <select class="form-select form-select-sm" name="status" required>
                                            <option value="under_review" <?= $request['status']=='under_review'?'selected':'' ?>>Under Review</option>
                                            <option value="in_progress" <?= $request['status']=='in_progress'?'selected':'' ?>>In Progress</option>
                                            <option value="awaiting_response" <?= $request['status']=='awaiting_response'?'selected':'' ?>>Awaiting Response</option>
                                            <option value="resolved" <?= $request['status']=='resolved'?'selected':'' ?>>Resolved</option>
                                            <option value="rejected" <?= $request['status']=='rejected'?'selected':'' ?>>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control form-control-sm" name="public_note" rows="2" placeholder="Public note (visible to student)..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
