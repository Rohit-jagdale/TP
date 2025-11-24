<?php
/**
 * Admin Dashboard
 */

require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pdo = getDBConnection();

if (!$pdo) {
    die('Database connection failed. Please check your database configuration.');
}

// Get statistics
$totalPosts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$publishedPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$draftPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Get recent posts
$recentPosts = getPosts(null, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 24px; }
        .header a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: #e74c3c;
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }
        .nav-links {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        .nav-links a {
            padding: 12px 24px;
            background: white;
            text-decoration: none;
            color: #2c3e50;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .nav-links a:hover {
            transform: translateY(-2px);
        }
        .recent-posts {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .recent-posts h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status.published {
            background: #d4edda;
            color: #155724;
        }
        .status.draft {
            background: #fff3cd;
            color: #856404;
        }
        .actions a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1><?php echo APP_NAME; ?> Admin</h1>
            <div>
                <span>Welcome, <?php echo escape($_SESSION['admin_username']); ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>Total Posts</h3>
                <div class="number"><?php echo $totalPosts; ?></div>
            </div>
            <div class="stat-card">
                <h3>Published</h3>
                <div class="number"><?php echo $publishedPosts; ?></div>
            </div>
            <div class="stat-card">
                <h3>Drafts</h3>
                <div class="number"><?php echo $draftPosts; ?></div>
            </div>
            <div class="stat-card">
                <h3>Categories</h3>
                <div class="number"><?php echo $totalCategories; ?></div>
            </div>
        </div>
        
        <div class="nav-links">
            <a href="posts.php">Manage Posts</a>
            <a href="categories.php">Manage Categories</a>
            <a href="../.." target="_blank">View Site</a>
        </div>
        
        <div class="recent-posts">
            <h2>Recent Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentPosts)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">No posts yet</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td><?php echo escape($post['title']); ?></td>
                                <td>
                                    <span class="status <?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($post['created_at']); ?></td>
                                <td class="actions">
                                    <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>">Edit</a>
                                    <a href="posts.php?action=delete&id=<?php echo $post['id']; ?>" 
                                       onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

