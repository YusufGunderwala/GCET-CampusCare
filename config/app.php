<?php
// config/app.php - Application Configuration

define('APP_NAME', 'GCET CampusConnect');

// Dynamically determine the application base URL
if (!defined('APP_URL')) {
    if (isset($_SERVER['HTTP_HOST'])) {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $protocol = $isHttps ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $parts = array_values(array_filter(explode('/', trim($scriptDir, '/'))));
        $projectFolder = !empty($parts[0]) ? '/' . $parts[0] : '';
        
        define('APP_URL', $protocol . $host . $projectFolder);
    } else {
        define('APP_URL', 'http://localhost/GCET-CampusConnect');
    }
}

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
