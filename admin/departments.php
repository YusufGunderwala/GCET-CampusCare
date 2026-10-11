<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

require_role('admin');

$stmt = $pdo->query("SELECT * FROM departments ORDER BY id DESC");
$departments = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['department_name'];
    $code = $_POST['department_code'];
    $desc = $_POST['description'];

    $insert = $pdo->prepare("INSERT INTO departments (department_name, department_code, description) VALUES (?, ?, ?)");
    try {
        $insert->execute([$name, $code, $desc]);
    } catch(Exception $e) {}
    redirect('admin/departments.php');
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">Manage Departments</h1>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Add Department</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="department_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Code (e.g. IT01)</label>
                                    <input type="text" name="department_code" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
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
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($departments as $d): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['department_code']) ?></td>
                                        <td><?= htmlspecialchars($d['department_name']) ?></td>
                                        <td><?= $d['status'] ?></td>
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
