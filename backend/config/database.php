<?php
/**
 * Database Configuration
 * Uses environment variables for Docker/cloud deployments, falls back to defaults for local development
 * Supports both MySQL and PostgreSQL
 */

// Database credentials - use environment variables if available (for Docker/Render), otherwise use defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'blog_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '64276629');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Database type: 'mysql' or 'pgsql' (PostgreSQL)
// Auto-detect from DB_HOST if it contains 'postgres' or 'postgresql', or starts with 'dpg-' (Render PostgreSQL)
// Otherwise default to mysql
$dbHostLower = strtolower(DB_HOST);
$envDbType = getenv('DB_TYPE');
if ($envDbType) {
    define('DB_TYPE', $envDbType);
} elseif (strpos($dbHostLower, 'postgres') !== false || strpos($dbHostLower, 'dpg-') === 0) {
    // Render PostgreSQL databases start with 'dpg-'
    define('DB_TYPE', 'pgsql');
} else {
    define('DB_TYPE', 'mysql');
}

/**
 * Get database connection
 * @return PDO|null
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            if (DB_TYPE === 'pgsql') {
                // PostgreSQL connection
                $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                // PostgreSQL uses port in connection string if provided
                $port = getenv('DB_PORT') ?: '5432';
                $dsn .= ";port=" . $port;
            } else {
                // MySQL connection
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            }
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            // Log connection attempt for debugging (remove in production)
            error_log("Attempting " . DB_TYPE . " connection to " . DB_HOST . " database " . DB_NAME);
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            error_log("Database connection successful (" . DB_TYPE . ")");
        } catch (PDOException $e) {
            error_log("Database connection failed (" . DB_TYPE . "): " . $e->getMessage());
            error_log("Connection details: host=" . DB_HOST . ", db=" . DB_NAME . ", user=" . DB_USER);
            return null;
        }
    }
    
    return $pdo;
}

