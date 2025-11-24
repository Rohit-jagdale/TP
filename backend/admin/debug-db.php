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

echo "Environment Variables:\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET (using default: ' . DB_HOST . ')') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'NOT SET (using default: ' . DB_NAME . ')') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'NOT SET (using default: ' . DB_USER . ')') . "\n";
echo "DB_PASS: " . (getenv('DB_PASS') ? '***SET***' : 'NOT SET (using default)') . "\n";
echo "DB_TYPE: " . (getenv('DB_TYPE') ?: 'NOT SET (auto-detected: ' . DB_TYPE . ')') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "DB_CHARSET: " . (getenv('DB_CHARSET') ?: 'NOT SET (using default: ' . DB_CHARSET . ')') . "\n\n";

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

