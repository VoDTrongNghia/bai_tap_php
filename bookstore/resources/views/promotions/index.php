<!-- Page Header -->
<section class="page-header promotions-header">
    <div class="container">
        <div class="page-header-content">
            <h1>Khuyến mãi</h1>
            <p>Những ưu đãi tốt nhất cho sách và văn phòng phẩm</p>
        </div>
    </div>
</section>

<!-- Active Promotions -->
<section class="promotions-active-section">
    <div class="container">
        <h2 class="section-title">Khuyến mãi đang diễn ra</h2>
        <div class="promotions-grid">
            <?php foreach ($activePromotions as $promotion): ?>
                <div class="promotion-card">
                    <div class="promotion-image">
                        <img src="<?= $baseUrl ?>assets/images/promotions/<?= $promotion['image'] ?>" 
                             alt="<?= htmlspecialchars($promotion['title']) ?>"
                             loading="lazy">
                        <div class="promotion-badge">
                            <?php if ($promotion['discount'] > 0): ?>
                                -<?= $promotion['discount'] ?>%
                            <?php else: ?>
                                <?= ucfirst($promotion['type']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="promotion-content">
                        <h3 class="promotion-title">
                            <a href="<?= $baseUrl ?>promotions/detail/<?= $promotion['id'] ?>">
                                <?= htmlspecialchars($promotion['title']) ?>
                            </a>
                        </h3>
                        <p class="promotion-description"><?= htmlspecialchars($promotion['description']) ?></p>
                        <div class="promotion-date">
                            <i class="far fa-calendar"></i>
                            <?= date('d/m/Y', strtotime($promotion['start_date'])) ?> - 
                            <?= date('d/m/Y', strtotime($promotion['end_date'])) ?>
                        </div>
                        <div class="promotion-actions">
                            <a href="<?= $baseUrl ?>promotions/detail/<?= $promotion['id'] ?>" class="promotion-view-btn">
                                Xem chi tiết
                            </a>
                            <button class="promotion-shop-btn" onclick="shopPromotion(<?= $promotion['id'] ?>)">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
function shopPromotion(id) {
    window.location.href = '<?= $baseUrl ?>books';
}
</script>
