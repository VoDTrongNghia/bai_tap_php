<?php require_once __DIR__ . '/../../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chi tiết đơn hàng #<?= htmlspecialchars($order['id'] ?? '') ?></h2>
            <div class="admin-card-actions">
                <a href="<?= BASE_URL ?>admin?page=orders" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
        
        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="order-detail-grid">
            <!-- Thông tin đơn hàng -->
            <div class="order-info-section">
                <h3>Thông tin đơn hàng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Mã đơn hàng:</label>
                        <span>#<?= htmlspecialchars($order['id'] ?? '') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Trạng thái:</label>
                        <?php 
                        $status = $order['trang_thai'] ?? $order['status'] ?? 'pending';
                        $statusClass = match($status) {
                            'cho_xu_ly' => 'status-pending',
                            'dang_xu_ly' => 'status-processing', 
                            'hoan_tat' => 'status-completed',
                            'huy' => 'status-cancelled',
                            'pending' => 'status-pending',
                            'processing' => 'status-processing',
                            'completed' => 'status-completed',
                            'cancelled' => 'status-cancelled',
                            default => 'status-pending'
                        };
                        $statusText = match($status) {
                            'cho_xu_ly' => 'Chờ xử lý',
                            'dang_xu_ly' => 'Đang xử lý',
                            'hoan_tat' => 'Hoàn thành',
                            'huy' => 'Đã hủy',
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                            default => 'Chờ xử lý'
                        };
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                    <div class="info-item">
                        <label>Ngày đặt:</label>
                        <span><?= date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Phương thức thanh toán:</label>
                        <span><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tổng tiền:</label>
                        <span class="amount"><?= number_format($order['total'] ?? $order['tong_tien'] ?? 0, 0, ',', '.') ?> đ</span>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin người đặt -->
            <div class="customer-info-section">
                <h3>Thông tin người đặt</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Họ tên:</label>
                        <span><?= htmlspecialchars($order['ten_khach_hang'] ?? $order['user_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= htmlspecialchars($order['email'] ?? $order['user_email'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Số điện thoại:</label>
                        <span><?= htmlspecialchars($order['so_dien_thoai'] ?? $order['user_phone'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Địa chỉ:</label>
                        <span><?= htmlspecialchars($order['customer_address'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chi tiết sản phẩm -->
        <div class="order-items-section">
            <h3>Chi tiết sản phẩm</h3>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($item['product_name'] ?? $item['ten_sach'] ?? 'Sản phẩm') ?>
                                </td>
                                <td><?= number_format($item['so_luong'] ?? $item['quantity'] ?? 1) ?></td>
                                <td><?= number_format($item['don_gia'] ?? $item['gia'] ?? $item['product_price'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td class="text-right">
                                    <?= number_format($item['thanh_tien'] ?? $item['subtotal'] ?? 0, 0, ',', '.') ?> đ
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <p>Không có sản phẩm nào trong đơn hàng</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right">
                                <strong class="amount"><?= number_format($order['tong_tien'] ?? 0, 0, ',', '.') ?> đ</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Ghi chú -->
        <?php if (!empty($order['ghi_chu'] ?? $order['note'])): ?>
        <div class="order-note-section">
            <h3>Ghi chú</h3>
            <p><?= htmlspecialchars($order['ghi_chu'] ?? $order['note']) ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Actions -->
        <div class="order-actions">
            <?php if ($status === 'cho_xu_ly' || $status === 'pending'): ?>
                <button class="btn btn-success" onclick="updateOrderStatus(<?= $order['id'] ?>, 'dang_xu_ly')">
                    <i class="fas fa-check"></i> Xác nhận đơn hàng
                </button>
            <?php endif; ?>
            
            <?php if (in_array($status, ['cho_xu_ly', 'dang_xu_ly', 'pending', 'processing'])): ?>
                <button class="btn btn-warning" onclick="updateOrderStatus(<?= $order['id'] ?>, 'hoan_tat')">
                    <i class="fas fa-truck"></i> Hoàn thành
                </button>
                <button class="btn btn-danger" onclick="updateOrderStatus(<?= $order['id'] ?>, 'huy')">
                    <i class="fas fa-times"></i> Hủy đơn hàng
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }
}

.order-info-section, .customer-info-section, .order-items-section, .order-note-section {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.order-info-section h3, .customer-info-section h3, .order-items-section h3, .order-note-section h3 {
    margin-bottom: 1rem;
    color: #374151;
    font-size: 1.1rem;
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
}

.info-item span {
    color: #111827;
}

.order-actions {
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending { background-color: #fef3c7; color: #92400e; }
.status-processing { background-color: #dbeafe; color: #1e40af; }
.status-shipped { background-color: #e0e7ff; color: #3730a3; }
.status-completed { background-color: #d1fae5; color: #065f46; }
.status-cancelled { background-color: #fee2e2; color: #991b1b; }

.amount {
    font-weight: 600;
    color: #dc2626;
}
</style>

<script>
function updateOrderStatus(orderId, newStatus) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này?')) {
        return;
    }
    
    fetch('<?= BASE_URL ?>admin/orders/update/' + orderId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
    });
}
</script>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>
