<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chỉnh sửa người dùng #<?= htmlspecialchars($user['id']) ?></h2>
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
        
        <form method="POST" action="<?= BASE_URL ?>admin?page=updateUser&id=<?= $user['id'] ?>" class="user-form">
            <div class="form-grid">
                <!-- Thông tin cơ bản -->
                <div class="form-section">
                    <h3>Thông tin cơ bản</h3>
                    
                    <div class="form-group">
                        <label for="ho_ten">Họ tên *</label>
                        <input type="text" id="ho_ten" name="ho_ten" value="<?= htmlspecialchars($user['ho_ten'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="so_dien_thoai">Số điện thoại</label>
                        <input type="tel" id="so_dien_thoai" name="so_dien_thoai" value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ</label>
                        <textarea id="dia_chi" name="dia_chi" rows="3"><?= htmlspecialchars($user['dia_chi'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <!-- Thông tin tài khoản -->
                <div class="form-section">
                    <h3>Thông tin tài khoản</h3>
                    
                    <div class="form-group">
                        <label for="ten_dang_nhap">Tên đăng nhập</label>
                        <input type="text" id="ten_dang_nhap" value="<?= htmlspecialchars($user['ten_dang_nhap']) ?>" readonly>
                        <small class="form-help">Không thể thay đổi tên đăng nhập</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="vai_tro">Vai trò</label>
                        <select id="vai_tro" name="vai_tro">
                            <option value="khach_hang" <?= $user['vai_tro'] === 'khach_hang' ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="quan_tri_vien" <?= $user['vai_tro'] === 'quan_tri_vien' ? 'selected' : '' ?>>Quản trị viên</option>
                        </select>
                        <small class="form-help">Cảnh báo: Thay đổi vai trò có thể ảnh hưởng đến quyền truy cập</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày tạo</label>
                        <input type="text" value="<?= date('d/m/Y H:i:s', strtotime($user['ngay_tao'])) ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Cập nhật lần cuối</label>
                        <input type="text" value="<?= date('d/m/Y H:i:s', strtotime($user['ngay_cap_nhat'])) ?>" readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
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
    window.location.href = '<?= BASE_URL ?>admin?page=users';
}

// Form validation
document.querySelector('.user-form').addEventListener('submit', function(e) {
    const hoTen = document.getElementById('ho_ten').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!hoTen) {
        alert('Vui lòng nhập họ tên');
        e.preventDefault();
        return;
    }
    
    if (!email) {
        alert('Vui lòng nhập email');
        e.preventDefault();
        return;
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Email không hợp lệ');
        e.preventDefault();
        return;
    }
});
</script>

<style>
.user-form {
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

.form-group input[readonly] {
    background-color: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
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
