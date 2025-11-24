<?php
/**
 * Database Connection Debug Page
 * Use this to check your database configuration
 * DELETE THIS FILE IN PRODUCTION!
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: text/plain');

echo "=== Database Configuration Debug ===\n\n";

// Helper function to check env vars from multiple sources
function checkEnvVar($key) {
    $value = getenv($key);
    if ($value !== false) return $value;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return null;
}

echo "Environment Variables:\n";
$dbHostEnv = checkEnvVar('DB_HOST');
echo "DB_HOST: " . ($dbHostEnv ?: 'NOT SET (using default: ' . DB_HOST . ')') . "\n";
$dbNameEnv = checkEnvVar('DB_NAME');
echo "DB_NAME: " . ($dbNameEnv ?: 'NOT SET (using default: ' . DB_NAME . ')') . "\n";
$dbUserEnv = checkEnvVar('DB_USER');
echo "DB_USER: " . ($dbUserEnv ?: 'NOT SET (using default: ' . DB_USER . ')') . "\n";
$dbPassEnv = checkEnvVar('DB_PASS');
echo "DB_PASS: " . ($dbPassEnv ? '***SET***' : 'NOT SET (using default)') . "\n";
$dbTypeEnv = checkEnvVar('DB_TYPE');
echo "DB_TYPE: " . ($dbTypeEnv ?: 'NOT SET (auto-detected: ' . DB_TYPE . ')') . "\n";
$dbPortEnv = checkEnvVar('DB_PORT');
echo "DB_PORT: " . ($dbPortEnv ?: 'NOT SET') . "\n";
$dbCharsetEnv = checkEnvVar('DB_CHARSET');
echo "DB_CHARSET: " . ($dbCharsetEnv ?: 'NOT SET (using default: ' . DB_CHARSET . ')') . "\n\n";

echo "All Environment Variables (from \$_ENV):\n";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'DB_') === 0) {
        echo "  $_ENV[$key] = " . (strpos($key, 'PASS') !== false ? '***' : $value) . "\n";
    }
}
echo "\n";

echo "Detected Configuration:\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_NAME: " . DB_NAME . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_TYPE: " . DB_TYPE . "\n";
echo "DB_CHARSET: " . DB_CHARSET . "\n\n";

echo "Connection Test:\n";
$pdo = getDBConnection();

if ($pdo) {
    echo "✓ Connection successful!\n\n";
    
    try {
        // Test query
        if (DB_TYPE === 'pgsql') {
            $stmt = $pdo->query("SELECT version()");
        } else {
            $stmt = $pdo->query("SELECT VERSION()");
        }
        $version = $stmt->fetchColumn();
        echo "Database Version: " . $version . "\n\n";
        
        // Check tables
        if (DB_TYPE === 'pgsql') {
            $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        } else {
            $stmt = $pdo->query("SHOW TABLES");
        }
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Tables found: " . count($tables) . "\n";
        foreach ($tables as $table) {
            echo "  - " . $table . "\n";
        }
        
        // Check admin user
        $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
        $adminCount = $stmt->fetchColumn();
        echo "\nAdmin users: " . $adminCount . "\n";
        
    } catch (PDOException $e) {
        echo "✗ Query failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Connection failed!\n";
    echo "Check the error logs for more details.\n";
}

echo "\n=== End Debug ===\n";
echo "\n⚠️  DELETE THIS FILE AFTER DEBUGGING!\n";

