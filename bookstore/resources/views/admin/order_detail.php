<?php
// Hàm helper để lấy giá trị từ đối tượng hoặc mảng
function getOrderValue($order, $key, $default = null) {
    if (is_object($order)) {
        return $order->$key ?? $default;
    } elseif (is_array($order)) {
        return $order[$key] ?? $default;
    }
    return $default;
}

// Khởi tạo biến nếu chưa được định nghĩa
if (!isset($order) || !is_object($order)) {
    $order = (object)[
        'id' => 0,
        'ma_don_hang' => '',
        'ho_ten' => 'Khách lẻ',
        'email' => 'N/A',
        'so_dien_thoai' => 'N/A',
        'dia_chi_giao_hang' => 'N/A',
        'ngay_dat' => date('Y-m-d H:i:s'),
        'trang_thai' => 'cho_xu_ly',
        'phuong_thuc_thanh_toan' => 'cod',
        'trang_thai_thanh_toan' => 'unpaid',
        'tam_tinh' => 0,
        'phi_van_chuyen' => 0,
        'giam_gia' => 0,
        'tong_tien' => 0,
        'ghi_chu' => ''
    ];
}

// Lấy các giá trị từ đơn hàng
$orderId = getOrderValue($order, 'id', 0);
$status = getOrderValue($order, 'trang_thai', 'cho_xu_ly');
$maDonHang = getOrderValue($order, 'ma_don_hang', 'DH' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT));
$subtotal = (float)getOrderValue($order, 'tam_tinh', 0);
$shipping = (float)getOrderValue($order, 'phi_van_chuyen', 0);
$discount = (float)getOrderValue($order, 'giam_gia', 0);
$total = (float)getOrderValue($order, 'tong_tien', $subtotal + $shipping - $discount);

// Khởi tạo mảng items nếu chưa có
if (!isset($items) || !is_array($items)) {
    $items = [];
}

// Nhãn trạng thái đơn hàng
$statusLabels = [
    'cho_xu_ly' => 'Chờ xử lý',
    'dang_xu_ly' => 'Đang xử lý',
    'dang_giao_hang' => 'Đang giao hàng',
    'hoan_thanh' => 'Đã hoàn thành',
    'da_huy' => 'Đã hủy',
    'pending' => 'Chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao hàng',
    'completed' => 'Đã hoàn thành',
    'cancelled' => 'Đã hủy'
];

$statusLabel = $statusLabels[$status] ?? $status;

// Định dạng tiền tệ
function formatCurrency($amount) {
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}

// Tính toán lại tổng tiền nếu cần
$calculateTotal = 0;
foreach ($items as $item) {
    $price = $item['book_sale_price'] > 0 ? $item['book_sale_price'] : $item['book_price'];
    $calculateTotal += $price * $item['so_luong'];
}

// Nếu tổng tiền từ items khác với tổng tiền từ đơn hàng
if (abs($calculateTotal - $subtotal) > 1) {
    $subtotal = $calculateTotal;
    $total = $subtotal + $shipping - $discount;
}
?>

<div class="order-details">
    <div class="order-header">
        <h3>Chi tiết đơn hàng #<?= htmlspecialchars($maDonHang) ?></h3>
        <span class="status-badge status-<?= $status ?>">
            <?= $statusLabel ?>
        </span>
    </div>

    <div class="order-info-grid">
        <div class="order-info-section">
            <h4>Thông tin khách hàng</h4>
            <p><strong>Tên:</strong> <?= htmlspecialchars($order->ho_ten) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order->email) ?></p>
            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order->so_dien_thoai) ?></p>
            <p><strong>Địa chỉ giao hàng:</strong> <?= nl2br(htmlspecialchars($order->dia_chi_giao_hang)) ?></p>
        </div>

        <div class="order-info-section">
            <h4>Thông tin thanh toán</h4>
            <p><strong>Phương thức:</strong> 
                <?= getOrderValue($order, 'phuong_thuc_thanh_toan', 'COD') === 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' ?>
            </p>
            <p><strong>Trạng thái thanh toán:</strong> 
                <?= getOrderValue($order, 'trang_thai_thanh_toan', 'Chưa thanh toán') ?>
            </p>
            <p><strong>Ngày đặt hàng:</strong> 
                <?= date('d/m/Y H:i', strtotime(getOrderValue($order, 'ngay_dat', 'now'))) ?>
            </p>
        </div>
    </div>

    <div class="order-items">
        <h4>Chi tiết đơn hàng</h4>
        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $itemName = $item['book_name'] ?? 'Sản phẩm không xác định';
                        $itemImage = $item['book_image'] ?? '';
                        
                        // Debug thông tin sản phẩm (đã ẩn để giao diện sạch hơn)
                        // echo '<pre>Debug item: ';
                        // print_r([
                        //     'book_name' => $item['book_name'] ?? 'N/A',
                        //     'book_image' => $item['book_image'] ?? 'N/A'
                        // ]);
                        // echo '</pre>';
                        
                        $price = $item['book_sale_price'] > 0 ? $item['book_sale_price'] : ($item['book_price'] ?? $item['don_gia'] ?? 0);
                        $quantity = (int)($item['so_luong'] ?? $item['quantity'] ?? 1);
                        $itemTotal = $price * $quantity;
                        ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                   <?php if (!empty($itemImage)): ?>
                                        <?php 
                                        // Tạo đường dẫn ảnh đúng
                                        $imagePath = '/bookstore/public/assets/images/books/' . $itemImage;
                                        ?>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                             alt="<?= htmlspecialchars($itemName) ?>" 
                                             class="product-image"
                                             onerror="this.onerror=null; this.src='/bookstore/public/assets/images/placeholder.jpg'">
                                    <?php else: ?>
                                        <div class="no-image">Không có ảnh</div>
                                    <?php endif; ?>
                                    <div class="product-details">
                                        <div class="product-name"><?= htmlspecialchars($itemName) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= number_format($price, 0, ',', '.') ?>đ</td>
                            <td><?= $quantity ?></td>
                            <td><?= number_format($itemTotal, 0, ',', '.') ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Tạm tính:</strong></td>
                    <td><?= number_format($subtotal, 0, ',', '.') ?>đ</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Phí vận chuyển:</strong></td>
                    <td><?= number_format($shipping, 0, ',', '.') ?>đ</td>
                </tr>
                <?php if ($discount > 0): ?>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Giảm giá:</strong></td>
                        <td>-<?= number_format($discount, 0, ',', '.') ?>đ</td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                    <td><strong><?= number_format($total, 0, ',', '.') ?>đ</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php $orderNote = getOrderValue($order, 'ghi_chu', ''); ?>
    <?php if (!empty($orderNote)): ?>
        <div class="order-notes">
            <h4>Ghi chú đơn hàng</h4>
            <div class="note-content">
                <?= nl2br(htmlspecialchars($orderNote)) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="order-actions">
        <button type="button" class="btn btn-secondary" onclick="closeOrderDetailsModal()">
            <i class="fas fa-times"></i> Đóng
        </button>
    </div>
</div>

<style>
.order-details {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.order-header h3 {
    margin: 0;
    color: #2c3e50;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending { background-color: #ffeeba; color: #856404; }
.status-processing { background-color: #b8daff; color: #004085; }
.status-shipping { background-color: #d4edda; color: #155724; }
.status-completed { background-color: #c3e6cb; color: #155724; }
.status-cancelled { background-color: #f8d7da; color: #721c24; }

.order-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.order-info-section {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #eee;
}

.order-info-section h4 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #2c3e50;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
}

.order-items {
    margin-bottom: 30px;
}

.order-items h4 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #2c3e50;
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.order-items-table th,
.order-items-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.order-items-table th {
    background-color: #f5f5f5;
    font-weight: 600;
    color: #555;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-image {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border: 1px solid #eee;
    border-radius: 4px;
}

.no-image {
    width: 50px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border: 1px solid #eee;
    color: #999;
    font-size: 12px;
    border-radius: 4px;
}

.product-details {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 500;
    margin-bottom: 3px;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.total-row {
    font-size: 1.1em;
    font-weight: bold;
    background-color: #f9f9f9;
}

.order-notes {
    margin-bottom: 25px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
}

.order-notes h4 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #2c3e50;
}

.note-content {
    background: white;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #eee;
}

.order-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}

.btn-close {
    background-color: #6c757d;
    color: white;
}

.btn-close:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .order-info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-items-table {
        display: block;
        overflow-x: auto;
    }
    
    .product-info {
        min-width: 200px;
    }
}
</style>