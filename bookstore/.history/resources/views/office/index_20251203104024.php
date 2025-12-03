<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Văn phòng phẩm</h1>
            <p>Mọi thứ bạn cần cho không gian làm việc hiệu quả và chuyên nghiệp</p>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas <?= $category['icon'] ?>"></i>
                    </div>
                    <h3 class="category-name"><?= htmlspecialchars($category['name']) ?></h3>
                    <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
                    <a href="<?= $baseUrl ?>office/category/<?= $category['id'] ?>" class="category-link">
                        Xem sản phẩm <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <div class="section-actions">
                <a href="<?= $baseUrl ?>office" class="view-all">Xem tất cả</a>
            </div>
        </div>
        
        <div class="products-grid">
            <?php foreach (array_slice($products, 0, 8) as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= $baseUrl ?>assets/images/office/<?= $product['image'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             loading="lazy">
                        <?php if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                            <div class="product-badge sale">
                                -<?= round(($product['old_price'] - $product['price']) / $product['old_price'] * 100) ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-content">
                        <h3 class="product-name">
                            <a href="<?= $baseUrl ?>office/detail/<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h3>
                        <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="product-price">
                            <?php if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                <span class="old-price"><?= number_format($product['old_price']) ?>đ</span>
                            <?php endif; ?>
                            <span class="current-price"><?= number_format($product['price']) ?>đ</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary add-to-cart-btn" 
                                    onclick="addToOfficeCart(event, <?= $product['id'] ?>)">
                                <i class="fas fa-shopping-cart"></i>
                                Thêm vào giỏ
                            </button>
                            <button class="btn btn-outline wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
        <div class="benefits-grid">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Giao hàng nhanh</h3>
                <p>Giao hàng trong ngày cho đơn hàng trong nội thành</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Chất lượng đảm bảo</h3>
                <p>Sản phẩm chính hãng, chất lượng cao</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3>Giá tốt nhất</h3>
                <p>Giá cạnh tranh, nhiều ưu đãi hấp dẫn</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3>Đổi trả dễ dàng</h3>
                <p>Chính sách đổi trả linh hoạt trong 7 ngày</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section office-newsletter">
    <div class="container">
        <div class="newsletter-content">
            <h2>Đăng ký nhận ưu đãi</h2>
            <p>Nhận thông tin khuyến mãi và sản phẩm mới nhất</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Email của bạn" required>
                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>
        </div>
    </div>
</section>

<script>
function addToOfficeCart(event, productId) {
    event.preventDefault();
    // Add to cart logic here
    alert('Đã thêm sản phẩm vào giỏ hàng!');
}
</script>
