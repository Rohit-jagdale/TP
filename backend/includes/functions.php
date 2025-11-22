<?php
/**
 * Helper Functions
 * Common utility functions for the blog
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Generate URL-friendly slug from string
 * @param string $text
 * @return string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}

/**
 * Sanitize output
 * @param string $data
 * @return string
 */
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Format date
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Get excerpt from content
 * @param string $content
 * @param int $length
 * @return string
 */
function getExcerpt($content, $length = 150) {
    $content = strip_tags($content);
    if (strlen($content) <= $length) {
        return $content;
    }
    return substr($content, 0, $length) . '...';
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /backend/admin/login.php');
        exit;
    }
}

/**
 * Get all posts
 * @param string $status
 * @param int $limit
 * @param int $offset
 * @return array
 */
function getPosts($status = 'published', $limit = null, $offset = 0) {
    $pdo = getDBConnection();
    if (!$pdo) return [];
    
    $sql = "SELECT * FROM posts WHERE status = :status ORDER BY created_at DESC";
    if ($limit !== null) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    if ($limit !== null) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get post by ID
 * @param int $id
 * @return array|null
 */
function getPostById($id) {
    $pdo = getDBConnection();
    if (!$pdo) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    return $stmt->fetch();
}

/**
 * Get post by slug
 * @param string $slug
 * @return array|null
 */
function getPostBySlug($slug) {
    $pdo = getDBConnection();
    if (!$pdo) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = :slug AND status = 'published'");
    $stmt->execute([':slug' => $slug]);
    
    return $stmt->fetch();
}

/**
 * Get categories for a post
 * @param int $postId
 * @return array
 */
function getPostCategories($postId) {
    $pdo = getDBConnection();
    if (!$pdo) return [];
    
    $stmt = $pdo->prepare("
        SELECT c.* FROM categories c
        INNER JOIN post_categories pc ON c.id = pc.category_id
        WHERE pc.post_id = :post_id
    ");
    $stmt->execute([':post_id' => $postId]);
    
    return $stmt->fetchAll();
}

/**
 * Get all categories
 * @return array
 */
function getCategories() {
    $pdo = getDBConnection();
    if (!$pdo) return [];
    
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

/**
 * Get posts by category
 * @param string $categorySlug
 * @param int $limit
 * @param int $offset
 * @return array
 */
function getPostsByCategory($categorySlug, $limit = null, $offset = 0) {
    $pdo = getDBConnection();
    if (!$pdo) return [];
    
    $sql = "
        SELECT p.* FROM posts p
        INNER JOIN post_categories pc ON p.id = pc.post_id
        INNER JOIN categories c ON pc.category_id = c.id
        WHERE c.slug = :slug AND p.status = 'published'
        ORDER BY p.created_at DESC
    ";
    
    if ($limit !== null) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':slug', $categorySlug, PDO::PARAM_STR);
    if ($limit !== null) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    
    return $stmt->fetchAll();
}

