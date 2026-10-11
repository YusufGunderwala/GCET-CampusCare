<?php
// config/app.php - Application Configuration

define('APP_NAME', 'GCET CampusCare');
define('APP_URL', 'http://localhost/Complaint%20System'); // Adjust if running in a different folder
define('APP_PATH', dirname(__DIR__));

// Security & Session Settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Error Reporting (Show errors during development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Upload Directory
define('UPLOAD_DIR', APP_PATH . '/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB

// Allowed file types
define('ALLOWED_MIME_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// Helper function to get base URL
function base_url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}
