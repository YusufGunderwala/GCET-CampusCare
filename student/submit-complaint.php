<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

require_role('student');

// Fetch categories for dropdown
$cat_stmt = $pdo->query("SELECT id, category_name FROM categories WHERE status = 'active' ORDER BY category_name");
$categories = $cat_stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        set_flash_message('error', 'Invalid request form submission.');
        redirect('submit-complaint.php');
    }

    $title = sanitize_input($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $location = sanitize_input($_POST['location']);
    $priority = $_POST['priority'] ?? 'medium';
    $description = sanitize_input($_POST['description']);
    $user_id = get_user_id();

    // Basic validation
    if (empty($title) || empty($category_id) || empty($description)) {
        set_flash_message('error', 'Title, category, and description are required.');
        redirect('submit-complaint.php');
    }

    // Determine default department based on category
    $dept_id = null;
    $stmt_dept = $pdo->prepare("SELECT default_department_id FROM categories WHERE id = ?");
    $stmt_dept->execute([$category_id]);
    if ($row = $stmt_dept->fetch()) {
        $dept_id = $row['default_department_id'];
    }

    $ref_num = generate_reference_number($pdo, 'CMP');

    try {
        $pdo->beginTransaction();

        // Insert complaint
        $stmt = $pdo->prepare("
            INSERT INTO requests (reference_number, user_id, request_type, title, description, category_id, department_id, location, priority) 
            VALUES (?, ?, 'complaint', ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$ref_num, $user_id, $title, $description, $category_id, $dept_id, $location, $priority]);
        $request_id = $pdo->lastInsertId();

        // Log history
        $hist_stmt = $pdo->prepare("INSERT INTO request_status_history (request_id, new_status, changed_by, public_note) VALUES (?, 'submitted', ?, 'Complaint submitted.')");
        $hist_stmt->execute([$request_id, $user_id]);

        // Handle file upload
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['attachment'];
            
            // Validate size
            if ($file['size'] > MAX_UPLOAD_SIZE) {
                throw new Exception("File size exceeds 5MB limit.");
            }
            
            // Validate MIME type safely
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime_type, ALLOWED_MIME_TYPES)) {
                throw new Exception("Invalid file type. Only images and documents are allowed.");
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $stored_filename = uniqid('req_') . '_' . time() . '.' . $ext;
            
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $stored_filename)) {
                $attach_stmt = $pdo->prepare("INSERT INTO request_attachments (request_id, stored_filename, original_filename, mime_type, file_size, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
                $attach_stmt->execute([$request_id, $stored_filename, $file['name'], $mime_type, $file['size'], $user_id]);
            } else {
                throw new Exception("Failed to save the uploaded file.");
            }
        }

        $pdo->commit();
        set_flash_message('success', "Complaint submitted successfully. Reference Number: $ref_num");
        redirect("complaint-details.php?id=$request_id");

    } catch (Exception $e) {
        $pdo->rollBack();
        set_flash_message('error', 'Error submitting complaint: ' . $e->getMessage());
    }
}
?>
<?php require_once '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Submit a Complaint</h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-5">
                        <div class="card-body p-4">
                            <form action="submit-complaint.php" method="POST" enctype="multipart/form-data">
                                <?php csrf_field(); ?>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Complaint Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required placeholder="Brief summary of the issue">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category...</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Priority</label>
                                        <select class="form-select" name="priority">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Location / Room Number</label>
                                    <input type="text" class="form-control" name="location" placeholder="e.g. Lab 3, Block A">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Detailed Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="5" required placeholder="Provide all necessary details to help resolve the issue quickly..."></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Attachment (Optional)</label>
                                    <input type="file" class="form-control" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <div class="form-text">Max size: 5MB. Allowed types: JPG, PNG, PDF, DOC, DOCX.</div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="dashboard.php" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i> Submit Complaint</button>
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
