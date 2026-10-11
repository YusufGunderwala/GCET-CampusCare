<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('admin');

$stmt = $pdo->query("
    SELECT r.id, r.reference_number, r.title, r.request_type, r.status, r.submitted_at, d.department_name, u.full_name as student_name
    FROM requests r
    LEFT JOIN departments d ON r.department_id = d.id
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.submitted_at DESC
");
$requests = $stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">All Requests</h1>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ref No</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Student</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($requests)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No requests found in the system.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($requests as $r): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($r['reference_number']) ?></strong></td>
                                        <td><?= $r['request_type'] == 'complaint' ? '<span class="badge bg-danger">Complaint</span>' : '<span class="badge bg-primary">Service</span>' ?></td>
                                        <td><?= htmlspecialchars(substr($r['title'], 0, 30)) ?>...</td>
                                        <td><?= htmlspecialchars($r['student_name']) ?></td>
                                        <td><?= htmlspecialchars($r['department_name'] ?? 'Unassigned') ?></td>
                                        <td><?= format_date($r['submitted_at']) ?></td>
                                        <td><?= get_status_badge($r['status']) ?></td>
                                        <td>
                                            <a href="../student/complaint-details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
