<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('student');
$user_id = get_user_id();

$stmt = $pdo->prepare("
    SELECT r.*, c.category_name, d.department_name
    FROM requests r
    LEFT JOIN categories c ON r.category_id = c.id
    LEFT JOIN departments d ON r.department_id = d.id
    WHERE r.user_id = ?
    ORDER BY r.submitted_at DESC
");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Requests</h1>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reference No.</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($requests)): ?>
                                    <tr><td colspan="7" class="text-center py-4">No requests found.</td></tr>
                                <?php else: ?>
                                    <?php foreach($requests as $r): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($r['reference_number']) ?></strong></td>
                                        <td><?= $r['request_type'] == 'complaint' ? '<span class="badge bg-danger">Complaint</span>' : '<span class="badge bg-primary">Service</span>' ?></td>
                                        <td><?= htmlspecialchars(substr($r['title'], 0, 30)) ?>...</td>
                                        <td><?= htmlspecialchars($r['department_name'] ?? 'Unassigned') ?></td>
                                        <td><?= format_date($r['submitted_at']) ?></td>
                                        <td><?= get_status_badge($r['status']) ?></td>
                                        <td><a href="complaint-details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
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
