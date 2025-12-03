<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Tin tức & Sự kiện</h1>
            <p>Cập nhật thông tin mới nhất từ nhà sách và các sự kiện văn hóa đọc</p>
        </div>
    </div>
</section>

<!-- News Content -->
<section class="news-section">
    <div class="container">
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
                <article class="news-card">
                    <div class="news-image">
                        <img src="<?= $baseUrl ?>assets/images/news/<?= $item['image'] ?>" 
                             alt="<?= htmlspecialchars($item['title']) ?>"
                             loading="lazy">
                        <div class="news-category"><?= htmlspecialchars($item['category']) ?></div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="far fa-calendar"></i>
                                <?= date('d/m/Y', strtotime($item['date'])) ?>
                            </span>
                            <span class="news-author">
                                <i class="far fa-user"></i>
                                <?= htmlspecialchars($item['author']) ?>
                            </span>
                        </div>
                        <h3 class="news-title">
                            <a href="<?= $baseUrl ?>news/detail/<?= $item['id'] ?>">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </h3>
                        <p class="news-excerpt"><?= htmlspecialchars($item['excerpt']) ?></p>
                        <a href="<?= $baseUrl ?>news/detail/<?= $item['id'] ?>" class="read-more">
                            Đọc thêm <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
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
    </div>
</section>

<!-- Newsletter Subscription -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>Đăng ký nhận tin</h2>
            <p>Nhận thông tin về sách mới, khuyến mãi và sự kiện độc quyền</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Email của bạn" required>
                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>
        </div>
    </div>
</section>
