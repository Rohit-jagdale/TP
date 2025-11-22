<?php
/**
 * Application Configuration
 * Blog Backend Configuration File
 */

// Application settings
define('APP_NAME', 'Blog');
define('APP_VERSION', '1.0.0');
define('BASE_URL', '/');

// Paths
define('ROOT_PATH', dirname(dirname(__DIR__)) . '/');
define('BACKEND_PATH', ROOT_PATH . 'backend/');
define('UPLOAD_PATH', ROOT_PATH . 'uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Timezone
date_default_timezone_set('UTC');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

