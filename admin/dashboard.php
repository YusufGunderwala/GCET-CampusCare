<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('admin');

// Dashboard statistics
$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM users WHERE role = 'student') as student_count,
        (SELECT COUNT(*) FROM users WHERE role = 'staff') as staff_count,
        (SELECT COUNT(*) FROM requests) as total_requests,
        (SELECT COUNT(*) FROM requests WHERE status = 'resolved') as resolved_requests,
        (SELECT COUNT(*) FROM requests WHERE status = 'submitted') as new_requests
");
$stats = $stmt->fetch();

// Recent requests
$req_stmt = $pdo->query("
    SELECT r.id, r.reference_number, r.title, r.status, r.submitted_at, d.department_name
    FROM requests r
    LEFT JOIN departments d ON r.department_id = d.id
    ORDER BY r.submitted_at DESC
    LIMIT 8
");
$recent_requests = $req_stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center">
                <h1 class="h2">Admin Dashboard</h1>
            </div>

            <!-- Stats row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Students</h6>
                            <h2 class="mb-0"><?= $stats['student_count'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Staff</h6>
                            <h2 class="mb-0"><?= $stats['staff_count'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark shadow-sm">
                        <div class="card-body">
                            <h6>Total Requests</h6>
                            <h2 class="mb-0"><?= $stats['total_requests'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <h6>Resolved Requests</h6>
                            <h2 class="mb-0"><?= $stats['resolved_requests'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Recent System Activity</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ref No</th>
                                            <th>Title</th>
                                            <th>Assigned Dept</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_requests as $r): ?>
                                        <tr>
                                            <td><strong><?= $r['reference_number'] ?></strong></td>
                                            <td><?= htmlspecialchars($r['title']) ?></td>
                                            <td><?= htmlspecialchars($r['department_name'] ?? 'Unassigned') ?></td>
                                            <td><?= format_date($r['submitted_at']) ?></td>
                                            <td><?= get_status_badge($r['status']) ?></td>
                                            <td><a href="<?= base_url('student/complaint-details.php?id='.$r['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
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
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
