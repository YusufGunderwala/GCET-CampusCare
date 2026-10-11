<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('student');

$user_id = get_user_id();

// Fetch summary stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
        SUM(CASE WHEN status IN ('under_review', 'in_progress', 'awaiting_response') THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
    FROM requests WHERE user_id = ?
");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

// Fetch recent requests
$stmt_recent = $pdo->prepare("
    SELECT r.id, r.reference_number, r.title, r.request_type, r.status, r.submitted_at, c.category_name
    FROM requests r
    LEFT JOIN categories c ON r.category_id = c.id
    WHERE r.user_id = ?
    ORDER BY r.submitted_at DESC
    LIMIT 5
");
$stmt_recent->execute([$user_id]);
$recent_requests = $stmt_recent->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Student Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="submit-complaint.php" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> New Complaint</a>
                        <a href="submit-service-request.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-tools"></i> New Service</a>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-primary shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Total Requests</h6>
                            <h2 class="mb-0"><?= intval($stats['total']) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-warning shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Pending / Submitted</h6>
                            <h2 class="mb-0"><?= intval($stats['submitted']) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-info shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">In Progress</h6>
                            <h2 class="mb-0"><?= intval($stats['active']) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">
                    <div class="card text-white bg-success shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Resolved</h6>
                            <h2 class="mb-0"><?= intval($stats['resolved']) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Requests</h5>
                    <a href="my-requests.php" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recent_requests)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No requests submitted yet.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($recent_requests as $req): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($req['reference_number']) ?></strong></td>
                                        <td>
                                            <?php if($req['request_type'] == 'complaint'): ?>
                                                <span class="badge bg-danger">Complaint</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Service</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars(substr($req['title'], 0, 40)) . (strlen($req['title']) > 40 ? '...' : '') ?></td>
                                        <td><?= htmlspecialchars($req['category_name'] ?? 'General') ?></td>
                                        <td><?= format_date($req['submitted_at']) ?></td>
                                        <td><?= get_status_badge($req['status']) ?></td>
                                        <td>
                                            <a href="complaint-details.php?id=<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
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
