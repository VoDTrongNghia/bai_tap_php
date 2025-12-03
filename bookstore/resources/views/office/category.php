<!-- Breadcrumb -->
<nav class="breadcrumb">
    <div class="container">
        <a href="<?= $baseUrl ?>">Trang chủ</a>
        <span class="separator">/</span>
        <a href="<?= $baseUrl ?>office">Văn phòng phẩm</a>
        <span class="separator">/</span>
        <span class="current"><?= htmlspecialchars($category['name']) ?></span>
    </div>
</nav>

<!-- Category Header -->
<section class="category-header">
    <div class="container">
        <div class="category-header-content">
            <div class="category-info">
                <h1><?= htmlspecialchars($category['name']) ?></h1>
                <p><?= htmlspecialchars($category['description']) ?></p>
                <div class="category-stats">
                    <span><?= count($products) ?> sản phẩm</span>
                    <span>Đã bán: <?= number_format(array_sum(array_column($products, 'sold') ?? [])) ?>+</span>
                </div>
            </div>
            <div class="category-image">
                <img src="<?= $baseUrl ?>assets/images/office/<?= $category['image'] ?>" 
                     alt="<?= htmlspecialchars($category['name']) ?>"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- Filters and Products -->
<section class="category-content">
    <div class="container">
        <div class="category-layout">
            <!-- Filters Sidebar -->
            <aside class="filters-sidebar">
                <div class="filter-section">
                    <h3 class="filter-title">Lọc theo giá</h3>
                    <div class="price-filter">
                        <input type="range" min="0" max="500000" step="10000" value="250000" class="price-range">
                        <div class="price-display">
                            <span>0đ - <span id="price-value">250.000đ</span></span>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Thương hiệu</h3>
                    <div class="brand-filter">
                        <label><input type="checkbox" name="brand" value="thien-long"> Thiên Long</label>
                        <label><input type="checkbox" name="brand" value="bibi"> Bến Nghé</label>
                        <label><input type="checkbox" name="brand" value="deli"> Deli</label>
                        <label><input type="checkbox" name="brand" value="double-a"> Double A</label>
                        <label><input type="checkbox" name="brand" value="hong-ha"> Hồng Hà</label>
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Đánh giá</h3>
                    <div class="rating-filter">
                        <label><input type="checkbox" name="rating" value="5"> <span class="stars">★★★★★</span></label>
                        <label><input type="checkbox" name="rating" value="4"> <span class="stars">★★★★☆</span> trở lên</label>
                        <label><input type="checkbox" name="rating" value="3"> <span class="stars">★★★☆☆</span> trở lên</label>
                    </div>
                </div>

                <button class="btn btn-outline btn-block">Áp dụng bộ lọc</button>
            </aside>

            <!-- Products Grid -->
            <main class="products-main">
                <div class="products-header">
                    <div class="results-count">
                        Hiển thị <?= count($products) ?> sản phẩm
                    </div>
                    <div class="sort-options">
                        <select class="sort-select">
                            <option value="default">Sắp xếp theo</option>
                            <option value="price-asc">Giá: Thấp đến cao</option>
                            <option value="price-desc">Giá: Cao đến thấp</option>
                            <option value="name-asc">Tên: A-Z</option>
                            <option value="best-selling">Bán chạy nhất</option>
                        </select>
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="products-grid" id="products-container">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?= $baseUrl ?>assets/images/office/<?= $product['image'] ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     loading="lazy">
                                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                    <div class="product-badge sale">-<?= $product['discount'] ?>%</div>
                                <?php endif; ?>
                                <div class="product-actions-overlay">
                                    <button class="btn btn-sm btn-outline quick-view-btn">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline wishlist-btn">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
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
                                <div class="product-rating">
                                    <div class="stars">★★★★☆</div>
                                    <span class="rating-count">(<?= $product['reviews'] ?? 0 ?>)</span>
                                </div>
                                <button class="btn btn-primary btn-block add-to-cart-btn" 
                                        onclick="addToOfficeCart(event, <?= $product['id'] ?>)">
                                    <i class="fas fa-shopping-cart"></i>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="pagination-number active">1</span>
                    <span class="pagination-number">2</span>
                    <span class="pagination-number">3</span>
                    <button class="pagination-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </main>
        </div>
    </div>
</section>

<script>
// Price range slider
const priceRange = document.querySelector('.price-range');
const priceValue = document.getElementById('price-value');

if (priceRange && priceValue) {
    priceRange.addEventListener('input', function() {
        priceValue.textContent = new Intl.NumberFormat('vi-VN').format(this.value) + 'đ';
    });
}

// View toggle
const viewBtns = document.querySelectorAll('.view-btn');
const productsContainer = document.getElementById('products-container');

viewBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        viewBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const view = this.dataset.view;
        if (view === 'list') {
            productsContainer.classList.add('list-view');
        } else {
            productsContainer.classList.remove('list-view');
        }
    });
});

function addToOfficeCart(event, productId) {
    event.preventDefault();
    // Add to cart logic here
    alert('Đã thêm sản phẩm vào giỏ hàng!');
}
</script>
