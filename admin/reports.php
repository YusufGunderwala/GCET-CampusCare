<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

require_role('admin');

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    $filename = "gcet_campusconnect_report_" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Reference No', 'Type', 'Title', 'Category', 'Department', 'Priority', 'Status', 'Submitted By', 'Submitted At', 'Resolved At']);
    
    $query = "SELECT r.reference_number, r.request_type, r.title, c.category_name, d.department_name, r.priority, r.status, u.full_name as student_name, r.submitted_at, r.resolved_at 
              FROM requests r 
              LEFT JOIN categories c ON r.category_id = c.id 
              LEFT JOIN departments d ON r.department_id = d.id 
              LEFT JOIN users u ON r.user_id = u.id 
              ORDER BY r.submitted_at DESC";
    
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// For UI display
$stats_stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM requests) as total,
        (SELECT COUNT(*) FROM requests WHERE request_type = 'complaint') as complaints,
        (SELECT COUNT(*) FROM requests WHERE request_type = 'service') as services,
        (SELECT COUNT(*) FROM requests WHERE status = 'resolved') as resolved
");
$stats = $stats_stmt->fetch();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center">
                System Reports
                <a href="reports.php?export=csv" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
            </h1>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Total Requests</h6>
                            <h2 class="mb-0"><?= $stats['total'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Complaints</h6>
                            <h2 class="mb-0"><?= $stats['complaints'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Service Requests</h6>
                            <h2 class="mb-0"><?= $stats['services'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-1">Resolved</h6>
                            <h2 class="mb-0"><?= $stats['resolved'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 fw-bold">Generate Reports</div>
                <div class="card-body">
                    <p class="text-muted">Use the "Export CSV" button above to download a complete spreadsheet of all complaints and service requests in the system. The exported file includes full metadata such as submission dates, assigned departments, priorities, and final resolutions, which can be further analyzed using Excel or other tools.</p>
                </div>
            </div>

        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
