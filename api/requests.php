<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/auth.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = get_user_id();
$role = get_user_role();
$status = $_GET['status'] ?? null;

$query = "SELECT id, reference_number, title, status, submitted_at FROM requests";
$params = [];

if ($role === 'student') {
    $query .= " WHERE user_id = ?";
    $params[] = $user_id;
} elseif ($role === 'staff') {
    $stmt = $pdo->prepare("SELECT department_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $staff = $stmt->fetch();
    $query .= " WHERE department_id = ?";
    $params[] = $staff['department_id'];
} else {
    $query .= " WHERE 1=1";
}

if ($status) {
    $query .= " AND status = ?";
    $params[] = $status;
}

$query .= " ORDER BY submitted_at DESC LIMIT 50";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'success',
    'count' => count($requests),
    'data' => $requests
]);
