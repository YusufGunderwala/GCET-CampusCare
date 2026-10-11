<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('staff');
$user_id = get_user_id();

// Get the staff's department
$stmt = $pdo->prepare("SELECT department_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$staff = $stmt->fetch();
$dept_id = $staff['department_id'];

// Get department requests
$req_stmt = $pdo->prepare("
    SELECT r.id, r.reference_number, r.title, r.priority, r.status, r.submitted_at, u.full_name as student_name
    FROM requests r
    JOIN users u ON r.user_id = u.id
    WHERE r.department_id = ?
    ORDER BY r.submitted_at DESC
");
$req_stmt->execute([$dept_id]);
$requests = $req_stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Assigned Requests</h1>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ref No</th>
                                    <th>Title</th>
                                    <th>Student</th>
                                    <th>Priority</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($requests)): ?>
                                    <tr><td colspan="7" class="text-center py-4">No requests assigned to this department.</td></tr>
                                <?php endif; ?>
                                <?php foreach($requests as $r): ?>
                                <tr>
                                    <td><strong><?= $r['reference_number'] ?></strong></td>
                                    <td><?= htmlspecialchars(substr($r['title'], 0, 40)) ?></td>
                                    <td><?= htmlspecialchars($r['student_name']) ?></td>
                                    <td><span class="badge bg-secondary"><?= ucfirst($r['priority']) ?></span></td>
                                    <td><?= format_date($r['submitted_at']) ?></td>
                                    <td><?= get_status_badge($r['status']) ?></td>
                                    <td><a href="<?= base_url('student/complaint-details.php?id='.$r['id']) ?>" class="btn btn-sm btn-outline-primary">Manage</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
