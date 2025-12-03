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
                        <div class="promotion-timer" data-end="<?= $promotion['end_date'] ?>">
                            <i class="far fa-clock"></i>
                            <span class="countdown">Còn lại: 23:45:12</span>
                        </div>
                    </div>
                    <div class="promotion-content">
                        <div class="promotion-type">
                            <span class="promotion-type-badge <?= $promotion['type'] ?>">
                                <?= $this->getPromotionTypeName($promotion['type']) ?>
                            </span>
                        </div>
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

<!-- Flash Sales -->
<section class="promotions-flash-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Flash Sale</h2>
            <div class="flash-sale-timer">
                <i class="fas fa-bolt"></i>
                <span>Kết thúc trong: <span class="countdown" data-end="2024-12-31">23:45:12</span></span>
            </div>
        </div>
        
        <div class="flash-sales-grid">
            <?php foreach ($flashSales as $flash): ?>
                <div class="flash-sale-card">
                    <div class="flash-sale-image">
                        <img src="<?= $baseUrl ?>assets/images/promotions/<?= $flash['image'] ?>" 
                             alt="<?= htmlspecialchars($flash['title']) ?>"
                             loading="lazy">
                        <div class="flash-sale-percentage">
                            -<?= $flash['discount'] ?>%
                        </div>
                    </div>
                    <div class="flash-sale-content">
                        <h4 class="flash-sale-title"><?= htmlspecialchars($flash['title']) ?></h4>
                        <p class="flash-sale-description"><?= htmlspecialchars($flash['description']) ?></p>
                        <button class="flash-sale-btn" onclick="shopFlashSale(<?= $flash['id'] ?>)">
                            Săn sale ngay
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Vouchers -->
<section class="promotions-vouchers-section">
    <div class="container">
        <h2 class="section-title">Voucher ưu đãi</h2>
        <div class="vouchers-grid">
            <?php foreach ($vouchers as $voucher): ?>
                <div class="voucher-card">
                    <div class="voucher-left">
                        <div class="voucher-value">
                            <?php if ($voucher.
                           TypeEnum::Voucher ===    </div . . . . . . . . . .. . . ..
                            <. . . .错误的JSON格式. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .Color: .ifica..
                            <. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .BorderColor: #. . .oge. . .);
                        </divper: . . .<|code_suffix|>
I see there are JSON parsing errors occurring when trying to create the promotions view file. Let me add the routes first to fix the 404 error, then we can create the view separately:
<tool_call>edit
<arg_key>explanation</arg_key>
<arg_value>Add promotions routes to fix 404 error
