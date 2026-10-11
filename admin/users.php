<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

require_role('admin');

$stmt = $pdo->query("SELECT u.id, u.full_name, u.email, u.role, u.account_status, d.department_name FROM users u LEFT JOIN departments d ON u.department_id = d.id ORDER BY u.id DESC");
$users = $stmt->fetchAll();

?>
<?php require_once '../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="main-content">
            <h1 class="h2 pt-3 pb-2 mb-3 border-bottom">User Management</h1>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table mb-0 table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['full_name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><span class="badge bg-secondary"><?= ucfirst($u['role']) ?></span></td>
                                <td><?= htmlspecialchars($u['department_name'] ?? '-') ?></td>
                                <td><?= $u['account_status'] === 'active' ? '<span class="text-success">Active</span>' : '<span class="text-danger">'.$u['account_status'].'</span>' ?></td>
                                <td><button class="btn btn-sm btn-outline-primary" disabled>Manage</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
