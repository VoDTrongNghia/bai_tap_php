<?php /** @var array $order */ ?>
<div class="order-success-page">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Đặt hàng thành công!</h1>
        <p class="success-message">Cảm ơn bạn đã mua sắm tại <?= htmlspecialchars(APP_NAME) ?></p>
        
        <?php if (isset($order['order_id'])): ?>
            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
                <p>Chúng tôi sẽ gửi email xác nhận đơn hàng đến địa chỉ email của bạn.</p>
            </div>
        <?php endif; ?>
        
        <div class="success-actions">
            <a href="<?= $baseUrl ?>orders/detail?code=<?= urlencode($order['order_code'] ?? $order['order_id'] ?? '') ?>" class="btn btn-primary">
                <i class="fas fa-eye"></i> Xem chi tiết đơn hàng
            </a>
            <a href="<?= $baseUrl ?>" class="btn btn-secondary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="<?= $baseUrl ?>" class="btn btn-secondary">
                <i class="fas fa-book"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<style>
.order-success-page {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.success-container {
    background: white;
    border-radius: 12px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-width: 600px;
    width: 100%;
}

.success-icon {
    font-size: 80px;
    color: #10b981;
    margin-bottom: 20px;
}

.success-container h1 {
    margin: 0 0 16px 0;
    color: #1f2937;
    font-size: 32px;
}

.success-message {
    font-size: 18px;
    color: #6b7280;
    margin: 0 0 30px 0;
}

.order-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 20px;
    margin: 30px 0;
    text-align: left;
}

.order-info p {
    margin: 8px 0;
    color: #1e40af;
}

.success-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-primary {
    background: #2b6cb0;
    color: white;
}

.btn-primary:hover {
    background: #1e4a72;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .success-container {
        padding: 40px 20px;
    }
    
    .success-icon {
        font-size: 60px;
    }
    
    .success-container h1 {
        font-size: 24px;
    }
    
    .success-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

