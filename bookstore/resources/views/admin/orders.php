<?php
// Authentication is handled by AdminController via AdminHelper::requireAdmin()
// No need for manual session checks here
?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý Đơn hàng</h2>
            <div class="filter-controls">
                <input type="date" id="fromDate" class="form-input" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>" onchange="applyDateFilter()">
                <input type="date" id="toDate" class="form-input" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>" onchange="applyDateFilter()">
                <select id="statusFilter" class="form-select" onchange="filterOrders()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="shipping">Đang giao</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
                <input type="text" id="searchOrder" class="form-input" placeholder="Tìm kiếm mã đơn hàng..." onkeyup="searchOrders()">
            </div>
        </div>
        
        <div class="admin-table-container">
            <form id="bulkDeleteForm" method="post" action="<?= $baseUrl ?>admin/order-bulk-delete">
            <table class="admin-table" id="ordersTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Chưa có đơn hàng nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr data-status="<?= $order['status'] ?? 'pending' ?>">
                                <td><input type="checkbox" name="order_ids[]" value="<?= (int)($order['id'] ?? 0) ?>" class="order-checkbox"></td>
                                <td><strong><?= htmlspecialchars($order['ma_don_hang'] ?? '') ?></strong></td>
                                <td><?= htmlspecialchars($order['customer_name'] ?? $order['ten_khach_hang'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if (!empty($order['ten_san_pham'])): ?>
                                        <?= htmlspecialchars($order['ten_san_pham']) ?>
                                        <span style="color:#6b7280; font-size:12px;">(<?= (int)($order['so_san_pham'] ?? 0) ?> sp)</span>
                                    <?php else: ?>
                                        <?= (int)($order['so_san_pham'] ?? 0) ?> sản phẩm
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($order['total'] ?? $order['tong_tien'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td>
                                    <span class="badge badge-<?= getPaymentBadgeClass($order['payment_method'] ?? $order['phuong_thuc_thanh_toan'] ?? 'cod') ?>">
                                        <?= getPaymentLabel($order['payment_method'] ?? $order['phuong_thuc_thanh_toan'] ?? 'cod') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= getBadgeClass($order['status'] ?? 'pending') ?>">
                                        <?= getStatusLabel($order['status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'] ?? $order['ngay_dat'] ?? 'now')) ?></td>
                                <td class="actions">
                                    <button class="btn btn-sm btn-info" onclick="OrderManager.viewOrderDetails(<?= $order['id'] ?? '' ?>)">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </button>
                                    <?php if (in_array($order['status'] ?? 'pending', ['pending', 'processing'])): ?>
                                        <button class="btn btn-sm btn-success" onclick="updateOrderStatus(<?= $order['id'] ?? '' ?>, 'processing')">
                                            <i class="fas fa-check"></i> Xử lý
                                        </button>
                                    <?php endif; ?>
                                    <?php if (($order['status'] ?? '') === 'processing'): ?>
                                        <button class="btn btn-sm btn-primary" onclick="updateOrderStatus(<?= $order['id'] ?? '' ?>, 'shipping')">
                                            <i class="fas fa-truck"></i> Giao hàng
                                        </button>
                                    <?php endif; ?>
                                    <?php if (($order['status'] ?? '') === 'shipping'): ?>
                                        <button class="btn btn-sm btn-success" onclick="updateOrderStatus(<?= $order['id'] ?? '' ?>, 'completed')">
                                            <i class="fas fa-check-circle"></i> Hoàn thành
                                        </button>
                                    <?php endif; ?>
                                    <?php if (in_array($order['status'] ?? 'pending', ['pending', 'processing'])): ?>
                                        <button class="btn btn-sm btn-danger" onclick="cancelOrder(<?= $order['id'] ?? '' ?>)">
                                            <i class="fas fa-times"></i> Hủy
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteOrder(<?= $order['id'] ?? '' ?>)">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="margin-top:10px; display:flex; justify-content:flex-start; gap:10px;">
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                    <i class="fas fa-trash"></i> Xóa đã chọn
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal" style="display: none;">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3>Chi tiết Đơn hàng</h3>
            <span class="close">&times;</span>
        </div>
        <div id="orderDetailsContent">
            <!-- Content will be loaded via JavaScript -->
        </div>
    </div>
</div>

<?php
function getBadgeClass($status) {
    $classes = [
        'completed' => 'completed',
        'processing' => 'processing',
        'shipping' => 'shipping',
        'pending' => 'pending',
        'cancelled' => 'cancelled'
    ];
    return $classes[$status] ?? 'pending';
}

function getStatusLabel($status) {
    $labels = [
        'completed' => 'Đã hoàn thành',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'pending' => 'Chờ xử lý',
        'cancelled' => 'Đã hủy'
    ];
    return $labels[$status] ?? 'Chờ xử lý';
}

function getPaymentBadgeClass($method) {
    $classes = [
        'cod' => 'cod',
        'bank' => 'bank'
    ];
    return $classes[$method] ?? 'cod';
}

function getPaymentLabel($method) {
    $labels = [
        'cod' => 'COD',
        'bank' => 'Chuyển khoản'
    ];
    return $labels[$method] ?? 'COD';
}
?>

<style>
.filter-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.form-select, .form-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background: white;
}

.form-input {
    width: 200px;
}

.modal-large {
    width: 800px;
    max-width: 95%;
}

.order-details {
    padding: 20px;
}

.order-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.info-group {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

.info-group h4 {
    margin: 0 0 10px 0;
    color: #2b6cb0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.info-label {
    font-weight: bold;
    color: #666;
}

.info-value {
    color: #333;
}

.order-items {
    margin-top: 20px;
}

.order-items h4 {
    margin: 0 0 15px 0;
    color: #2b6cb0;
}

.item-table {
    width: 100%;
    border-collapse: collapse;
}

.item-table th,
.item-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.item-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.item-image {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.item-name {
    font-weight: bold;
    color: #333;
}

.item-quantity {
    text-align: center;
}

.item-price {
    text-align: right;
    font-weight: bold;
}

.order-summary {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.summary-row.total {
    font-weight: bold;
    font-size: 16px;
    color: #2b6cb0;
    padding-top: 10px;
    border-top: 2px solid #2b6cb0;
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

.badge-processing {
    background: #3b82f6;
    color: white;
}

.badge-shipping {
    background: #8b5cf6;
    color: white;
}

.badge-completed {
    background: #10b981;
    color: white;
}

.badge-cancelled {
    background: #ef4444;
    color: white;
}

.badge-cod {
    background: #6b7280;
    color: white;
}

.badge-bank {
    background: #2b6cb0;
    color: white;
}

.text-center {
    text-align: center;
    padding: 40px;
    color: #6b7280;
}

.actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 600px;
    max-width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ddd;
}

.modal-header h3 {
    margin: 0;
    color: #2b6cb0;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

@media (max-width: 768px) {
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-input {
        width: 100%;
    }
    
    .order-info {
        grid-template-columns: 1fr;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<script>
function filterOrders() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    
    rows.forEach(row => {
        if (status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function applyDateFilter() {
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    const url = new URL(window.location.href);
    if (from) {
        url.searchParams.set('from_date', from);
    } else {
        url.searchParams.delete('from_date');
    }
    if (to) {
        url.searchParams.set('to_date', to);
    } else {
        url.searchParams.delete('to_date');
    }
    window.location.href = url.toString();
}

function searchOrders() {
    const searchTerm = document.getElementById('searchOrder').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Sử dụng một namespace riêng để tránh xung đột
const OrderManager = {
    init: function() {
        console.log('OrderManager initialized');
        
        // Đóng modal khi click vào nút đóng
        document.querySelectorAll('.close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                console.log('Close button clicked');
                e.preventDefault();
                e.stopPropagation();
                this.closeModal();
            });
        });

        // Đóng modal khi click bên ngoài nội dung modal
        const modal = document.getElementById('orderDetailsModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                console.log('Modal clicked', e.target);
                if (e.target === modal) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.closeModal();
                }
            });
        }
        
        // Ngăn chặn sự kiện submit form mặc định
        const bulkForm = document.getElementById('bulkDeleteForm');
        if (bulkForm) {
            bulkForm.addEventListener('submit', (e) => {
                console.log('Form submit prevented');
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        }
    },

    viewOrderDetails: function(orderId) {
        console.log('viewOrderDetails called with orderId:', orderId);
        
        // Ngăn chặn sự kiện mặc định nếu có
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const url = '<?= $baseUrl ?>admin/order-detail?id=' + encodeURIComponent(orderId);
        const modal = document.getElementById('orderDetailsModal');
        const content = document.getElementById('orderDetailsContent');
        
        if (!modal || !content) {
            console.error('Modal elements not found');
            return false;
        }
        
        // Hiển thị loading
        content.innerHTML = '<div class="text-center" style="padding: 40px;">Đang tải chi tiết đơn hàng...</div>';
        modal.style.display = 'block';
        
        console.log('Fetching order details from:', url);
        
        // Tải nội dung
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            console.log('Received HTML content');
            content.innerHTML = html;
            // Thêm sự kiện cho nút đóng trong nội dung modal
            const closeBtn = content.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.closeModal();
                });
            }
            return true;
        })
        .catch(error => {
            console.error('Error loading order details:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <p>Không thể tải chi tiết đơn hàng. Vui lòng thử lại.</p>
                    <p><small>Lỗi: ${error.message}</small></p>
                </div>
                <button class="btn btn-secondary" onclick="OrderManager.closeModal()">Đóng</button>
            `;
        });
        
        return false;
    },

    closeModal: function(event) {
        console.log('closeModal called');
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const modal = document.getElementById('orderDetailsModal');
        if (modal) {
            modal.style.display = 'none';
        }
        return false;
    }
};

// Khởi tạo khi tài liệu đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    OrderManager.init();
});

function updateOrderStatus(orderId, newStatus) {
    if (confirm(`Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?`)) {
        window.location.href = `<?= $baseUrl ?>admin/order-update?id=${orderId}&status=${newStatus}`;
    }
}

function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        window.location.href = `<?= $baseUrl ?>admin/order-cancel?id=${orderId}`;
    }
}

function deleteOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác.')) {
        window.location.href = `<?= $baseUrl ?>admin/order-delete?id=${orderId}`;
    }
}

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
    });
}

function bulkDelete() {
    const checked = document.querySelectorAll('.order-checkbox:checked');
    if (checked.length === 0) {
        alert('Vui lòng chọn ít nhất một đơn hàng để xóa.');
        return;
    }
    if (confirm('Bạn có chắc chắn muốn xóa các đơn hàng đã chọn? Hành động này không thể hoàn tác.')) {
        document.getElementById('bulkDeleteForm').submit();
    }
}

// Xử lý sự kiện click bên ngoài modal đã được chuyển vào OrderManager
</script>

