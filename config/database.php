<?php
// config/database.php - Database Connection using PDO

require_once 'app.php';

$host = '127.0.0.1';
$db   = 'gcet_campusconnect';
$fallback_db = 'gcet_campuscare';
$user = 'root';
$pass = ''; // XAMPP default is empty
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Important for security
];

// Ports to check: 3307 (MariaDB in XAMPP) and 3306 (standard MySQL/MariaDB)
$ports = [3307, 3306];
$pdo = null;
$lastException = null;

foreach ($ports as $port) {
    // 1. Try target database gcet_campusconnect
    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
        $pdo = new PDO($dsn, $user, $pass, $options);
        break;
    } catch (\PDOException $e) {
        $lastException = $e;
    }

    // 2. Try fallback database gcet_campuscare
    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$fallback_db};charset={$charset}";
        $pdo = new PDO($dsn, $user, $pass, $options);
        break;
    } catch (\PDOException $e) {
        $lastException = $e;
    }
}

if (!$pdo) {
    // If neither connected, show friendly message with hint
    $msg = "Database connection failed. Please ensure MySQL/MariaDB is running on port 3306 or 3307.";
    if (ini_get('display_errors')) {
        $msg .= " Details: " . ($lastException ? $lastException->getMessage() : "Unknown error");
    }
    die($msg);
}
