<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?> đ</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Doanh thu tháng này</div>
                <div class="stat-value"><?= number_format($stats['monthly_revenue'] ?? 0, 0, ',', '.') ?> đ</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng đơn hàng</div>
                <div class="stat-value"><?= number_format($stats['total_orders'] ?? 0) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng khách hàng</div>
                <div class="stat-value"><?= number_format($stats['total_customers'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Thống kê doanh thu</h2>
        </div>
        <div class="stats-content">
            <div class="stats-row">
                <div class="stats-item">
                    <h4>Tổng doanh thu tất cả thời gian</h4>
                    <p class="stats-number"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?> đ</p>
                </div>
                <div class="stats-item">
                    <h4>Doanh thu tháng hiện tại</h4>
                    <p class="stats-number"><?= number_format($stats['monthly_revenue'] ?? 0, 0, ',', '.') ?> đ</p>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-item">
                    <h4>Tổng số đơn hàng</h4>
                    <p class="stats-number"><?= number_format($stats['total_orders'] ?? 0) ?></p>
                </div>
                <div class="stats-item">
                    <h4>Tổng số khách hàng</h4>
                    <p class="stats-number"><?= number_format($stats['total_customers'] ?? 0) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
