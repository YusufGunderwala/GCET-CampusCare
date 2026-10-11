<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Public endpoint to search request status without logging in
$ref = sanitize_input($_GET['ref'] ?? '');
$request = null;
$searched = !empty($ref);

if ($searched) {
    $stmt = $pdo->prepare("
        SELECT r.id, r.reference_number, r.title, r.status, r.submitted_at, r.request_type, 
               c.category_name, d.department_name
        FROM requests r
        LEFT JOIN categories c ON r.category_id = c.id
        LEFT JOIN departments d ON r.department_id = d.id
        WHERE r.reference_number = ?
    ");
    $stmt->execute([$ref]);
    $request = $stmt->fetch();
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-page-wrapper py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                
                <div class="text-center mb-4">
                    <div class="auth-icon-badge mb-3 mx-auto">
                        <i class="bi bi-search"></i>
                    </div>
                    <h2 class="fw-bold mb-2" style="color: var(--brand-navy);">Track Your Request</h2>
                    <p class="text-muted small">Enter your complaint or service reference number (e.g. <code>GCET-CMP-2026-0001</code>) to view its live status.</p>
                </div>

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4 bg-white">
                    <div class="card-body p-4 p-md-5">
                        <form method="GET" action="track.php" class="d-flex flex-column flex-sm-row gap-2">
                            <div class="flex-grow-1 position-relative">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                                    <i class="bi bi-ticket-detailed fs-5"></i>
                                </span>
                                <input type="text" name="ref" class="form-control form-control-lg ps-5 bg-light" placeholder="e.g. GCET-CMP-2026-0001" value="<?= htmlspecialchars($ref) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">
                                <i class="bi bi-arrow-right-circle me-1"></i> Check Status
                            </button>
                        </form>
                    </div>
                </div>

                <?php if ($searched): ?>
                    <?php if ($request): ?>
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4 bg-white">
                            <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary text-uppercase fw-bold"><?= htmlspecialchars(strtoupper($request['request_type'])) ?></span>
                                <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i> Submitted: <?= format_date($request['submitted_at']) ?></span>
                            </div>
                            <div class="card-body p-4 text-center">
                                <h3 class="fw-bold mb-1" style="color: var(--brand-navy);"><?= htmlspecialchars($request['reference_number']) ?></h3>
                                <h5 class="text-muted mb-4"><?= htmlspecialchars($request['title']) ?></h5>
                                
                                <div class="p-3 bg-light rounded-3 mb-4 d-inline-block px-5 border">
                                    <div class="small text-muted text-uppercase fw-bold mb-1" style="letter-spacing: 0.05em;">Current Resolution Status</div>
                                    <div class="fs-4">
                                        <?= get_status_badge($request['status']) ?>
                                    </div>
                                </div>

                                <div class="row text-start g-3 mb-3 px-md-4">
                                    <div class="col-sm-6">
                                        <div class="p-2 border rounded bg-white">
                                            <small class="text-muted d-block">Department</small>
                                            <strong><?= htmlspecialchars($request['department_name'] ?? 'Not Assigned') ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="p-2 border rounded bg-white">
                                            <small class="text-muted d-block">Category</small>
                                            <strong><?= htmlspecialchars($request['category_name'] ?? 'General') ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info small mt-4 mb-0 text-start">
                                    <i class="bi bi-info-circle-fill me-1"></i> 
                                    For detailed case notes, staff replies, and resolution history, please <a href="login.php" class="fw-bold alert-link">Log In to your Student Portal</a>.
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger shadow-sm rounded-4 p-4 text-center">
                            <i class="bi bi-exclamation-triangle fs-1 d-block mb-2 text-danger"></i>
                            <h5 class="fw-bold">No Request Found</h5>
                            <p class="mb-0 text-muted">We couldn't find any request matching <strong><?= htmlspecialchars($ref) ?></strong>. Please double-check your reference ID format and try again.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
