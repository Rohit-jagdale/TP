<?php
/**
 * Post Management
 */

require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$pdo = getDBConnection();
if (!$pdo) {
    die('Database connection failed. Please check your database configuration.');
}

$message = '';
$messageType = '';

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;
    
    if ($action === 'delete' && $id) {
        // Get the post to retrieve the featured image path
        $post = getPostById($id);
        
        if ($post) {
            // Delete the associated image file if it exists
            if (!empty($post['featured_image'])) {
                $imagePath = dirname(dirname(__DIR__)) . '/' . $post['featured_image'];
                if (file_exists($imagePath) && is_file($imagePath)) {
                    @unlink($imagePath);
                }
            }
            
            // Delete the post from database
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
            if ($stmt->execute([':id' => $id])) {
                $message = 'Post deleted successfully';
                $messageType = 'success';
            } else {
                $message = 'Error deleting post';
                $messageType = 'error';
            }
        } else {
            $message = 'Post not found';
            $messageType = 'error';
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    $categoryIds = $_POST['categories'] ?? [];
    
    if (!empty($title) && !empty($content)) {
        $slug = slugify($title);
        
        // Handle file upload
        // For new posts, start with null; for edits, use existing image if present
        $featuredImage = !empty($_POST['existing_image']) ? $_POST['existing_image'] : null;
        $oldImagePath = null;
        
        // If updating a post, get the old image path for potential deletion
        if ($id) {
            $existingPost = getPostById($id);
            if ($existingPost && !empty($existingPost['featured_image'])) {
                $oldImagePath = $existingPost['featured_image'];
            }
        }
        
        // Check if a new file is being uploaded
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(dirname(__DIR__)) . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['featured_image']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                $fileName = time() . '_' . basename($_FILES['featured_image']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetFile)) {
                    // Verify the file was actually saved
                    if (file_exists($targetFile)) {
                        $featuredImage = 'uploads/' . $fileName;
                        
                        // Delete the old image if a new one was successfully uploaded
                        if ($oldImagePath && $oldImagePath !== $featuredImage) {
                            $oldImageFullPath = dirname(dirname(__DIR__)) . '/' . $oldImagePath;
                            if (file_exists($oldImageFullPath) && is_file($oldImageFullPath)) {
                                @unlink($oldImageFullPath);
                            }
                        }
                    } else {
                        $message = 'Error: Image file was not saved correctly.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Error: Failed to upload image file. Please check file permissions.';
                    $messageType = 'error';
                }
            } else {
                $message = 'Error: Invalid file type. Please upload an image (JPEG, PNG, GIF, or WebP).';
                $messageType = 'error';
            }
        } elseif (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle upload errors
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive.',
                UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
                UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
            ];
            $errorCode = $_FILES['featured_image']['error'];
            $message = 'Error uploading image: ' . ($uploadErrors[$errorCode] ?? 'Unknown error');
            $messageType = 'error';
        }
        
        // Only proceed if no upload error occurred
        if (!isset($message) || $messageType !== 'error') {
            if ($id) {
            // Update existing post
            $stmt = $pdo->prepare("
                UPDATE posts 
                SET title = :title, slug = :slug, content = :content, excerpt = :excerpt, 
                    featured_image = :featured_image, status = :status, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':slug' => $slug,
                ':content' => $content,
                ':excerpt' => $excerpt,
                ':featured_image' => $featuredImage,
                ':status' => $status
            ]);
            
            // Update categories
            $pdo->prepare("DELETE FROM post_categories WHERE post_id = :id")->execute([':id' => $id]);
            foreach ($categoryIds as $catId) {
                $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (:post_id, :cat_id)")
                    ->execute([':post_id' => $id, ':cat_id' => $catId]);
            }
            
            $message = 'Post updated successfully';
        } else {
            // Create new post
            $stmt = $pdo->prepare("
                INSERT INTO posts (title, slug, content, excerpt, featured_image, status)
                VALUES (:title, :slug, :content, :excerpt, :featured_image, :status)
            ");
            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':content' => $content,
                ':excerpt' => $excerpt,
                ':featured_image' => $featuredImage,
                ':status' => $status
            ]);
            
            $postId = $pdo->lastInsertId();
            
            // Add categories
            foreach ($categoryIds as $catId) {
                $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (:post_id, :cat_id)")
                    ->execute([':post_id' => $postId, ':cat_id' => $catId]);
            }
            
            $message = 'Post created successfully';
            }
            
            $messageType = 'success';
        } // End of "no upload error" check
    } else {
        $message = 'Please fill in all required fields';
        $messageType = 'error';
    }
}

// Get post for editing
$post = null;
$postCategories = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $post = getPostById($_GET['id']);
    if ($post) {
        $postCategories = array_column(getPostCategories($post['id']), 'id');
    }
}

// Get all posts
$allPosts = getPosts(null);

// Get all categories
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - <?php echo APP_NAME; ?></title>
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
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        #content {
            min-height: 400px;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: pointer;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 5px;
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
        .toggle-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Manage Posts</h1>
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
            <h2><?php echo $post ? 'Edit Post' : 'Create New Post'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $post ? $post['id'] : ''; ?>">
                
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $post ? escape($post['title']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea id="excerpt" name="excerpt"><?php echo $post ? escape($post['excerpt']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" required><?php echo $post ? escape($post['content']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <?php if ($post && $post['featured_image']): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="/<?php echo escape($post['featured_image']); ?>" 
                                 alt="Current image" style="max-width: 200px; height: auto;">
                            <input type="hidden" name="existing_image" value="<?php echo escape($post['featured_image']); ?>">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="categories">Categories</label>
                    <div class="checkbox-group">
                        <?php foreach ($categories as $category): ?>
                            <label>
                                <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>"
                                       <?php echo in_array($category['id'], $postCategories) ? 'checked' : ''; ?>>
                                <?php echo escape($category['name']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="draft" <?php echo ($post && $post['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($post && $post['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary"><?php echo $post ? 'Update Post' : 'Create Post'; ?></button>
                <?php if ($post): ?>
                    <a href="posts.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="list-container">
            <h2>All Posts</h2>
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
                    <?php if (empty($allPosts)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">No posts yet</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allPosts as $p): ?>
                            <tr>
                                <td><?php echo escape($p['title']); ?></td>
                                <td>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                                          background: <?php echo $p['status'] === 'published' ? '#d4edda' : '#fff3cd'; ?>;
                                          color: <?php echo $p['status'] === 'published' ? '#155724' : '#856404'; ?>;">
                                        <?php echo ucfirst($p['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($p['created_at']); ?></td>
                                <td class="actions">
                                    <a href="posts.php?action=edit&id=<?php echo $p['id']; ?>">Edit</a>
                                    <a href="posts.php?action=delete&id=<?php echo $p['id']; ?>" 
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

