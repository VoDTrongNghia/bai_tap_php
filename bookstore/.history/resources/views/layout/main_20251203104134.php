<?php /** @var string $title */ ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars(($title ?? APP_NAME) . ' - ' . APP_NAME) ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/home.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/news.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/office.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= $baseUrl ?>js/banner-slider.js" defer></script>
    
    <!-- Frontend Logger -->
    <script src="<?= $baseUrl ?>js/frontend-logger.js"></script>
    <script>
    // Initialize logger
    window.logger = new FrontendLogger({
        endpoint: '<?= $baseUrl ?>api/logs.php',
        batchSize: 10,
        flushInterval: 5000,
        enableConsoleLog: true,
        maxRetries: 3
    });

    // Track additional performance metrics
    window.addEventListener('load', function() {
        // Track image loading performance
        const images = document.querySelectorAll('img.lazy');
        let loadedCount = 0;
        let errorCount = 0;
        let totalLoadTime = 0;
        
        images.forEach(img => {
            const startTime = Date.now();
            
            img.addEventListener('load', function() {
                loadedCount++;
                const loadTime = Date.now() - startTime;
                totalLoadTime += loadTime;
                
                window.logger.trackPerformance('image_load_time', loadTime, 'ms');
                
                if (loadedCount + errorCount === images.length) {
                    window.logger.trackPerformance('avg_image_load_time', totalLoadTime / loadedCount, 'ms');
                    window.logger.trackPerformance('image_load_success_rate', (loadedCount / images.length) * 100, '%');
                }
            });
            
            img.addEventListener('error', function() {
                errorCount++;
                window.logger.trackCustomEvent('image_load_failed', {
                    src: this.src,
                    className: this.className
                });
            });
        });
        
        // Track scroll depth
        let maxScrollDepth = 0;
        window.addEventListener('scroll', function() {
            const scrollDepth = Math.round((window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
            if (scrollDepth > maxScrollDepth) {
                maxScrollDepth = scrollDepth;
                window.logger.trackCustomEvent('scroll_depth_update', {
                    depth: scrollDepth,
                    maxDepth: maxScrollDepth
                });
            }
        });
        
        // Track page load time
        if (window.performance && window.performance.timing) {
            const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
            window.logger.trackPerformance('page_load_time', loadTime, 'ms');
        }
    });

    // Track user interactions with products
    document.addEventListener('DOMContentLoaded', function() {
        // Track product card interactions
        const productCards = document.querySelectorAll('.book-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                window.logger.trackCustomEvent('product_hover', {
                    productId: this.querySelector('a')?.href?.match(/\/books\/(\d+)/)?.[1] || 'unknown',
                    title: this.querySelector('.book-title')?.textContent?.trim() || 'unknown'
                });
            });
            
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.add-to-cart-btn')) {
                    window.logger.trackCustomEvent('product_click', {
                        productId: this.querySelector('a')?.href?.match(/\/books\/(\d+)/)?.[1] || 'unknown',
                        title: this.querySelector('.book-title')?.textContent?.trim() || 'unknown'
                    });
                }
            });
        });
        
        // Track add to cart clicks
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const bookIdInput = form?.querySelector('input[name="id"]');
                const bookId = bookIdInput?.value || form?.querySelector('input[name="book_id"]')?.value;
                const bookCard = this.closest('.book-card');
                const title = bookCard?.querySelector('.book-title')?.textContent?.trim();
                
                window.logger.trackCustomEvent('add_to_cart_click', {
                    bookId: bookId || 'unknown',
                    title: title || 'unknown'
                });

                if (form) {
                    addToCart(e, form, bookId || '');
                }
            });
        });
        
        // Track banner interactions
        const bannerSlides = document.querySelectorAll('.banner-slide');
        bannerSlides.forEach((slide, index) => {
            slide.addEventListener('click', function() {
                window.logger.trackCustomEvent('banner_click', {
                    slideIndex: index,
                    slideTitle: this.querySelector('h2')?.textContent?.trim() || 'unknown'
                });
            });
        });
        
        // Track search interactions
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                window.logger.trackCustomEvent('search_focus');
            });
            
            searchInput.addEventListener('search', function() {
                if (this.value.trim()) {
                    window.logger.trackCustomEvent('search_submit', {
                        query: this.value.trim(),
                        queryLength: this.value.length
                    });
                }
            });
        }
    });
    </script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?php include 'footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>