<?php
/**
 * Database Setup Script
 * This script will create all necessary tables in your PostgreSQL database
 * DELETE THIS FILE AFTER USE FOR SECURITY!
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: text/plain');

echo "=== Database Setup Script ===\n\n";

$pdo = getDBConnection();

if (!$pdo) {
    die("✗ Database connection failed! Please check your configuration.\n");
}

echo "✓ Database connection successful!\n";
echo "Database Type: " . DB_TYPE . "\n";
echo "Database: " . DB_NAME . "\n\n";

// Read the schema file
$schemaFile = __DIR__ . '/../../database/schema-postgres.sql';

if (!file_exists($schemaFile)) {
    die("✗ Schema file not found: $schemaFile\n");
}

echo "Reading schema file: $schemaFile\n\n";

$schema = file_get_contents($schemaFile);

try {
    // PostgreSQL can execute multiple statements, but we need to handle the function definition carefully
    // The schema file uses $$ delimiters which PostgreSQL handles correctly
    
    echo "--- Executing schema ---\n";
    
    // Execute the entire schema file
    // PostgreSQL's exec() can handle multiple statements separated by semicolons
    // The $$ delimiters in the function definition are handled correctly by PostgreSQL
    $pdo->exec($schema);
    
    echo "✓ Schema executed successfully!\n\n";
    
    // Verify tables were created
    echo "--- Verifying tables ---\n";
    $tables = ['categories', 'posts', 'post_categories', 'admin_users'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "✓ Table '$table' exists (rows: $count)\n";
        } catch (PDOException $e) {
            echo "✗ Table '$table' does not exist or error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== Setup Complete ===\n";
    echo "✓ Database setup finished!\n";
    echo "⚠️  DELETE THIS FILE (setup-database.php) FOR SECURITY!\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error executing schema: " . $e->getMessage() . "\n";
    echo "\nYou may need to run the schema manually using Render's psql console.\n";
}

