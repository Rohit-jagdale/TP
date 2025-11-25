<?php
/**
 * Homepage - Dynamic PHP Version
 */

require_once __DIR__ . '/backend/includes/functions.php';

// Get latest published posts
$latestPosts = getPosts('published', 5);
?>
<!doctype html>

<html lang="en" class="minimal-style is-menu-fixed is-always-fixed is-selection-shareable blog-animated header-light header-small" data-effect="slideUp">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Impose - Responsive HTML5 Template">
    <meta name="keywords" content="personal, blog, html5">
    <meta name="author" content="Pixelwars">
    <title>Impose - for bloggers</title>
    
    <!-- FAV and TOUCH ICONS -->
    <link rel="shortcut icon" href="imgdd.jpg">
    <link rel="apple-touch-icon" href="imgdd.jpg"/>
    
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Noto+Sans:400,400i,700,700i|Poppins:300,400,500,600,700" rel="stylesheet">
    
    <!-- STYLES -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    
	<!-- INITIAL SCRIPTS -->
	<script>
// Modernizr placeholder - Feature detection library
(function(window) {
    'use strict';
    window.Modernizr = window.Modernizr || {};
    // Basic feature detection
    window.Modernizr.csstransforms3d = 'transform' in document.documentElement.style;
    window.Modernizr.csstransitions = 'transition' in document.documentElement.style;
})(window);
	</script>

</head>

<body class="  ">

    <!-- page -->
    <div id="page" class="hfeed site">
        
        <!-- header -->
        <header id="masthead" class="site-header" role="banner">
 			
            
            <!-- site-navigation -->
            <nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
                
                
                
                <!-- layout-medium -->
                <div class="layout-medium">
                
                    
                    
                    
                    <!-- site-title : image-logo -->
                    <h1 class="site-title">
                        <a href="index.php" rel="home">
                            <img src="imgdd.jpg" alt="logo">
                        	<span class="screen-reader-text">Haley Dust</span>
                        </a>
                    </h1>
                    <!-- site-title -->
                    
                    <!-- site-title : text-logo -->
                    <!--<h1 class="site-title">
                        <a href="../index.php" rel="home">
                            Haley Dust
                        </a>
                    </h1>-->
                    <!-- site-title -->
                    
                    
                    
                
                    <a class="menu-toggle"><span class="lines"></span></a>
                    
                    <!-- nav-menu -->
                    <div class="nav-menu">
                        <ul>
                            <li><a href="index.php">HOME</a></li>
                            <li><a href="posts.php">POSTS</a></li>
                            <li><a href="about.html">ABOUT ME</a></li>
                            <li><a href="contact.html">CONTACT</a></li>
                        </ul>
                    </div>
                    <!-- nav-menu -->
                    
                    <a class="search-toggle toggle-link"></a>
                                        
                    <!-- search-container -->
                    <div class="search-container">
                        
                        <div class="search-box" role="search">
                            <form method="get" class="search-form" action="#">
                                <label>Search Here
                                    <input type="search" id="search-field" placeholder="type and hit enter" name="s">
                                </label>
                                <input type="submit" class="search-submit" value="Search">
                            </form>
                        </div>
                    
                    </div>
                    <!-- search-container -->
                    
                    <!-- social-container -->
                    <div class="social-container">
                        
                        <a class="social-link facebook" href="#"><img src="facebook-logo.jpeg" alt="Facebook" style="width: 16px; height: 16px; object-fit: contain;"></a>
                        <a class="social-link twitter" href="#"><img src="x-logo.jpg" alt="X (Twitter)" style="width: 16px; height: 16px; object-fit: contain;"></a>
                        <a class="social-link vine" href="#"></a>
                        <a class="social-link dribbble" href="#"></a>
                        <a class="social-link instagram" href="#"><img src="insta-logo.avif" alt="Instagram" style="width: 16px; height: 16px; object-fit: contain;"></a>
    
                    </div>
                    <!-- social-container -->
        
                </div>
                <!-- layout-medium -->
                
            </nav>
            <!-- site-navigation -->
            
            		
        </header>
        <!-- header -->
        
        
        
        
        <!-- site-main -->
        <div id="main" class="site-main">
		
            
            <div class="layout-medium"> 
                <div id="primary" class="content-area">
             
            
                    <!-- site-content -->
                    <div id="content" class="site-content" role="main">
                    
                                    
                        <!-- .hentry -->
                        <article class="hentry page">
                            
                                
                            <!-- .entry-content -->
                            <div class="entry-content intro" data-animation="rotate-1">
                                
                                
                                
                                <!-- .profile-image -->
                                <div class="profile-image">
                                	<img alt="profile" src="imgdd.jpg"/>
                                </div>  
                                <!-- .profile-image -->
                                
                                
                                <h2><em>Hi.</em> I am Shreyas Shinde</h2>
                                <h3>I am a <span class="flip-text"><strong class="flip-word" data-word="blogger">blogger</strong><strong class="flip-word" data-word="traveler">traveler</strong><strong class="flip-word" data-word="writer">writer</strong></span></h3>
                                
                                
                                <!-- .link-boxes -->
                                <figure>
                                	<a href="about.html"><img src="imgdd.jpg" alt="About Me"></a>
                                    <figcaption class="wp-caption-text">About Me</figcaption>
                                </figure>        
                                
                                <figure>
                                	<a href="about.html"><img src="imgdd.jpg" alt="About Me"></a>
                                    <figcaption class="wp-caption-text">Life</figcaption>
                                </figure> 
                                
                                <figure>
                                	<a href="about.html"><img src="imgdd.jpg" alt="About Me"></a>
                                    <figcaption class="wp-caption-text">Travel</figcaption>
                                </figure> 
                                
                                <figure>
                                	<a href="https://www.instagram.com/pixelwarsdesign/"><img src="insta-logo.avif" alt="Follow On Instagram"></a>
                                    <figcaption class="wp-caption-text">Follow On Instagram</figcaption>
                                </figure> 
                                <!-- .link-boxes -->         
                                	
                                    
                             </div> 
                             <!-- .entry-content -->
                                
                                	
                                    
                         </article> 
                         <!-- .page -->
                                 
                                 
                       <!-- .home-title -->
                       <h3 class="widget-title home-title">LATEST FROM THE BLOG</h3>  
                         
                        
                       <!-- BLOG SIMPLE -->
                       <div class="blog-simple">
                            
                            <?php if (empty($latestPosts)): ?>
                                <p style="text-align: center; padding: 40px; color: #999;">No posts yet. Check back soon!</p>
                            <?php else: ?>
                                <?php foreach ($latestPosts as $post): ?>
                                    <?php
                                    $postDate = new DateTime($post['created_at']);
                                    $day = $postDate->format('d');
                                    $month = $postDate->format('M');
                                    $year = $postDate->format('Y');
                                    $featuredImage = !empty($post['featured_image']) ? $post['featured_image'] : 'imgdd.jpg';
                                    ?>
                                    <!-- .hentry -->
                                    <article class="hentry post has-post-thumbnail">
                                        
                                        <!-- .hentry-left -->
                                        <div class="hentry-left">
                                            <div class="entry-date">
                                                <span class="day"><?php echo $day; ?></span>
                                                <span class="month"><?php echo $month; ?></span>
                                                <span class="year"><?php echo $year; ?></span>
                                            </div>
                                            <div class="featured-image" style="background-image:url(<?php echo escape($featuredImage); ?>)"></div>
                                        </div>
                                        <!-- .hentry-left -->
                                        
                                        <!-- .hentry-middle -->
                                        <div class="hentry-middle">
                                                
                                            <!-- .entry-title -->
                                            <h2 class="entry-title"><a href="single-post.php?slug=<?php echo escape($post['slug']); ?>"><?php echo escape($post['title']); ?></a></h2>
                                    
                                        </div>
                                        <!-- .hentry-middle -->
                                        
                                        <a class="post-link" href="single-post.php?slug=<?php echo escape($post['slug']); ?>"><?php echo escape($post['title']); ?></a>
                                        
                                    </article>
                                    <!-- .hentry -->
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                       </div> 
                       <!-- BLOG SIMPLE -->
                      
                      
                      <!-- .home-launch -->
                      <div class="home-launch">
                        <a class="button" href="posts.php">See All Posts</a>
                      </div> 
                      <!-- .home-launch -->
                               
                                
                        
                            
                        
                    </div>
                    <!-- site-content -->
            
                </div>
                <!-- primary -->    
            
            
            </div>
            <!-- layout -->
        
        
        </div>
        <!-- site-main -->
        
        
        
        <!-- site-footer -->
        <footer id="colophon" class="site-footer" role="contentinfo">
			
            <!-- layout-medium -->
            <div class="layout-medium">
            
            	
                <!-- site-title-wrap -->
                <div class="site-title-wrap">
                               
                    <!-- site-title : image-logo -->
                    <h1 class="site-title">
                        <a href="index.php" rel="home">
                            <img src="imgdd.jpg" alt="logo">
                        </a>
                    </h1>
                    <!-- site-title -->
                    
                    <p class="site-description">just living the life as it goes by</p>
                
                </div>
                <!-- site-title-wrap -->
                
            	
                <!-- footer-social -->
                <div class="footer-social">
                	
                    <div class="textwidget">
                        <a class="social-link facebook" href="#"><img src="facebook-logo.jpeg" alt="Facebook" style="width: 20px; height: 20px; object-fit: contain; vertical-align: middle;"></a>
                        <a class="social-link twitter" href="#"><img src="x-logo.jpg" alt="X (Twitter)" style="width: 20px; height: 20px; object-fit: contain; vertical-align: middle;"></a>
                        <a class="social-link vine" href="#"></a>
                        <a class="social-link dribbble" href="#"></a>
                        <a class="social-link instagram" href="#"><img src="insta-logo.avif" alt="Instagram" style="width: 20px; height: 20px; object-fit: contain; vertical-align: middle;"></a>
                    </div>
                    
                </div>
                <!-- footer-social -->
                
               
            </div>
            <!-- layout-medium -->
                
                
            <!-- .site-info -->
            <div class="site-info">
            	
                <!-- layout-medium -->
            	<div class="layout-medium">
                
            		<div class="textwidget">crafted with <i class="pw-icon-heart"></i> <em>by</em> Pixelwars</div>
                
                </div>
            	<!-- layout-medium -->
            
            </div>
            <!-- .site-info -->
            
            
            
		</footer>
        <!-- site-footer -->

        
	</div>
    <!-- page -->
    

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
	   if (!window.jQuery) { 
		  console.log('jQuery fallback placeholder - CDN should be used instead');
	   }
	</script>
    <script>
// jQuery Migrate placeholder
console.log('jQuery Migrate placeholder loaded');
    </script>
    <script>
// FastClick placeholder - Removes click delays on touch devices
(function() {
    'use strict';
    if (typeof FastClick !== 'undefined') {
        FastClick.attach(document.body);
    }
})();
    </script>
    <script>
// FitVids placeholder - Makes videos responsive
(function($) {
    'use strict';
    if (typeof $.fn.fitVids === 'undefined') {
        $.fn.fitVids = function() {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Viewport placeholder
console.log('Viewport placeholder loaded');
    </script>
    <script>
// Waypoints placeholder - Trigger functions when scrolling to elements
(function($) {
    'use strict';
    if (typeof $.fn.waypoint === 'undefined') {
        $.fn.waypoint = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// jQuery Validation placeholder - Form validation
(function($) {
    'use strict';
    if (typeof $.fn.validate === 'undefined') {
        $.fn.validate = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// ImagesLoaded placeholder - Trigger callbacks when images are loaded
(function($) {
    'use strict';
    if (typeof $.fn.imagesLoaded === 'undefined') {
        $.fn.imagesLoaded = function(callback) {
            if (callback) callback();
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Isotope placeholder - Filter & sort layouts
(function($) {
    'use strict';
    if (typeof $.fn.isotope === 'undefined') {
        $.fn.isotope = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Magnific Popup placeholder - Lightbox plugin
(function($) {
    'use strict';
    if (typeof $.fn.magnificPopup === 'undefined') {
        $.fn.magnificPopup = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Fluidbox placeholder - Lightbox alternative
(function($) {
    'use strict';
    if (typeof $.fn.fluidbox === 'undefined') {
        $.fn.fluidbox = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Owl Carousel placeholder - Touch enabled carousel
(function($) {
    'use strict';
    if (typeof $.fn.owlCarousel === 'undefined') {
        $.fn.owlCarousel = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Selection Sharer placeholder - Share selected text
console.log('Selection Sharer placeholder loaded');
    </script>
    <script>
// Social Stream placeholder
(function($) {
    'use strict';
    if (typeof $.fn.socialstream === 'undefined') {
        $.fn.socialstream = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// CollagePlus placeholder - Image collage layout
(function($) {
    'use strict';
    if (typeof $.fn.collagePlus === 'undefined') {
        $.fn.collagePlus = function(options) {
            return this;
        };
    }
})(jQuery);
    </script>
    <script>
// Main JavaScript file for the blog template
(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Menu toggle functionality
        $('.menu-toggle').on('click', function() {
            $('body').toggleClass('is-menu-toggled-on');
            $(this).toggleClass('close');
        });
        
        // Search toggle functionality
        $('.search-toggle').on('click', function() {
            $('body').toggleClass('is-search-toggled-on');
        });
        
        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.site-navigation, .menu-toggle').length) {
                $('body').removeClass('is-menu-toggled-on');
                $('.menu-toggle').removeClass('close');
            }
        });
        
        // Mobile submenu toggle
        $('.nav-menu ul li').has('ul').each(function() {
            var $toggle = $('<span class="submenu-toggle"></span>');
            $(this).children('a').after($toggle);
        });
        
        $('.submenu-toggle').on('click', function(e) {
            e.preventDefault();
            $(this).parent('li').toggleClass('active');
        });
        
        // Rotating words animation (if rotate-words.js is not loaded)
        if (typeof window.RotateWords === 'undefined') {
            var $rotateTitle = $('.rotate-title');
            if ($rotateTitle.length) {
                var words = $rotateTitle.find('strong');
                var currentIndex = 0;
                
                if (words.length > 1) {
                    words.eq(0).addClass('is-visible');
                    
                    setInterval(function() {
                        words.eq(currentIndex).removeClass('is-visible').addClass('is-hidden');
                        currentIndex = (currentIndex + 1) % words.length;
                        words.eq(currentIndex).removeClass('is-hidden').addClass('is-visible');
                    }, 3000);
                }
            }
        }
        
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
        
        // Initialize plugins if they exist
        if (typeof $.fn.fitVids !== 'undefined') {
            $('.entry-content').fitVids();
        }
        
        if (typeof $.fn.owlCarousel !== 'undefined') {
            $('.owl-carousel').owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true
            });
        }
        
        if (typeof $.fn.magnificPopup !== 'undefined') {
            $('.gallery-item a, .featured-image a').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
        
        if (typeof $.fn.fluidbox !== 'undefined') {
            $('.featured-image a').fluidbox();
        }
        
        // Blog simple hover effect
        $('.blog-simple .hentry').on('mouseenter', function() {
            $(this).find('.featured-image').css('opacity', '1');
        }).on('mouseleave', function() {
            $(this).find('.featured-image').css('opacity', '0');
        });
        
    });
    
})(jQuery);
    </script>
    <script>
// Shortcodes JavaScript placeholder
(function($) {
    'use strict';
    $(document).ready(function() {
        // Shortcodes initialization
        console.log('Shortcodes placeholder loaded');
    });
})(jQuery);
    </script>

</body>
</html>

