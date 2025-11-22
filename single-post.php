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
$featuredImage = !empty($post['featured_image']) ? $post['featured_image'] : 'post1.webp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($post['title']); ?> - Blog</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    
    <link href="posts.css" rel="stylesheet" type="text/css" />
    <link href="styles.css" rel="stylesheet" type="text/css" />
    
    <link href="imgdd.jpg" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
    <div id="home" class="header">
        <div class="inner-header">
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

    <section class="top-blog-section" style="padding: 60px 0;">
        <div class="w-layout-blockcontainer main-container w-container">
            <article style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                
                <?php if ($featuredImage): ?>
                <div style="margin-bottom: 30px;">
                    <img src="<?php echo escape($featuredImage); ?>" alt="<?php echo escape($post['title']); ?>" 
                         style="width: 100%; height: auto; border-radius: 8px;" />
                </div>
                <?php endif; ?>
                
                <header style="margin-bottom: 30px;">
                    <h1 style="font-size: 36px; margin-bottom: 15px; color: #333;"><?php echo escape($post['title']); ?></h1>
                    
                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px; color: #666; font-size: 14px;">
                        <span><?php echo formatDate($post['created_at'], 'F j, Y'); ?></span>
                        <span>•</span>
                        <span><?php echo $readTime; ?> min read</span>
                        <?php if (!empty($postCategories)): ?>
                            <span>•</span>
                            <div style="display: flex; gap: 10px;">
                                <?php foreach ($postCategories as $cat): ?>
                                    <a href="posts.php?category=<?php echo escape($cat['slug']); ?>" 
                                       style="color: #667eea; text-decoration: none;">
                                        <?php echo escape($cat['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($post['excerpt'])): ?>
                    <p style="font-size: 18px; color: #666; line-height: 1.6; margin-bottom: 20px;">
                        <?php echo escape($post['excerpt']); ?>
                    </p>
                    <?php endif; ?>
                </header>
                
                <div class="entry-content" style="line-height: 1.8; color: #333; font-size: 16px;">
                    <?php echo nl2br(escape($post['content'])); ?>
                </div>
                
                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #eee;">
                    <a href="posts.php" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        ← Back to Posts
                    </a>
                </div>
            </article>
            
            <?php if (!empty($relatedPosts)): ?>
            <div style="max-width: 800px; margin: 60px auto 0;">
                <h2 style="font-size: 28px; margin-bottom: 30px; color: #333;">Related Posts</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <?php foreach ($relatedPosts as $relatedPost): 
                        $relatedImage = !empty($relatedPost['featured_image']) ? $relatedPost['featured_image'] : 'post1.webp';
                    ?>
                    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <a href="single-post.php?slug=<?php echo escape($relatedPost['slug']); ?>">
                            <img src="<?php echo escape($relatedImage); ?>" alt="<?php echo escape($relatedPost['title']); ?>" 
                                 style="width: 100%; height: 200px; object-fit: cover;" />
                        </a>
                        <div style="padding: 20px;">
                            <h3 style="margin-bottom: 10px;">
                                <a href="single-post.php?slug=<?php echo escape($relatedPost['slug']); ?>" 
                                   style="color: #333; text-decoration: none; font-size: 18px;">
                                    <?php echo escape($relatedPost['title']); ?>
                                </a>
                            </h3>
                            <p style="color: #666; font-size: 14px;">
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
                            <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                            <div class="social-text">facebook</div>
                        </a>
                        <a href="#" class="social-item w-inline-block">
                            <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                            <div class="social-text">twitter</div>
                        </a>
                        <a href="#" class="social-item w-inline-block">
                            <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                            <div class="social-text">instagram</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="copyright-text">
                    Designed by <a href="https://themeforest.net/user/max-themes" target="_blank">MaxThemes</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/plugins.js" type="text/javascript"></script>
</body>
</html>

