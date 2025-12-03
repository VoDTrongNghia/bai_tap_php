<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars($title ?? 'Bookstore') ?> - <?= htmlspecialchars(APP_NAME) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/home.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

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
<body>

<!-- Hero Banner -->
<section class="hero-banner">
    <div class="banner-container">
        <div class="banner-slider" id="bannerSlider">
            <!-- Slide 1 -->
            <div class="banner-slide active">
                <div class="banner-image">
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80&format=webp" alt="Sách hay mỗi ngày" loading="lazy">
                </div>
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <div class="container">
                        <h2>Khám phá thế giới sách</h2>
                        <p>Hàng ngàn đầu sách hay với giá tốt nhất</p>
                        <a href="<?= $baseUrl ?>index.php?page=books" class="btn btn-primary">Mua sắm ngay</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="banner-slide">
                <div class="banner-image">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80&format=webp" alt="Sách mới phát hành" loading="lazy">
                </div>
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <div class="container">
                        <h2>Sách mới phát hành</h2>
                        <p>Cập nhật những cuốn sách hot nhất thị trường</p>
                        <a href="<?= $baseUrl ?>index.php?page=books" class="btn btn-primary">Xem ngay</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="banner-slide">
                <div class="banner-image">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80&format=webp" alt="Khuyến mãi đặc biệt" loading="lazy">
                </div>
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <div class="container">
                        <h2>Khuyến mãi đặc biệt</h2>
                        <p>Giảm giá lên đến 50% cho tất cả sách</p>
                        <a href="<?= $baseUrl ?>index.php?page=books" class="btn btn-primary">Khám phá ngay</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button class="banner-nav banner-prev" id="bannerPrev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="banner-nav banner-next" id="bannerNext">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Dots Indicator -->
        <div class="banner-dots" id="bannerDots">
            <span class="dot active" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <div class="content-layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-section">
                    <h3>Danh mục sách</h3>
                    <ul class="category-list">
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Sách quốc văn</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Sách ngoại văn</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Văn học</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Kinh tế</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Kỹ năng sống</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Tiểu thuyết</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Tâm lý học</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Lịch sử</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Khoa học</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books">Thiếu nhi</a></li>
                    </ul>
                </div>

                <div class="sidebar-section">
                    <h3>Khuyến mãi</h3>
                    <div class="promo-box">
                        <img src="<?= $baseUrl ?>assets/images/books/tuoitredanggiabaonhieu.jpg" alt="Khuyến mãi">
                        <h4>Giảm 20% sách mới</h4>
                        <p>Áp dụng cho tất cả sách mới phát hành</p>
                    </div>
                </div>
            </aside>

            <!-- Main Area -->
            <div class="main-area">
                <!-- Debug info (remove after testing) -->
                <?php if (empty($bestSellingBooks) && empty($newBooks) && empty($discountBooks)): ?>
                    <div class="alert alert-info" style="padding: 20px; margin: 20px 0; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px;">
                        <h3>Chưa có sách trong database</h3>
                        <p>Vui lòng:</p>
                        <ol>
                            <li>Chạy file SQL: <code>database/create_books_table.sql</code> để tạo bảng</li>
                            <li>Chạy file SQL: <code>database/insert_sample_books.sql</code> để thêm dữ liệu mẫu</li>
                            <li>Hoặc truy cập <a href="<?= $baseUrl ?>admin/create">trang admin</a> để thêm sách mới</li>
                        </ol>
                    </div>
                <?php endif; ?>
                
                <!-- Best Selling Books Section -->
                <?php if (!empty($bestSellingBooks)): ?>
                <section class="books-section">
                    <div class="section-header">
                        <h2>Sách bán chạy</h2>
                        <a href="<?= $baseUrl ?>index.php?page=books&type=bestselling" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="books-grid" id="debug-grid-1">
                        <!-- DEBUG: Số lượng sách: <?= count($bestSellingBooks) ?> -->
                        <?php foreach ($bestSellingBooks as $book): ?>
                            <div class="book-card">
                                <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>" class="book-link">
                                    <div class="book-image-wrapper">
                                        <img 
                                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 280'%3E%3Crect fill='%23f8f9fa' width='200' height='280'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3ELoading...%3C/text%3E%3C/svg%3E" 
                                            data-src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                                            alt="<?= htmlspecialchars($book->title) ?>"
                                            class="book-cover lazy"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="book-info">
                                        <h3 class="book-title"><?= htmlspecialchars($book->title) ?></h3>
                                        <p class="book-author"><?= htmlspecialchars($book->author) ?></p>
                                        <div class="book-price-container">
                                            <?php if ($book->hasDiscount()): ?>
                                                <p class="book-price-original"><?= $book->getFormattedOriginalPrice() ?> đ</p>
                                                <p class="book-price-discount"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php else: ?>
                                                <p class="book-price"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                                <?php if ($book->hasDiscount()): ?>
                                    <span class="book-discount-badge">-<?= $book->getFormattedDiscount() ?>%</span>
                                <?php endif; ?>
                                <form method="post" action="<?= $baseUrl ?>cart/add" class="add-to-cart-form" onsubmit="addToCart(event, this, '<?= htmlspecialchars($book->id) ?>'); return false;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                                    <input type="hidden" name="qty" value="1" />
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- New Books Section -->
                <?php if (!empty($newBooks)): ?>
                <section class="books-section">
                    <div class="section-header">
                        <h2>Sách mới phát hành</h2>
                        <a href="<?= $baseUrl ?>index.php?page=books" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="books-grid">
                        <?php foreach ($newBooks as $book): ?>
                            <div class="book-card">
                                <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>" class="book-link">
                                    <div class="book-image-wrapper">
                                        <img 
                                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 280'%3E%3Crect fill='%23f8f9fa' width='200' height='280'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3ELoading...%3C/text%3E%3C/svg%3E" 
                                            data-src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                                            alt="<?= htmlspecialchars($book->title) ?>"
                                            class="book-cover lazy"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="book-info">
                                        <h3 class="book-title"><?= htmlspecialchars($book->title) ?></h3>
                                        <p class="book-author"><?= htmlspecialchars($book->author) ?></p>
                                        <div class="book-price-container">
                                            <?php if ($book->hasDiscount()): ?>
                                                <p class="book-price-original"><?= $book->getFormattedOriginalPrice() ?> đ</p>
                                                <p class="book-price-discount"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php else: ?>
                                                <p class="book-price"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                                <?php if ($book->hasDiscount()): ?>
                                    <span class="book-discount-badge">-<?= $book->getFormattedDiscount() ?>%</span>
                                <?php endif; ?>
                                <form method="post" action="<?= $baseUrl ?>cart/add" class="add-to-cart-form" onsubmit="addToCart(event, this, '<?= htmlspecialchars($book->id) ?>'); return false;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                                    <input type="hidden" name="qty" value="1" />
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Discount Books Section -->
                <?php if (!empty($discountBooks)): ?>
                <section class="books-section">
                    <div class="section-header">
                        <h2>Sách đang giảm giá</h2>
                        <a href="<?= $baseUrl ?>index.php?page=books" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="books-grid">
                        <?php foreach ($discountBooks as $book): ?>
                            <div class="book-card">
                                <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>" class="book-link">
                                    <div class="book-image-wrapper">
                                        <img 
                                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 280'%3E%3Crect fill='%23f8f9fa' width='200' height='280'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3ELoading...%3C/text%3E%3C/svg%3E" 
                                            data-src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                                            alt="<?= htmlspecialchars($book->title) ?>"
                                            class="book-cover lazy"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="book-info">
                                        <h3 class="book-title"><?= htmlspecialchars($book->title) ?></h3>
                                        <p class="book-author"><?= htmlspecialchars($book->author) ?></p>
                                        <div class="book-price-container">
                                            <?php if ($book->hasDiscount()): ?>
                                                <p class="book-price-original"><?= $book->getFormattedOriginalPrice() ?> đ</p>
                                                <p class="book-price-discount"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php else: ?>
                                                <p class="book-price"><?= $book->getFormattedPrice() ?> đ</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                                <?php if ($book->hasDiscount()): ?>
                                    <span class="book-discount-badge">-<?= $book->getFormattedDiscount() ?>%</span>
                                <?php endif; ?>
                                <form method="post" action="<?= $baseUrl ?>cart/add" class="add-to-cart-form" onsubmit="addToCart(event, this, '<?= htmlspecialchars($book->id) ?>'); return false;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                                    <input type="hidden" name="qty" value="1" />
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- News & Events -->
<section class="news-section">
    <div class="container">
        <div class="section-header">
            <h2>Tin tức & Sự kiện</h2>
            <a href="<?= $baseUrl ?>index.php?page=books" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php if (isset($news) && !empty($news)): ?>
        <div class="news-grid">
            <?php foreach ($news as $article): ?>
            <article class="news-card">
                <img src="<?= htmlspecialchars($article->image ?? '') ?>" alt="<?= htmlspecialchars($article->title ?? '') ?>">
                <div class="news-content">
                    <h3><a href="<?= $baseUrl ?>index.php?page=books"><?= htmlspecialchars($article->title ?? '') ?></a></h3>
                    <p><?= htmlspecialchars($article->excerpt) ?></p>
                    <span class="news-date"><?= date('d/m/Y', strtotime($article->createdAt)) ?></span>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- DEBUG SCRIPT -->
<script>
(function() {
    function debugGrids() {
        console.log('=== DEBUG GRID ===');
        const grids = document.querySelectorAll('.books-grid');
        console.log('Tìm thấy', grids.length, 'grid(s)');
        
        grids.forEach((grid, index) => {
            const styles = window.getComputedStyle(grid);
            console.log(`Grid ${index + 1}:`, {
                id: grid.id || 'no-id',
                display: styles.display,
                gridTemplateColumns: styles.gridTemplateColumns,
                gap: styles.gap,
                width: styles.width,
                children: grid.children.length,
                className: grid.className
            });
            
            // Kiểm tra nếu không phải grid
            if (styles.display !== 'grid') {
                console.error('❌ Grid không hoạt động! Display:', styles.display);
                // Force apply grid
                grid.style.display = 'grid';
                grid.style.gridTemplateColumns = 'repeat(4, 1fr)';
                grid.style.gap = '25px';
            } else {
                console.log('✅ Grid hoạt động đúng');
            }
        });
        
        // Kiểm tra CSS
        const homeCss = Array.from(document.querySelectorAll('link[rel="stylesheet"]')).find(link => 
            link.href.includes('home.css')
        );
        console.log('home.css:', homeCss ? homeCss.href : '❌ NOT FOUND');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', debugGrids);
    } else {
        debugGrids();
    }
})();

// Add to cart with AJAX
function addToCart(event, form, bookId) {
    event.preventDefault();
    
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update cart count
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            // Show toast notification
            if (window.showToast) {
                window.showToast(data.message, 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        console.error('Error adding to cart:', err);
        let errorMessage = 'Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại.';
        
        // Try to extract more detailed error message
        if (err.message) {
            if (err.message.includes('Unexpected token')) {
                errorMessage = 'Lỗi định dạng dữ liệu từ server. Vui lòng thử lại.';
            } else if (err.message.includes('HTTP error')) {
                errorMessage = 'Lỗi kết nối đến server. Vui lòng thử lại.';
            } else {
                errorMessage = err.message;
            }
        }
        
        if (window.showToast) {
            window.showToast(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
    })
    .finally(() => {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<!-- Lazy Loading JavaScript -->
<script>
// Lazy loading for images
document.addEventListener('DOMContentLoaded', function() {
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img.lazy');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Load the actual image
                    img.src = img.dataset.src;
                    
                    // Add fade-in effect
                    img.style.opacity = '0';
                    img.onload = function() {
                        img.style.transition = 'opacity 0.3s ease-in-out';
                        img.style.opacity = '1';
                    };
                    
                    // Remove lazy class and stop observing
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
    }
});
</script>

</body>
</html>
