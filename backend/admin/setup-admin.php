<?php
/**
 * Admin User Setup Script
 * Run this once to create/update the admin user
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();

if (!$pdo) {
    die("Error: Could not connect to database. Please check your database configuration in backend/config/database.php");
}

// Default admin credentials
$username = 'admin';
$password = 'admin123';
$email = 'admin@blog.com';

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        // Update existing user
        $stmt = $pdo->prepare("UPDATE admin_users SET password = :password, email = :email WHERE username = :username");
        $stmt->execute([
            ':password' => $hashedPassword,
            ':email' => $email,
            ':username' => $username
        ]);
        echo "✓ Admin user updated successfully!\n";
    } else {
        // Create new admin user
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':email' => $email
        ]);
        echo "✓ Admin user created successfully!\n";
    }
    
    echo "\n";
    echo "Admin credentials:\n";
    echo "Username: " . $username . "\n";
    echo "Password: " . $password . "\n";
    echo "\n";
    echo "You can now login at: http://localhost:8000/backend/admin/login.php\n";
    echo "\n";
    echo "⚠️  IMPORTANT: Delete this file (setup-admin.php) after use for security!\n";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}

