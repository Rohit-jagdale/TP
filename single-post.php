<?php
/**
 * Single Post Page - Dynamic PHP Version
 */

require_once __DIR__ . '/backend/includes/functions.php';

// Get post slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    die('Post not found');
}

// Get post by slug
$post = getPostBySlug($slug);

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    die('Post not found');
}

// Get post categories
$postCategories = getPostCategories($post['id']);

// Get related posts (same category or recent)
$relatedPosts = getPosts('published', 3);
$relatedPosts = array_filter($relatedPosts, function($p) use ($post) {
    return $p['id'] != $post['id'];
});
$relatedPosts = array_slice($relatedPosts, 0, 3);

$readTime = ceil(str_word_count(strip_tags($post['content'])) / 200);
$featuredImage = (!empty($post['featured_image']) && trim($post['featured_image']) !== '') 
    ? trim($post['featured_image']) 
    : 'post1.webp';
?>
<!DOCTYPE html>
<html data-wf-page="grungy-id" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title><?php echo escape($post['title']); ?> - Blog</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    
    <link href="styles.css" rel="stylesheet" type="text/css" />
    <link href="posts.css" rel="stylesheet" type="text/css" />
    
    <link href="imgdd.jpg" rel="shortcut icon" type="image/x-icon" />
    <link href="imgdd.jpg" rel="apple-touch-icon" />
</head>
<body class="body">
    <div id="home" class="header">
        <div class="inner-header">

            <div class="top-bar">
                <div class="tob-bar-category-text">Blog</div>
                <div class="tech-cateogry-moving-section">
                    <div class="moving-tech-list-wrapper w-dyn-list">
                        <div role="list" class="moving-tech-list w-dyn-items">
                            <?php 
                            $recentPosts = getPosts('published', 6);
                            foreach ($recentPosts as $recentPost): 
                                $recentPostImage = (!empty($recentPost['featured_image']) && trim($recentPost['featured_image']) !== '') 
                                    ? trim($recentPost['featured_image']) 
                                    : 'post1.webp';
                            ?>
                            <div role="listitem" class="w-dyn-item">
                                <a href="single-post.php?slug=<?php echo escape($recentPost['slug']); ?>" class="moving-tech-item w-inline-block">
                                    <img src="<?php echo escape($recentPostImage); ?>" loading="lazy" width="20" alt="<?php echo escape($recentPost['title']); ?>" class="comment-icon" />
                                    <div class="moving-tech-text"><?php echo escape($recentPost['title']); ?></div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease" role="banner" class="navbar w-nav">
                <div class="inner-navbar">
                    <a href="index.php" class="logo w-nav-brand">
                        <img src="imgdd.jpg" width="200" alt="" class="logo-img" />
                    </a>
                    <nav role="navigation" class="nav-menu w-nav-menu">
                        <a href="index.php" class="menu-item w-nav-link">Home</a>
                        <a href="posts.php" class="menu-item w-nav-link">Posts</a>
                        <a href="about.html" class="menu-item w-nav-link">About</a>
                        <a href="contact.html" class="menu-item w-nav-link">Contact</a>
                    </nav>
                    <div class="menu-button w-nav-button">
                        <div class="icon w-icon-nav-menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="single-post-section">
        <div class="w-layout-blockcontainer main-container w-container">
            <article class="single-post-article">
                
                <?php if ($featuredImage): ?>
                <div class="single-post-featured-image">
                    <img src="<?php echo escape($featuredImage); ?>" alt="<?php echo escape($post['title']); ?>" />
                </div>
                <?php endif; ?>
                
                <header class="single-post-header">
                    <h1 class="single-post-title"><?php echo escape($post['title']); ?></h1>
                    
                    <div class="single-post-meta">
                        <span><?php echo formatDate($post['created_at'], 'F j, Y'); ?></span>
                        <span>•</span>
                        <span><?php echo $readTime; ?> min read</span>
                        <?php if (!empty($postCategories)): ?>
                            <span>•</span>
                            <div style="display: flex; gap: 10px;">
                                <?php foreach ($postCategories as $cat): ?>
                                    <a href="posts.php?category=<?php echo escape($cat['slug']); ?>" 
                                       class="post-category-link">
                                        <?php echo escape($cat['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($post['excerpt'])): ?>
                    <div class="single-post-excerpt">
                        <?php echo escape($post['excerpt']); ?>
                    </div>
                    <?php endif; ?>
                </header>
                
                <div class="single-post-content">
                    <?php echo nl2br(escape($post['content'])); ?>
                </div>
                
                <div class="back-to-posts">
                    <a href="posts.php" class="back-link">
                        ← Back to Posts
                    </a>
                </div>
            </article>
            
            <?php if (!empty($relatedPosts)): ?>
            <div class="related-posts-section">
                <h2 class="related-posts-title">Related Posts</h2>
                <div class="related-posts-grid">
                    <?php foreach ($relatedPosts as $relatedPost): 
                        $relatedImage = (!empty($relatedPost['featured_image']) && trim($relatedPost['featured_image']) !== '') 
                            ? trim($relatedPost['featured_image']) 
                            : 'post1.webp';
                    ?>
                    <div class="related-post-card">
                        <a href="single-post.php?slug=<?php echo escape($relatedPost['slug']); ?>" class="related-post-image-link">
                            <img src="<?php echo escape($relatedImage); ?>" alt="<?php echo escape($relatedPost['title']); ?>" />
                        </a>
                        <div class="related-post-content">
                            <h3 class="related-post-title">
                                <a href="single-post.php?slug=<?php echo escape($relatedPost['slug']); ?>">
                                    <?php echo escape($relatedPost['title']); ?>
                                </a>
                            </h3>
                            <p class="related-post-date">
                                <?php echo formatDate($relatedPost['created_at'], 'F j, Y'); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="footer">
        <div class="w-layout-blockcontainer main-container w-container">
            <div class="inner-footer">
                <div class="social-footer">
                    <div class="social-inner">
                        <a href="#" class="social-item w-inline-block">
                            <img src="facebook-logo.jpeg" loading="lazy" width="48" alt="Facebook" class="social-icon" />
                            <div class="social-text">facebook</div>
                        </a>
                        <a href="#" class="social-item w-inline-block">
                            <img src="x-logo.jpg" loading="lazy" width="48" alt="X (Twitter)" class="social-icon" />
                            <div class="social-text">twitter</div>
                        </a>
                        <a href="#" class="social-item w-inline-block">
                            <img src="insta-logo.avif" loading="lazy" width="48" alt="Instagram" class="social-icon" />
                            <div class="social-text">instagram</div>
                        </a>
                    </div>
                </div>
                <div class="copyright">
                    <div class="copyright-text">
                        Designed by <a href="https://themeforest.net/user/max-themes" target="_blank">JD</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/webfont.js" type="text/javascript"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/plugins.js" type="text/javascript"></script>
</body>
</html>

