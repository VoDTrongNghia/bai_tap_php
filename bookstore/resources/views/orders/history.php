<?php /** @var App\Models\Order[] $orders */ ?>
<?php /** @var App\Models\Order|null $searchOrder */ ?>
<?php /** @var bool $isLoggedIn */ ?>
<div class="orders-page">
    <h1>Lịch sử đơn hàng</h1>
    
    <?php if (!$isLoggedIn): ?>
        <div class="guest-search-section">
            <h2>Tra cứu đơn hàng</h2>
            <p>Nhập số điện thoại hoặc mã đơn hàng để tra cứu:</p>
            <form method="GET" action="<?= $baseUrl ?>orders" class="order-search-form">
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="<?= htmlspecialchars($_GET['phone'] ?? '') ?>"
                        placeholder="Nhập số điện thoại"
                        class="form-input"
                    />
                </div>
                <div class="form-group">
                    <label for="code">Mã đơn hàng</label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        value="<?= htmlspecialchars($_GET['code'] ?? '') ?>"
                        placeholder="Nhập mã đơn hàng (VD: ORD202412011234)"
                        class="form-input"
                    />
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </form>
        </div>
    <?php endif; ?>
    
    <?php if ($isLoggedIn && empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-shopping-bag" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="<?= $baseUrl ?>books" class="btn">Mua sắm ngay</a>
        </div>
    <?php elseif (!$isLoggedIn && !$searchOrder): ?>
        <div class="empty-orders">
            <i class="fas fa-search" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
            <p>Vui lòng nhập thông tin để tra cứu đơn hàng.</p>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php if ($isLoggedIn): ?>
                <?php foreach ($orders as $order): ?>
                    <?php renderOrderCard($order); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php if ($searchOrder): ?>
                    <?php renderOrderCard($searchOrder); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Helper function to render order card
function renderOrderCard($order) {
    global $baseUrl;
    
    $statusLabels = [
        'pending' => ['label' => 'Chờ xử lý', 'class' => 'status-pending'],
        'processing' => ['label' => 'Đang xử lý', 'class' => 'status-processing'],
        'shipped' => ['label' => 'Đang giao', 'class' => 'status-shipped'],
        'completed' => ['label' => 'Hoàn tất', 'class' => 'status-completed'],
        'cancelled' => ['label' => 'Đã hủy', 'class' => 'status-cancelled'],
    ];
    
    $status = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'status-default'];
    
    $paymentLabels = [
        'cod' => 'Thanh toán khi nhận hàng (COD)',
        'bank_transfer' => 'Chuyển khoản ngân hàng',
        'vnpay' => 'VNPay',
    ];
    $paymentLabel = $paymentLabels[$order->paymentMethod] ?? $order->paymentMethod;
    ?>
    <div class="order-card">
        <div class="order-header">
            <div class="order-code-section">
                <h3>Mã đơn hàng: <strong><?= htmlspecialchars($order->orderCode) ?></strong></h3>
                <span class="order-date">
                    <i class="fas fa-calendar"></i> 
                    <?= date('d/m/Y H:i', strtotime($order->createdAt)) ?>
                </span>
            </div>
            <span class="status-badge <?= $status['class'] ?>">
                <?= $status['label'] ?>
            </span>
        </div>
        
        <div class="order-info">
            <div class="info-row">
                <span class="info-label"><i class="fas fa-user"></i> Khách hàng:</span>
                <span class="info-value"><?= htmlspecialchars($order->customerName) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fas fa-phone"></i> Số điện thoại:</span>
                <span class="info-value"><?= htmlspecialchars($order->customerPhone) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fas fa-map-marker-alt"></i> Địa chỉ:</span>
                <span class="info-value"><?= htmlspecialchars($order->customerAddress) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fas fa-credit-card"></i> Phương thức thanh toán:</span>
                <span class="info-value"><?= htmlspecialchars($paymentLabel) ?></span>
            </div>
        </div>
        
        <div class="order-total">
            <span class="total-label">Tổng tiền:</span>
            <span class="total-value"><?= number_format($order->total, 0, ',', '.') ?> đ</span>
        </div>
        
        <div class="order-actions">
            <a href="<?= $baseUrl ?>orders/detail?code=<?= urlencode($order->orderCode) ?>" class="btn-view-detail">
                <i class="fas fa-eye"></i> Xem chi tiết
            </a>
        </div>
    </div>
    <?php
}
?>

<style>
.orders-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.guest-search-section {
    background: white;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.guest-search-section h2 {
    margin: 0 0 10px 0;
    color: #2b6cb0;
}

.guest-search-section p {
    margin: 0 0 20px 0;
    color: #6b7280;
}

.order-search-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

.order-search-form .form-group {
    margin: 0;
}

.order-search-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1f2937;
}

.order-search-form .form-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
}

.btn-search {
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.btn-search:hover {
    background: #1e4a72;
}

.empty-orders {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.empty-orders p {
    margin: 0 0 20px 0;
    color: #6b7280;
    font-size: 16px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s;
}

.order-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
}

.order-code-section h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 18px;
}

.order-date {
    color: #6b7280;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-shipped {
    background: #d1fae5;
    color: #065f46;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.order-info {
    margin-bottom: 20px;
}

.info-row {
    display: flex;
    margin-bottom: 12px;
    align-items: flex-start;
}

.info-label {
    min-width: 200px;
    color: #6b7280;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    color: #1f2937;
    flex: 1;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f0f9ff;
    border-radius: 6px;
    margin-bottom: 20px;
}

.total-label {
    font-size: 16px;
    color: #1f2937;
    font-weight: 600;
}

.total-value {
    font-size: 20px;
    color: #2b6cb0;
    font-weight: 700;
}

.order-actions {
    display: flex;
    gap: 12px;
}

.btn-view-detail {
    background: #2b6cb0;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-view-detail:hover {
    background: #1e4a72;
}

@media (max-width: 768px) {
    .order-search-form {
        grid-template-columns: 1fr;
    }
    
    .order-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .info-row {
        flex-direction: column;
        gap: 4px;
    }
    
    .info-label {
        min-width: auto;
    }
}
</style>

