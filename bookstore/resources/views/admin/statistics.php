<?php
// Authentication is handled by AdminController via AdminHelper::requireAdmin()
// No need for manual session checks here
?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Thống kê Doanh thu</h2>
            <div class="filter-controls">
                <select id="periodFilter" class="form-select" onchange="updateStatistics()">
                    <option value="7">7 ngày qua</option>
                    <option value="30" selected>30 ngày qua</option>
                    <option value="90">3 tháng qua</option>
                    <option value="365">1 năm qua</option>
                </select>
            </div>
        </div>
        
        <!-- Revenue Overview Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Tổng doanh thu</div>
                    <div class="stat-value"><?= number_format($totalRevenue ?? 0, 0, ',', '.') ?> đ</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Đơn hàng thành công</div>
                    <div class="stat-value"><?= number_format($successfulOrders ?? 0) ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Giá trị trung bình/đơn</div>
                    <div class="stat-value"><?= number_format($averageOrderValue ?? 0, 0, ',', '.') ?> đ</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Tỷ lệ chuyển đổi</div>
                    <div class="stat-value"><?= number_format($conversionRate ?? 0, 1) ?>%</div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-card">
                <h3>Doanh thu theo thời gian</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
            
            <div class="chart-card">
                <h3>Sản phẩm bán chạy</h3>
                <div class="top-products">
                    <?php if (!empty($topProducts)): ?>
                        <?php foreach ($topProducts as $index => $product): ?>
                            <div class="product-item">
                                <span class="rank"><?= $index + 1 ?></span>
                                <span class="product-name"><?= htmlspecialchars($product['ten_sach']) ?></span>
                                <span class="sales-count"><?= $product['so_luong_ban'] ?> sold</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Chưa có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Category Performance -->
        <div class="chart-card">
            <h3>Hiệu suất theo danh mục</h3>
            <canvas id="categoryChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Recent Orders Table -->
        <div class="chart-card">
            <h3>Đơn hàng gần đây</h3>
            <div class="table-container">
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
                        <?php if (!empty($recentOrders)): ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['ma_don_hang'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($order['ten_khach_hang'] ?? '') ?></td>
                                    <td><?= number_format($order['tong_tien'] ?? 0, 0, ',', '.') ?> đ</td>
                                    <td>
                                        <span class="badge badge-<?= getBadgeClass($order['trang_thai'] ?? 'pending') ?>">
                                            <?= getStatusLabel($order['trang_thai'] ?? 'pending') ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($order['ngay_dat'] ?? 'now')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Chưa có đơn hàng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.filter-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.form-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background: white;
}

.charts-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

.chart-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.chart-card h3 {
    margin: 0 0 15px 0;
    color: #2b6cb0;
    font-size: 16px;
}

.top-products {
    max-height: 300px;
    overflow-y: auto;
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}

.product-item:last-child {
    border-bottom: none;
}

.rank {
    background: #2b6cb0;
    color: white;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.product-name {
    flex: 1;
    margin: 0 15px;
    font-size: 14px;
}

.sales-count {
    font-weight: bold;
    color: #10b981;
}

.table-container {
    overflow-x: auto;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.badge-success {
    background: #10b981;
    color: white;
}

.badge-pending {
    background: #f59e0b;
    color: white;
}

.badge-cancelled {
    background: #ef4444;
    color: white;
}

.text-center {
    text-align: center;
    padding: 20px 0;
    color: #6b7280;
}

.admin-card-body a {
    color: #3b82f6;
    text-decoration: none;
}

.admin-card-body a:hover {
    text-decoration: underline;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample data - replace with actual data from backend
const revenueData = {
    labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
    datasets: [{
        label: 'Doanh thu',
        data: [12000000, 19000000, 15000000, 25000000, 22000000, 30000000],
        borderColor: '#2b6cb0',
        backgroundColor: 'rgba(43, 108, 176, 0.1)',
        tension: 0.4
    }]
};

const categoryData = {
    labels: ['Văn học', 'Kinh tế', 'Kỹ năng', 'Thiếu nhi', 'Khoa học'],
    datasets: [{
        label: 'Số lượng bán',
        data: [65, 59, 80, 81, 56],
        backgroundColor: [
            '#2b6cb0',
            '#10b981',
            '#f59e0b',
            '#ef4444',
            '#8b5cf6'
        ]
    }]
};

// Initialize charts
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: revenueData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        }
    }
});

const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: categoryData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function updateStatistics() {
    const period = document.getElementById('periodFilter').value;
    // Reload page with new period
    window.location.href = '<?= $baseUrl ?>admin/statistics?period=' + period;
}

function getBadgeClass(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'pending': return 'pending';
        case 'cancelled': return 'cancelled';
        default: return 'pending';
    }
}

function getStatusLabel(status) {
    switch(status) {
        case 'completed': return 'Hoàn thành';
        case 'pending': return 'Chờ xử lý';
        case 'cancelled': return 'Đã hủy';
        default: return 'Chờ xử lý';
    }
}
</script>

