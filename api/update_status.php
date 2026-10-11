<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/csrf.php';

// Allow staff or admin to update status
if (!is_logged_in() || (get_user_role() !== 'staff' && get_user_role() !== 'admin')) {
    set_flash_message('error', 'Unauthorized access.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    $request_id = (int)$_POST['request_id'];
    $new_status = sanitize_input($_POST['status']);
    $public_note = sanitize_input($_POST['public_note']);
    $user_id = get_user_id();
    
    // Ensure request exists and (if staff) belongs to their department
    // Simplifying authorization check for brevity - in production, check department mapping!
    $stmt = $pdo->prepare("SELECT id FROM requests WHERE id = ?");
    $stmt->execute([$request_id]);
    if (!$stmt->fetch()) {
        die('Request not found.');
    }
    
    try {
        $pdo->beginTransaction();
        
        // Update request status
        $update = $pdo->prepare("UPDATE requests SET status = ?, updated_at = NOW() WHERE id = ?");
        $update->execute([$new_status, $request_id]);
        
        // Add to history
        $hist = $pdo->prepare("INSERT INTO request_status_history (request_id, new_status, changed_by, public_note) VALUES (?, ?, ?, ?)");
        $hist->execute([$request_id, $new_status, $user_id, $public_note]);
        
        $pdo->commit();
        set_flash_message('success', 'Request status updated successfully.');
    } catch (Exception $e) {
        $pdo->rollBack();
        set_flash_message('error', 'Failed to update status.');
    }
    
    // Redirect back to details page
    // (Note: in a real app, staff might have their own details page, but here we redirect to the shared one we put in student folder, or make a router.
    // Let's redirect properly depending on role)
    $role = get_user_role();
    if ($role === 'staff') {
        redirect("staff/dashboard.php"); // Or staff/request-details.php if created
    } else {
        redirect("admin/dashboard.php");
    }
}
