<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chi tiết Voucher #<?= htmlspecialchars($voucher['id']) ?></h2>
            <div class="admin-card-actions">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button class="btn btn-warning" onclick="editVoucher(<?= $voucher['id'] ?>)">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </button>
                <button class="btn btn-danger" onclick="deleteVoucher(<?= $voucher['id'] ?>)">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
        
        <div class="voucher-detail-grid">
            <!-- Thông tin cơ bản -->
            <div class="voucher-info-section">
                <h3>Thông tin cơ bản</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>ID:</label>
                        <span>#<?= htmlspecialchars($voucher['id']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Mã voucher:</label>
                        <span class="voucher-code"><?= htmlspecialchars($voucher['ma_voucher']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Mô tả:</label>
                        <span><?= htmlspecialchars($voucher['mo_ta'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Trạng thái:</label>
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
                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin giảm giá -->
            <div class="voucher-discount-section">
                <h3>Thông tin giảm giá</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Kiểu giảm giá:</label>
                        <span>
                            <?php 
                            $type = $voucher['kieu_giam'];
                            echo $type === 'percent' ? 'Phần trăm (%)' : 'Số tiền cố định (₫)';
                            ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Giảm giá:</label>
                        <span class="discount-amount">
                            <?php 
                            $discount = $voucher['giam_gia'];
                            if ($type === 'percent') {
                                echo number_format($discount, 0) . '%';
                            } else {
                                echo number_format($discount, 0) . '₫';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Giá trị tối thiểu:</label>
                        <span><?= number_format($voucher['gia_tri_toi_thieu'], 0) ?>₫</span>
                    </div>
                    <div class="info-item">
                        <label>Số lượng:</label>
                        <span class="quantity-info">
                            <?= $voucher['da_su_dung'] ?>/<?= $voucher['so_luong'] ?>
                            <small>(Còn lại: <?= $voucher['so_luong'] - $voucher['da_su_dung'] ?>)</small>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Thời gian áp dụng -->
            <div class="voucher-time-section">
                <h3>Thời gian áp dụng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Ngày bắt đầu:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($voucher['ngay_bat_dau'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Ngày kết thúc:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($voucher['ngay_ket_thuc'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Thời gian còn lại:</label>
                        <span class="time-remaining">
                            <?php 
                            if ($now < $startDate) {
                                $diff = $startDate - $now;
                                $days = floor($diff / (24 * 60 * 60));
                                $hours = floor(($diff % (24 * 60 * 60)) / (60 * 60));
                                echo "Bắt đầu sau: {$days} ngày {$hours} giờ";
                            } elseif ($now > $endDate) {
                                echo "Đã hết hạn";
                            } else {
                                $diff = $endDate - $now;
                                $days = floor($diff / (24 * 60 * 60));
                                $hours = floor(($diff % (24 * 60 * 60)) / (60 * 60));
                                echo "Còn lại: {$days} ngày {$hours} giờ";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Ngày tạo:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($voucher['created_at'])) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Thống kê sử dụng -->
            <div class="voucher-stats-section">
                <h3>Thống kê sử dụng</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= $voucher['da_su_dung'] ?></div>
                        <div class="stat-label">Đã sử dụng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $voucher['so_luong'] - $voucher['da_su_dung'] ?></div>
                        <div class="stat-label">Còn lại</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            <?php 
                            $percentage = $voucher['so_luong'] > 0 ? round(($voucher['da_su_dung'] / $voucher['so_luong']) * 100, 1) : 0;
                            echo $percentage . '%';
                            ?>
                        </div>
                        <div class="stat-label">Tỷ lệ sử dụng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $voucher['so_luong'] ?></div>
                        <div class="stat-label">Tổng số lượng</div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                <div class="progress-container">
                    <div class="progress-label">Tiến độ sử dụng</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $percentage ?>%"></div>
                    </div>
                    <div class="progress-text"><?= $voucher['da_su_dung'] ?>/<?= $voucher['so_luong'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
    window.location.href = '<?= BASE_URL ?>admin?page=vouchers';
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
</script>

<style>
.voucher-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.voucher-info-section,
.voucher-discount-section,
.voucher-time-section,
.voucher-stats-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.voucher-info-section h3,
.voucher-discount-section h3,
.voucher-time-section h3,
.voucher-stats-section h3 {
    margin-bottom: 1rem;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
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

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 500;
    color: #6b7280;
    min-width: 120px;
}

.info-item span {
    color: #374151;
    flex: 1;
    text-align: right;
}

.voucher-code {
    font-weight: 600;
    color: #059669;
    font-family: monospace;
}

.discount-amount {
    font-weight: 600;
    color: #dc2626;
    font-size: 1.125rem;
}

.quantity-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.quantity-info small {
    color: #6b7280;
    font-size: 0.75rem;
}

.time-remaining {
    font-weight: 500;
    color: #059669;
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    text-align: center;
    border: 1px solid #e5e7eb;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.progress-container {
    margin-top: 1rem;
}

.progress-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background-color: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background-color: #3b82f6;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.875rem;
    color: #6b7280;
    text-align: right;
}

@media (max-width: 768px) {
    .voucher-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .info-item span {
        text-align: left;
    }
    
    .quantity-info {
        align-items: flex-start;
    }
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
