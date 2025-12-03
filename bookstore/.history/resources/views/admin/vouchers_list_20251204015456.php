<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý Voucher</h2>
            <div class="admin-card-actions">
                <button class="btn btn-primary" onclick="addVoucher()">
                    <i class="fas fa-plus"></i> Thêm voucher mới
                </button>
                <button class="btn btn-secondary" onclick="refreshVouchers()">
                    <i class="fas fa-sync"></i> Làm mới
                </button>
            </div>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã voucher</th>
                        <th>Mô tả</th>
                        <th>Giảm giá</th>
                        <th>Giá trị tối thiểu</th>
                        <th>Số lượng</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vouchers)): ?>
                        <?php foreach ($vouchers as $voucher): ?>
                        <tr>
                            <td>
                                <span class="voucher-id">#<?= htmlspecialchars($voucher['id']) ?></span>
                            </td>
                            <td>
                                <div class="voucher-code">
                                    <strong><?= htmlspecialchars($voucher['ma_voucher']) ?></strong>
                                </div>
                            </td>
                            <td>
                                <div class="voucher-description">
                                    <?= htmlspecialchars($voucher['mo_ta'] ?? 'N/A') ?>
                                </div>
                            </td>
                            <td>
                                <div class="voucher-discount">
                                    <?php 
                                    $discount = $voucher['giam_gia'];
                                    $type = $voucher['kieu_giam'];
                                    if ($type === 'percent') {
                                        echo number_format($discount, 0) . '%';
                                    } else {
                                        echo number_format($discount, 0) . '₫';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?= number_format($voucher['gia_tri_toi_thieu'], 0) ?>₫
                            </td>
                            <td>
                                <div class="voucher-quantity">
                                    <?= $voucher['da_su_dung'] ?>/<?= $voucher['so_luong'] ?>
                                </div>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($voucher['ngay_bat_dau'])) ?>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($voucher['ngay_ket_thuc'])) ?>
                            </td>
                            <td>
                                <?php 
                                $now = time();
                                $startDate = strtotime($voucher['ngay_bat_dau']);
                                $endDate = strtotime($voucher['ngay_ket_thuc']);
                                $remaining = $voucher['so_luong'] - $voucher['da_su_dung'];
                                
                                if ($voucher['trang_thai'] == 0) {
                                    $statusClass = 'status-inactive';
                                    $statusText = 'Không hoạt động';
                                } elseif ($now < $startDate) {
                                    $statusClass = 'status-pending';
                                    $statusText = 'Sắp diễn ra';
                                } elseif ($now > $endDate || $remaining <= 0) {
                                    $statusClass = 'status-expired';
                                    $statusText = 'Hết hạn';
                                } else {
                                    $statusClass = 'status-active';
                                    $statusText = 'Đang hoạt động';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewVoucher(<?= $voucher['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editVoucher(<?= $voucher['id'] ?>)" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteVoucher(<?= $voucher['id'] ?>)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-ticket-alt"></i>
                                    <p>Chưa có voucher nào</p>
                                    <button class="btn btn-primary" onclick="addVoucher()">Thêm voucher đầu tiên</button>
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
function addVoucher() {
    window.location.href = '<?= BASE_URL ?>admin?page=addVoucher';
}

function viewVoucher(voucherId) {
    window.location.href = '<?= BASE_URL ?>admin?page=voucherDetail&id=' + voucherId;
}

function editVoucher(voucherId) {
    window.location.href = '<?= BASE_URL ?>admin?page=editVoucher&id=' + voucherId;
}

function deleteVoucher(voucherId) {
    if (!confirm('Bạn có chắc chắn muốn xóa voucher này? Hành động này không thể hoàn tác!')) {
        return;
    }
    
    window.location.href = '<?= BASE_URL ?>admin?page=deleteVoucher&id=' + voucherId;
}

function refreshVouchers() {
    location.reload();
}
</script>

<style>
.voucher-id {
    font-family: monospace;
    font-weight: bold;
    color: #6b7280;
}

.voucher-code {
    font-weight: 500;
    color: #374151;
}

.voucher-description {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.voucher-discount {
    font-weight: 600;
    color: #059669;
}

.voucher-quantity {
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-expired {
    background-color: #f3f4f6;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
    color: #9ca3af;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .admin-table {
        font-size: 0.875rem;
    }
    
    .voucher-description {
        max-width: 150px;
    }
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
