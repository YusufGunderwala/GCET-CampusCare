<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - G. H. Patel College of Engineering & Technology</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100 antialiased">
    
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center text-white" href="<?= base_url('index.php') ?>">
        <div class="brand-icon me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-mortarboard-fill fs-4" style="color: var(--brand-accent);"></i>
        </div>
        <div class="d-flex flex-column">
            <span class="fw-bold lh-1" style="font-size: 1.15rem; letter-spacing: -0.3px;">GCET CampusConnect</span>
            <span class="text-white-50 small" style="font-size: 0.7rem; letter-spacing: 0.5px;">G. H. PATEL COLLEGE OF ENG. & TECH.</span>
        </div>
    </a>
    <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-2 text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('index.php') ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('about.php') ?>">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('track.php') ?>">Track Request</a>
        </li>
        
        <?php if (is_logged_in()): ?>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                <?php $dashboard_link = base_url(get_user_role() . '/dashboard.php'); ?>
                <a class="btn btn-accent btn-sm px-3 fw-bold shadow-sm" href="<?= $dashboard_link ?>">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </li>
            <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                <a class="btn btn-outline-light btn-sm px-3" href="<?= base_url('logout.php') ?>">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                <a class="btn btn-outline-light btn-sm px-3 fw-semibold" href="<?= base_url('login.php') ?>">Sign In</a>
            </li>
            <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                <a class="btn btn-accent btn-sm px-3 fw-bold shadow-sm" href="<?= base_url('register.php') ?>">Student Register</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php if (isset($_SESSION['flash'])): ?>
<div class="container mt-3">
    <?php display_flash_message(); ?>
</div>
<?php endif; ?>

<main class="flex-grow-1">
