<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý đơn hàng</h2>
            <div class="admin-card-actions">
                <button class="btn btn-primary" onclick="refreshOrders()">
                    <i class="fas fa-sync"></i> Làm mới
                </button>
            </div>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <span class="order-id">#<?= htmlspecialchars($order['id'] ?? $order['ma_don_hang'] ?? '') ?></span>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name"><?= htmlspecialchars($order['customer_name'] ?? $order['ten_khach_hang'] ?? 'N/A') ?></div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($order['customer_email'] ?? $order['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($order['customer_phone'] ?? $order['so_dien_thoai'] ?? 'N/A') ?></td>
                            <td class="text-right">
                                <span class="amount"><?= number_format($order['total_amount'] ?? $order['tong_tien'] ?? 0, 0, ',', '.') ?> đ</span>
                            </td>
                            <td>
                                <?php 
                                $status = $order['status'] ?? $order['trang_thai'] ?? 'pending';
                                $statusClass = 'status-' . $status;
                                $statusText = match($status) {
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'shipped' => 'Đã giao hàng',
                                    'completed' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                    default => 'Chờ xử lý'
                                };
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($order['created_at'] ?? $order['ngay_dat'] ?? 'now')) ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewOrder(<?= $order['id'] ?? $order['ma_don_hang'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($status === 'pending'): ?>
                                    <button class="btn btn-sm btn-success" onclick="updateOrderStatus(<?= $order['id'] ?? $order['ma_don_hang'] ?>, 'processing')" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php if (in_array($status, ['pending', 'processing'])): ?>
                                    <button class="btn btn-sm btn-danger" onclick="updateOrderStatus(<?= $order['id'] ?? $order['ma_don_hang'] ?>, 'cancelled')" title="Hủy">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p>Chưa có đơn hàng nào</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function viewOrder(orderId) {
    window.location.href = '<?= BASE_URL ?>admin?page=orderDetail&id=' + orderId;
}

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

function refreshOrders() {
    location.reload();
}
</script>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
