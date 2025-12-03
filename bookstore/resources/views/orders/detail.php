<?php /** @var App\Models\Order $order */ ?>
<?php /** @var array $items */ ?>
<?php /** @var App\Models\Book[] $books */ ?>
<div class="order-detail-page">
    <div class="order-detail-header">
        <a href="<?= $baseUrl ?>orders" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <h1>Chi tiết đơn hàng</h1>
    </div>
    
    <div class="order-detail-container">
        <div class="order-info-card">
            <h2><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span class="info-value"><strong><?= htmlspecialchars($order->orderCode) ?></strong></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value"><?= date('d/m/Y H:i', strtotime($order->createdAt)) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Trạng thái:</span>
                    <span class="status-badge status-<?= htmlspecialchars($order->status) ?>">
                        <?php
                        $statusLabels = [
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'shipped' => 'Đang giao',
                            'completed' => 'Hoàn tất',
                            'cancelled' => 'Đã hủy'
                        ];
                        echo $statusLabels[$order->status] ?? $order->status;
                        ?>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Phương thức thanh toán:</span>
                    <span class="info-value">
                        <?php
                        $paymentLabels = [
                            'cod' => 'Thanh toán khi nhận hàng (COD)',
                            'bank_transfer' => 'Chuyển khoản ngân hàng',
                            'vnpay' => 'VNPay',
                        ];
                        echo $paymentLabels[$order->paymentMethod] ?? $order->paymentMethod;
                        ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="customer-info-card">
            <h2><i class="fas fa-user"></i> Thông tin khách hàng</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Họ và tên:</span>
                    <span class="info-value"><?= htmlspecialchars($order->customerName) ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Số điện thoại:</span>
                    <span class="info-value"><?= htmlspecialchars($order->customerPhone) ?></span>
                </div>
                
                <div class="info-item full-width">
                    <span class="info-label">Địa chỉ giao hàng:</span>
                    <span class="info-value"><?= htmlspecialchars($order->customerAddress) ?></span>
                </div>
            </div>
        </div>
        
        <div class="order-items-card">
            <h2><i class="fas fa-shopping-cart"></i> Sản phẩm đã đặt</h2>
            
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $bookMap = [];
                    foreach ($books as $book) {
                        $bookMap[$book->id] = $book;
                    }
                    
                    foreach ($items as $item): 
                        $book = $bookMap[$item['book_id']] ?? null;
                        if (!$book) continue;
                    ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="<?= htmlspecialchars($book->imageUrl ?? $book->getImageUrl()) ?>" 
                                         alt="<?= htmlspecialchars($book->title) ?>"
                                         class="product-image"
                                         // TẠM TẮT onerror để tránh load placeholder vô hạn
                                         // onerror="this.src='<?= $baseUrl ?>assets/images/books/placeholder.jpg'">
                                    <div>
                                        <strong><?= htmlspecialchars($book->title) ?></strong>
                                        <p class="product-author"><?= htmlspecialchars($book->author) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><strong><?= number_format($item['subtotal'], 0, ',', '.') ?> đ</strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Tạm tính:</strong></td>
                        <td><strong><?= number_format($order->subtotal, 0, ',', '.') ?> đ</strong></td>
                    </tr>
                    <?php if ($order->discount > 0): ?>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Giảm giá:</strong></td>
                            <td><strong style="color: #10b981;">-<?= number_format($order->discount, 0, ',', '.') ?> đ</strong></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                        <td><strong style="font-size: 20px; color: #2b6cb0;"><?= number_format($order->total, 0, ',', '.') ?> đ</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <?php if (!empty($order->note)): ?>
            <div class="order-note-card">
                <h2><i class="fas fa-sticky-note"></i> Ghi chú</h2>
                <p><?= nl2br(htmlspecialchars($order->note)) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.order-detail-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.order-detail-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.btn-back {
    background: #6b7280;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-back:hover {
    background: #4b5563;
}

.order-detail-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-info-card,
.customer-info-card,
.order-items-card,
.order-note-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-info-card h2,
.customer-info-card h2,
.order-items-card h2,
.order-note-card h2 {
    margin: 0 0 20px 0;
    color: #2b6cb0;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 12px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 14px;
}

.info-value {
    color: #1f2937;
    font-size: 16px;
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

.order-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.order-items-table th {
    background: #2b6cb0;
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
}

.order-items-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #e2e8f0;
}

.order-items-table tbody tr:hover {
    background: #f8fafc;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-image {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.product-author {
    margin: 4px 0 0 0;
    color: #6b7280;
    font-size: 14px;
}

.order-items-table tfoot {
    background: #f8fafc;
}

.order-items-table tfoot td {
    padding: 12px;
    font-weight: 600;
}

.order-items-table tfoot .total-row {
    background: #e0f2fe;
    border-top: 2px solid #2b6cb0;
}

.order-note-card p {
    margin: 0;
    color: #1f2937;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-items-table {
        font-size: 14px;
    }
    
    .product-info {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

