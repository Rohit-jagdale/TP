<?php
/**
 * Category Management
 */

require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pdo = getDBConnection();
if (!$pdo) {
    die('Database connection failed. Please check your database configuration.');
}

$message = '';
$messageType = '';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    if ($stmt->execute([':id' => $id])) {
        $message = 'Category deleted successfully';
        $messageType = 'success';
    } else {
        $message = 'Error deleting category';
        $messageType = 'error';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (!empty($name)) {
        $slug = slugify($name);
        
        if ($id) {
            // Update existing category
            $stmt = $pdo->prepare("
                UPDATE categories 
                SET name = :name, slug = :slug, description = :description, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description
            ]);
            $message = 'Category updated successfully';
        } else {
            // Create new category
            $stmt = $pdo->prepare("
                INSERT INTO categories (name, slug, description)
                VALUES (:name, :slug, :description)
            ");
            $stmt->execute([
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description
            ]);
            $message = 'Category created successfully';
        }
        
        $messageType = 'success';
    } else {
        $message = 'Please enter a category name';
        $messageType = 'error';
    }
}

// Get category for editing
$category = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $category = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $category->execute([':id' => $_GET['id']]);
    $category = $category->fetch();
}

// Get all categories
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - <?php echo APP_NAME; ?></title>
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
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-container, .list-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
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
        }
        .actions a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Manage Categories</h1>
            <div>
                <a href="index.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo escape($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2><?php echo $category ? 'Edit Category' : 'Create New Category'; ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $category ? $category['id'] : ''; ?>">
                
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo $category ? escape($category['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo $category ? escape($category['description']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><?php echo $category ? 'Update Category' : 'Create Category'; ?></button>
                <?php if ($category): ?>
                    <a href="categories.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="list-container">
            <h2>All Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">No categories yet</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?php echo escape($cat['name']); ?></td>
                                <td><?php echo escape($cat['slug']); ?></td>
                                <td><?php echo escape($cat['description'] ?? ''); ?></td>
                                <td class="actions">
                                    <a href="categories.php?action=edit&id=<?php echo $cat['id']; ?>">Edit</a>
                                    <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" 
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

