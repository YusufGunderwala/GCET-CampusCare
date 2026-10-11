<?php
// includes/functions.php - Global Utility Functions

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function redirect($url) {
    header("Location: " . base_url($url));
    exit;
}

function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message
    ];
}

function display_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $alert_type = $flash['type'] === 'error' ? 'danger' : $flash['type'];
        echo '<div class="alert alert-' . htmlspecialchars($alert_type) . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($flash['message']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['flash']);
    }
}

function format_date($timestamp) {
    return date('d M Y, h:i A', strtotime($timestamp));
}

function generate_reference_number($pdo, $type = 'CMP') {
    // Format: GCET-{TYPE}-{YEAR}-{0000}
    $year = date('Y');
    
    // Lock or atomic check would be ideal here in high concurrency
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM requests WHERE YEAR(submitted_at) = ? AND request_type = ?");
    $db_type = ($type == 'CMP') ? 'complaint' : 'service';
    $stmt->execute([$year, $db_type]);
    $result = $stmt->fetch();
    
    $sequence = intval($result['count']) + 1;
    $sequence_str = str_pad($sequence, 4, '0', STR_PAD_LEFT);
    
    return "GCET-{$type}-{$year}-{$sequence_str}";
}

function get_status_badge($status) {
    $badges = [
        'submitted' => '<span class="badge bg-secondary">Submitted</span>',
        'under_review' => '<span class="badge bg-info text-dark">Under Review</span>',
        'in_progress' => '<span class="badge bg-primary">In Progress</span>',
        'awaiting_response' => '<span class="badge bg-warning text-dark">Awaiting Response</span>',
        'resolved' => '<span class="badge bg-success">Resolved</span>',
        'closed' => '<span class="badge bg-dark">Closed</span>',
        'rejected' => '<span class="badge bg-danger">Rejected</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
