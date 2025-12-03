<!-- Page Header -->
<section class="page-header gifts-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Quà tặng</h1>
            <p>Những món quà ý nghĩa và đặc biệt cho mọi dịp</p>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="gifts-categories-section">
    <div class="container">
        <h2 class="section-title">Danh mục quà tặng</h2>
        <div class="gifts-categories-grid">
            <?php foreach ($categories as $category): ?>
                <div class="gift-category-card">
                    <div class="gift-category-icon">
                        <i class="fas <?= $category['icon'] ?>"></i>
                    </div>
                    <h3 class="gift-category-name"><?= htmlspecialchars($category['name']) ?></h3>
                    <p class="gift-category-description"><?= htmlspecialchars($category['description']) ?></p>
                    <div class="gift-category-meta">
                        <span class="gift-category-count"><?= $category['count'] ?> sản phẩm</span>
                    </div>
                    <a href="<?= $baseUrl ?>gifts/category/<?= $category['id'] ?>" class="gift-category-link">
                        Xem chi tiết <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="gifts-featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Quà tặng nổi bật</h2>
            <div class="section-actions">
                <a href="<?= $baseUrl ?>gifts" class="view-all">Xem tất cả</a>
            </div>
        </div>
        
        <div class="gifts-products-grid">
            <?php foreach (array_slice($products, 0, 8) as $product): ?>
                <div class="gift-product-card">
                    <div class="gift-product-image">
                        <img src="<?= $baseUrl ?>assets/images/gifts/<?= $product['image'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             loading="lazy">
                        <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                            <div class="gift-product-badge sale">-<?= $product['discount'] ?>%</div>
                        <?php endif; ?>
                        <div class="gift-product-overlay">
                            <button class="gift-quick-view-btn">
                                <i class="fas fa-eye"></i>
                                Xem nhanh
                            </button>
                            <button class="gift-wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="gift-product-content">
                        <h3 class="gift-product-name">
                            <a href="<?= $baseUrl ?>gifts/detail/<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h3>
                        <p class="gift-product-description"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="gift-product-price">
                            <?php if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                <span class="gift-old-price"><?= number_format($product['old_price']) ?>đ</span>
                            <?php endif; ?>
                            <span class="gift-current-price"><?= number_format($product['price']) ?>đ</span>
                        </div>
                        <div class="gift-product-rating">
                            <div class="gift-stars"><?= str_repeat('★', floor($product['rating'])) ?><?= str_repeat('☆', 5 - floor($product['rating'])) ?></div>
                            <span class="gift-rating-count">(<?= $product['reviews'] ?>)</span>
                        </div>
                        <button class="gift-add-to-cart-btn" onclick="addToGiftCart(event, <?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Special Offers -->
<section class="gifts-offers-section">
    <div class="container">
        <h2 class="section-title">Ưu đãi đặc biệt</h2>
        <div class="gifts-offers-grid">
            <div class="gift-offer-card">
                <div class="gift-offer-content">
                    <h3>Giảm giá 20%</h3>
                    <p>Cho tất cả quà tặng sinh nhật trong tuần này</p>
                    <a href="<?= $baseUrl ?>gifts/category/1" class="gift-offer-btn">Khám phá ngay</a>
                </div>
                <div class="gift-offer-badge">
                    <span>-20%</span>
                </div>
            </div>
            
            <div class="gift-offer-card">
                <div class="gift-offer-content">
                    <h3>Miễn phí vận chuyển</h3>
                    <p>Cho đơn hàng quà tặng từ 500.000đ</p>
                    <a href="<?= $baseUrl ?>gifts" class="gift-offer-btn">Mua ngay</a>
                </div>
                <div class="gift-offer-badge shipping">
                    <span>Freeship</span>
                </div>
            </div>
            
            <div class="gift-offer-card">
                <div class="gift-offer-content">
                    <h3>Gói quà miễn phí</h3>
                    <p>Đóng gói quà tặng chuyên nghiệp</p>
                    <a href="<?= $baseUrl ?>gifts" class="gift-offer-btn">Tìm hiểu thêm</a>
                </div>
                <div class="gift-offer-badge gift">
                    <span>Gift</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="gifts-why-section">
    <div class="container">
        <h2 class="section-title">Tại sao chọn quà tặng của chúng tôi?</h2>
        <div class="gifts-why-grid">
            <div class="gift-why-item">
                <div class="gift-why-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <h3>Đa dạng mẫu mã</h3>
                <p>Hàng trăm mẫu quà tặng đẹp mắt và ý nghĩa</p>
            </div>
            <div class="gift-why-item">
                <div class="gift-why-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Chất lượng cao</h3>
                <p>Sản phẩm được tuyển chọn kỹ lưỡng, chất lượng đảm bảo</p>
            </div>
            <div class="gift-why-item">
                <div class="gift-why-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Đóng gói chuyên nghiệp</h3>
                <p>Dịch vụ gói quà miễn phí, sang trọng</p>
            </div>
            <div class="gift-why-item">
                <div class="gift-why-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Giao hàng nhanh</h3>
                <p>Giao hàng hỏa tốc trong ngày cho đơn khẩn cấp</p>
            </div>
        </div>
    </div>
</section>

<!-- Gift Guide -->
<section class="gifts-guide-section">
    <div class="container">
        <div class="gifts-guide-content">
            <div class="gifts-guide-text">
                <h2>Cần tư vấn chọn quà?</h2>
                <p>Đội ngũ chuyên viên của chúng tôi sẵn sàng giúp bạn chọn được món quà hoàn hảo nhất cho từng dịp và từng người nhận.</p>
                <div class="gifts-guide-features">
                    <div class="gifts-guide-feature">
                        <i class="fas fa-phone"></i>
                        <span>Hotline: 1900-1234</span>
                    </div>
                    <div class="gifts-guide-feature">
                        <i class="fas fa-envelope"></i>
                        <span>Email: gift@bookstore.com</span>
                    </div>
                    <div class="gifts-guide-feature">
                        <i class="fas fa-comments"></i>
                        <span>Chat tư vấn 24/7</span>
                    </div>
                </div>
                <button class="gifts-guide-btn">Nhận tư vấn miễn phí</button>
            </div>
            <div class="gifts-guide-image">
                <img src="<?= $baseUrl ?>assets/images/gifts/gift-guide.jpg" alt="Gift Guide">
            </div>
        </div>
    </div>
</section>

<script>
function addToGiftCart(event, productId) {
    event.preventDefault();
    // Add to cart logic here
    alert('Đã thêm quà tặng vào giỏ hàng!');
}
</script>
