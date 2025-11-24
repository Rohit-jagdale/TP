<?php
/**
 * Posts Listing Page - Dynamic PHP Version
 */

require_once __DIR__ . '/backend/includes/functions.php';

// Get category filter if provided
$categorySlug = $_GET['category'] ?? null;
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Get posts based on category or all posts
if ($categorySlug) {
    $allPosts = getPostsByCategory($categorySlug);
    $totalPosts = count($allPosts);
    $posts = array_slice($allPosts, $offset, $perPage);
} else {
    $pdo = getDBConnection();
    if ($pdo) {
        $totalPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
        $posts = getPosts('published', $perPage, $offset);
    } else {
        $totalPosts = 0;
        $posts = [];
    }
}

$totalPages = ceil($totalPosts / $perPage);
$categories = getCategories();
?>
<!DOCTYPE html>
<html data-wf-page="grungy-id">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <title>Posts - Blog HTML Website Template</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

        <link href="posts.css" rel="stylesheet" type="text/css" />

        <link href="imgdd.jpg" rel="shortcut icon" type="image/x-icon" />
        <link href="imgdd.jpg" rel="apple-touch-icon" />
    </head>

    <body class="body">
        <div id="home" class="header">
            <div class="inner-header">

                <div class="top-bar">
                    <div class="tob-bar-category-text">Tech</div>
                    <div class="tech-cateogry-moving-section">
                        <div class="moving-tech-list-wrapper w-dyn-list">
                            <div role="list" class="moving-tech-list w-dyn-items">
                                <?php 
                                $recentPosts = getPosts('published', 6);
                                foreach ($recentPosts as $recentPost): 
                                ?>
                                <div role="listitem" class="w-dyn-item">
                                    <a href="single-post.php?slug=<?php echo escape($recentPost['slug']); ?>" class="moving-tech-item w-inline-block">
                                        <img src="post1.webp" loading="lazy" width="20" alt="" class="comment-icon" />
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
                        <a href="index.php" aria-current="page" class="logo w-nav-brand w--current">
                            <img src="imgdd.jpg" width="200" alt="" class="logo-img" />
                        </a>
                        <nav role="navigation" class="nav-menu w-nav-menu">
                            <a href="index.php" class="menu-item w-nav-link">Home</a>
                            <a href="about.html" class="menu-item w-nav-link">About</a>
                            <a href="categories.html" class="menu-item w-nav-link">categories</a>
                            <a href="posts.php" class="menu-item w-nav-link w--current">posts</a>
                            <a href="contact.html" class="menu-item w-nav-link">Contact</a>
                        </nav>
                        <div class="menu-button w-nav-button">
                            <div class="icon w-icon-nav-menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="top-blog-section">
            <div class="w-layout-blockcontainer main-container w-container">
                <div class="top-blog-list-wrapper w-dyn-list">
                    <div role="list" class="top-blog-list w-dyn-items">
                        <?php if (empty($posts)): ?>
                            <div role="listitem" class="w-dyn-item">
                                <p style="text-align: center; padding: 40px;">No posts found.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($posts as $post): 
                                $postDate = new DateTime($post['created_at']);
                                $featuredImage = !empty($post['featured_image']) ? $post['featured_image'] : 'post1.webp';
                                $postCategories = getPostCategories($post['id']);
                                $primaryCategory = !empty($postCategories) ? $postCategories[0] : null;
                                $readTime = ceil(str_word_count(strip_tags($post['content'])) / 200); // Approximate reading time
                            ?>
                            <div role="listitem" class="w-dyn-item">
                                <div data-w-id="1b3f3cd6-09ce-1f4e-e361-71a9f11e93a8" class="top-blog-item">
                                    <a href="single-post.php?slug=<?php echo escape($post['slug']); ?>" class="top-blog-img-wrapper w-inline-block">
                                        <img src="<?php echo escape($featuredImage); ?>" alt="<?php echo escape($post['title']); ?>" class="top-blog-img" />
                                        <img src="imgdd.jpg" loading="lazy" width="143" alt="" class="tob-blog-line" />
                                    </a>
                                    <div class="top-blog-content">
                                        <h2 class="top-blog-title"><?php echo escape($post['title']); ?></h2>
                                        <div class="top-blog-time-box">
                                            <div class="post-time-text"><?php echo formatDate($post['created_at'], 'F j, Y'); ?></div>
                                            <div class="post-time-text"><?php echo $readTime; ?> min Read</div>
                                        </div>
                                        <div class="top-blog-read-box">
                                            <img src="imgdd.jpg" loading="lazy" width="55" alt="" class="top-blog-left-arrow" />
                                            <a href="single-post.php?slug=<?php echo escape($post['slug']); ?>" class="read-more w-inline-block">
                                                <div class="read-more-text">Read post</div>
                                                <div class="arrow">
                                                    <img src="arrow.png" loading="lazy" width="34" alt="" />
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php if ($primaryCategory): ?>
                                    <a href="posts.php?category=<?php echo escape($primaryCategory['slug']); ?>" class="top-blog-category"><?php echo escape($primaryCategory['name']); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($totalPages > 1): ?>
                <div style="text-align: center; margin-top: 40px; padding: 20px;">
                    <?php if ($page > 1): ?>
                        <a href="posts.php?page=<?php echo $page - 1; ?><?php echo $categorySlug ? '&category=' . escape($categorySlug) : ''; ?>" 
                           style="padding: 10px 20px; margin: 0 5px; background: #333; color: white; text-decoration: none; border-radius: 5px;">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span style="padding: 10px 20px; margin: 0 5px; background: #667eea; color: white; border-radius: 5px;"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="posts.php?page=<?php echo $i; ?><?php echo $categorySlug ? '&category=' . escape($categorySlug) : ''; ?>" 
                               style="padding: 10px 20px; margin: 0 5px; background: #333; color: white; text-decoration: none; border-radius: 5px;"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="posts.php?page=<?php echo $page + 1; ?><?php echo $categorySlug ? '&category=' . escape($categorySlug) : ''; ?>" 
                           style="padding: 10px 20px; margin: 0 5px; background: #333; color: white; text-decoration: none; border-radius: 5px;">Next</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="footer">
            <div class="w-layout-blockcontainer main-container w-container">
                <div class="inner-footer">
                    <div id="w-node-_309588e7-d5f0-871f-0289-459e178d6ab7-178d6ab4" data-w-id="309588e7-d5f0-871f-0289-459e178d6ab7" class="social-footer" style="opacity: 0;">
                        <div class="social-inner">
                            <a id="w-node-_309588e7-d5f0-871f-0289-459e178d6ab9-178d6ab4" href="http://www.themeforest.com" target="_blank" class="social-item w-inline-block">
                                <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                                <div class="social-text">facebook</div>
                            </a>
                            <a id="w-node-_309588e7-d5f0-871f-0289-459e178d6abd-178d6ab4" href="http://www.themeforest.com" target="_blank" class="social-item w-inline-block">
                                <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                                <div class="social-text">twitter</div>
                            </a>
                            <a id="w-node-_309588e7-d5f0-871f-0289-459e178d6ac1-178d6ab4" href="http://www.themeforest.com" target="_blank" class="social-item w-inline-block">
                                <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                                <div class="social-text">instagram</div>
                            </a>
                            <a id="w-node-_309588e7-d5f0-871f-0289-459e178d6ac5-178d6ab4" href="http://www.themeforest.com" target="_blank" class="social-item w-inline-block">
                                <img src="imgdd.jpg" loading="lazy" width="48" alt="" class="social-icon" />
                                <div class="social-text">yotube</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-w-id="309588e7-d5f0-871f-0289-459e178d6ac9" class="copyright" style="opacity: 0;">
                    <div class="copyright-text">
                        Designed by <a href="https://themeforest.net/user/max-themes" target="_blank" class="copyright-text">MaxThemes</a>
                    </div>
                    <div class="copyright-rightside">
                        <a href="#" class="copyright-text license-text">License</a>
                        <a href="#home" class="backtop w-inline-block"><img src="imgdd.jpg" loading="lazy" width="14" alt="" class="backtop-image" /></a>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/webfont.js" type="text/javascript"></script>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/plugins.js" type="text/javascript"></script>
    </body>
</html>

