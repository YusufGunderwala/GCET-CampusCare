<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

require_role('admin');

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();

$dept_stmt = $pdo->query("SELECT id, department_name FROM departments WHERE status='active'");
$departments = $dept_stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = $_POST['category_name'];
    $desc = $_POST['description'];
    $dept_id = !empty($_POST['default_department_id']) ? $_POST['default_department_id'] : null;

    $insert = $pdo->prepare("INSERT INTO categories (category_name, description, default_department_id) VALUES (?, ?, ?)");
    $insert->execute([$cat_name, $desc, $dept_id]);
    redirect('admin/categories.php');
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Manage Categories</h1>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Add Category</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Category Name</label>
                                    <input type="text" name="category_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Default Department</label>
                                    <select name="default_department_id" class="form-select">
                                        <option value="">None</option>
                                        <?php foreach($departments as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= $d['department_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $c): ?>
                                    <tr>
                                        <td><?= $c['id'] ?></td>
                                        <td><?= htmlspecialchars($c['category_name']) ?></td>
                                        <td><?= $c['status'] ?></td>
                                        <td><button class="btn btn-sm btn-outline-secondary" disabled>Edit</button></td>
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
<?php require_once '../includes/footer.php'; ?>
