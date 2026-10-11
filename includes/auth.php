<?php
// includes/auth.php - Authentication Logic

session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_role() {
    return $_SESSION['role'] ?? null;
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function require_role($required_role) {
    require_login();
    if (get_user_role() !== $required_role) {
        // Redirect to their respective dashboard if they try to access wrong area
        if (get_user_role() === 'admin') redirect('admin/dashboard.php');
        if (get_user_role() === 'staff') redirect('staff/dashboard.php');
        if (get_user_role() === 'student') redirect('student/dashboard.php');
        redirect('index.php');
    }
}
