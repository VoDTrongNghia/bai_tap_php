<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng sản phẩm</div>
                <div class="stat-value"><?= number_format($totalBooks ?? 0) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng đơn hàng</div>
                <div class="stat-value"><?= number_format($totalOrders ?? 0) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng khách hàng</div>
                <div class="stat-value"><?= number_format($totalUsers ?? 0) ?></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value"><?= number_format($totalRevenue ?? 0, 0, ',', '.') ?> đ</div>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Tổng quan hệ thống</h2>
        </div>
        <p>Chào mừng đến với trang quản trị. Sử dụng menu bên trái để quản lý các chức năng.</p>
        
        <?php if (!empty($recentOrders)): ?>
        <div class="recent-orders">
            <h3>Đơn hàng gần đây</h3>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?? '' ?></td>
                            <td><?= htmlspecialchars($order['customer_name'] ?? '') ?></td>
                            <td><?= number_format($order['total'] ?? 0) ?> đ</td>
                            <td>
                                <span class="status-badge status-<?= $order['status'] ?? 'pending' ?>">
                                    <?= ucfirst($order['status'] ?? 'pending') ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($order['created_at'] ?? 'now')) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
