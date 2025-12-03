<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Thêm Voucher mới</h2>
            <div class="admin-card-actions">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
            </div>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>admin?page=saveVoucher" class="voucher-form">
            <div class="form-grid">
                <!-- Thông tin cơ bản -->
                <div class="form-section">
                    <h3>Thông tin cơ bản</h3>
                    
                    <div class="form-group">
                        <label for="ma_voucher">Mã voucher *</label>
                        <input type="text" id="ma_voucher" name="ma_voucher" value="<?= htmlspecialchars($_POST['ma_voucher'] ?? '') ?>" required>
                        <small class="form-help">Mã voucher phải là duy nhất</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="mo_ta">Mô tả</label>
                        <textarea id="mo_ta" name="mo_ta" rows="3"><?= htmlspecialchars($_POST['mo_ta'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="trang_thai">Trạng thái</label>
                        <select id="trang_thai" name="trang_thai">
                            <option value="1" <?= ($_POST['trang_thai'] ?? 1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= ($_POST['trang_thai'] ?? 1) == 0 ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                
                <!-- Thông tin giảm giá -->
                <div class="form-section">
                    <h3>Thông tin giảm giá</h3>
                    
                    <div class="form-group">
                        <label for="kieu_giam">Kiểu giảm giá</label>
                        <select id="kieu_giam" name="kieu_giam" onchange="toggleDiscountType()">
                            <option value="percent" <?= ($_POST['kieu_giam'] ?? 'percent') === 'percent' ? 'selected' : '' ?>>Phần trăm (%)</option>
                            <option value="fixed" <?= ($_POST['kieu_giam'] ?? 'percent') === 'fixed' ? 'selected' : '' ?>>Số tiền cố định (₫)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="giam_gia">Giảm giá *</label>
                        <input type="number" id="giam_gia" name="giam_gia" value="<?= htmlspecialchars($_POST['giam_gia'] ?? '') ?>" step="0.01" min="0" required>
                        <small class="form-help" id="discount-help">Nhập phần trăm giảm (0-100)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="gia_tri_toi_thieu">Giá trị tối thiểu đơn hàng</label>
                        <input type="number" id="gia_tri_toi_thieu" name="gia_tri_toi_thieu" value="<?= htmlspecialchars($_POST['gia_tri_toi_thieu'] ?? '0') ?>" step="0.01" min="0">
                        <small class="form-help">Giá trị đơn hàng tối thiểu để áp dụng voucher</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="so_luong">Số lượng *</label>
                        <input type="number" id="so_luong" name="so_luong" value="<?= htmlspecialchars($_POST['so_luong'] ?? '1') ?>" min="1" required>
                        <small class="form-help">Số lượng voucher có thể sử dụng</small>
                    </div>
                </div>
                
                <!-- Thời gian -->
                <div class="form-section">
                    <h3>Thời gian áp dụng</h3>
                    
                    <div class="form-group">
                        <label for="ngay_bat_dau">Ngày bắt đầu *</label>
                        <input type="datetime-local" id="ngay_bat_dau" name="ngay_bat_dau" value="<?= htmlspecialchars($_POST['ngay_bat_dau'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ngay_ket_thuc">Ngày kết thúc *</label>
                        <input type="datetime-local" id="ngay_ket_thuc" name="ngay_ket_thuc" value="<?= htmlspecialchars($_POST['ngay_ket_thuc'] ?? '') ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu voucher
                </button>
                <button type="button" class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function goBack() {
    window.location.href = '<?= BASE_URL ?>admin?page=vouchers';
}

function toggleDiscountType() {
    const type = document.getElementById('kieu_giam').value;
    const helpText = document.getElementById('discount-help');
    
    if (type === 'percent') {
        helpText.textContent = 'Nhập phần trăm giảm (0-100)';
        document.getElementById('giam_gia').max = '100';
    } else {
        helpText.textContent = 'Nhập số tiền giảm (VNĐ)';
        document.getElementById('giam_gia').removeAttribute('max');
    }
}

// Form validation
document.querySelector('.voucher-form').addEventListener('submit', function(e) {
    const maVoucher = document.getElementById('ma_voucher').value.trim();
    const giamGia = parseFloat(document.getElementById('giam_gia').value);
    const soLuong = parseInt(document.getElementById('so_luong').value);
    const ngayBatDau = document.getElementById('ngay_bat_dau').value;
    const ngayKetThuc = document.getElementById('ngay_ket_thuc').value;
    const kieuGiam = document.getElementById('kieu_giam').value;
    
    if (!maVoucher) {
        alert('Vui lòng nhập mã voucher');
        e.preventDefault();
        return;
    }
    
    if (isNaN(giamGia) || giamGia <= 0) {
        alert('Giảm giá phải lớn hơn 0');
        e.preventDefault();
        return;
    }
    
    if (kieuGiam === 'percent' && giamGia > 100) {
        alert('Giảm giá theo phần trăm không được vượt quá 100%');
        e.preventDefault();
        return;
    }
    
    if (isNaN(soLuong) || soLuong <= 0) {
        alert('Số lượng phải lớn hơn 0');
        e.preventDefault();
        return;
    }
    
    if (!ngayBatDau || !ngayKetThuc) {
        alert('Vui lòng chọn ngày bắt đầu và ngày kết thúc');
        e.preventDefault();
        return;
    }
    
    if (new Date(ngayBatDau) >= new Date(ngayKetThuc)) {
        alert('Ngày kết thúc phải sau ngày bắt đầu');
        e.preventDefault();
        return;
    }
});

// Initialize
toggleDiscountType();
</script>

<style>
.voucher-form {
    max-width: 100%;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.form-section h3 {
    margin-bottom: 1.5rem;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: border-color 0.15s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-help {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions button {
        width: 100%;
    }
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
