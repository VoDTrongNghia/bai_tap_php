<?php /** @var int $totalBooks */ ?>
<?php /** @var int $totalOrders */ ?>
<?php /** @var int $totalUsers */ ?>
<?php /** @var float $totalRevenue */ ?>
<div class="admin-dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng sản phẩm</div>
                <div class="stat-value"><?= number_format($totalBooks) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng đơn hàng</div>
                <div class="stat-value"><?= number_format($totalOrders) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng khách hàng</div>
                <div class="stat-value"><?= number_format($totalUsers) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</div>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Tổng quan hệ thống</h2>
        </div>
        <p>Chào mừng đến với trang quản trị. Sử dụng menu bên trái để quản lý các chức năng.</p>
    </div>
</div>
