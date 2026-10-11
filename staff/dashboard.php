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

// Get counts
$stats_stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status IN ('submitted', 'under_review') THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
    FROM requests WHERE department_id = ?
");
$stats_stmt->execute([$dept_id]);
$stats = $stats_stmt->fetch();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Staff Dashboard (Department Queue)</h1>
            </div>

            <!-- Stats -->
            <div class="row mb-4 text-center">
                <div class="col-md-3"><div class="card bg-primary text-white"><div class="card-body"><h5>Total</h5><h2><?= intval($stats['total']) ?></h2></div></div></div>
                <div class="col-md-3"><div class="card bg-warning text-dark"><div class="card-body"><h5>Pending</h5><h2><?= intval($stats['pending']) ?></h2></div></div></div>
                <div class="col-md-3"><div class="card bg-info text-white"><div class="card-body"><h5>In Progress</h5><h2><?= intval($stats['in_progress']) ?></h2></div></div></div>
                <div class="col-md-3"><div class="card bg-success text-white"><div class="card-body"><h5>Resolved</h5><h2><?= intval($stats['resolved']) ?></h2></div></div></div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 fw-bold">Department Requests</div>
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
