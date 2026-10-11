<?php
// includes/sidebar.php - Sidebar navigation based on role

$role = get_user_role();
$current_page = basename($_SERVER['PHP_SELF']);

// Define menus based on roles
$menus = [
    'student' => [
        ['url' => 'student/dashboard.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'student/submit-complaint.php', 'icon' => 'bi-exclamation-octagon', 'label' => 'New Complaint'],
        ['url' => 'student/submit-service-request.php', 'icon' => 'bi-tools', 'label' => 'New Service Request'],
        ['url' => 'student/my-requests.php', 'icon' => 'bi-list-ul', 'label' => 'My Requests'],
        ['url' => 'student/profile.php', 'icon' => 'bi-person-circle', 'label' => 'My Profile']
    ],
    'staff' => [
        ['url' => 'staff/dashboard.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'staff/assigned-requests.php', 'icon' => 'bi-inbox', 'label' => 'Assigned Requests'],
        ['url' => 'staff/profile.php', 'icon' => 'bi-person', 'label' => 'Profile']
    ],
    'admin' => [
        ['url' => 'admin/dashboard.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
        ['url' => 'admin/requests.php', 'icon' => 'bi-folder', 'label' => 'All Requests'],
        ['url' => 'admin/users.php', 'icon' => 'bi-people', 'label' => 'User Management'],
        ['url' => 'admin/departments.php', 'icon' => 'bi-building', 'label' => 'Departments'],
        ['url' => 'admin/categories.php', 'icon' => 'bi-tags', 'label' => 'Categories'],
        ['url' => 'admin/reports.php', 'icon' => 'bi-bar-chart', 'label' => 'Reports']
    ]
];

$active_menu = $menus[$role] ?? [];
?>

<div class="sidebar collapse d-md-block">
    <div class="position-sticky pt-2">
        <div class="user-profile-badge text-center mb-3 p-3 bg-light rounded-3 border">
            <div class="avatar-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white rounded-circle shadow-sm" style="width: 42px; height: 42px; background: var(--gcet-navy);">
                <i class="bi bi-person-fill fs-5" style="color: var(--gcet-accent);"></i>
            </div>
            <div class="fw-bold text-dark small text-truncate"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></div>
            <span class="badge bg-primary text-uppercase mt-1" style="font-size: 0.65rem;"><?= ucfirst($role) ?></span>
        </div>
        
        <ul class="nav flex-column mb-auto">
            <?php foreach ($active_menu as $item): ?>
                <?php 
                    // Simple logic to detect active state
                    $isActive = (strpos($_SERVER['REQUEST_URI'], $item['url']) !== false) ? 'active' : ''; 
                ?>
                <li class="nav-item">
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $isActive ?>">
                        <i class="bi <?= $item['icon'] ?> me-2"></i>
                        <?= $item['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <hr class="border-secondary mx-3">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('logout.php') ?>">
                    <i class="bi bi-box-arrow-left me-2"></i>
                    Sign out
                </a>
            </li>
        </ul>
    </div>
</div>
