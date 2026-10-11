<?php
// config/database.php - Database Connection using PDO

require_once 'app.php';

$host = '127.0.0.1';
$db   = 'gcet_campuscare';
$user = 'root';
$pass = ''; // XAMPP default is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Important for security
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log the error and show a generic message.
    die("Database connection failed. Please check the database configuration.");
}
