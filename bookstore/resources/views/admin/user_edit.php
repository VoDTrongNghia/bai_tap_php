<?php /** @var App\Models\User $user */ ?>
<?php /** @var array $errors */ ?>
<?php /** @var string|null $success */ ?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Sửa thông tin người dùng</h2>
            <a href="<?= $baseUrl ?>admin?page=users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= $baseUrl ?>admin/user-edit?id=<?= urlencode($user->getId()) ?>" class="admin-form">
            <div class="form-group">
                <label class="form-label" for="ten_dang_nhap">Tên đăng nhập <span class="required">*</span></label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" class="form-input" 
                       value="<?= htmlspecialchars($_POST['ten_dang_nhap'] ?? $user->getTenDangNhap()) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="ho_ten">Họ tên <span class="required">*</span></label>
                <input type="text" id="ho_ten" name="ho_ten" class="form-input" 
                       value="<?= htmlspecialchars($_POST['ho_ten'] ?? $user->getHoTen()) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-input" 
                       value="<?= htmlspecialchars($_POST['email'] ?? $user->getEmail()) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" class="form-input" 
                       minlength="6">
                <small class="form-hint">Để trống nếu không muốn thay đổi mật khẩu. Mật khẩu phải có ít nhất 6 ký tự.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="so_dien_thoai">Số điện thoại</label>
                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" class="form-input" 
                       value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? $user->getSoDienThoai()) ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="dia_chi">Địa chỉ</label>
                <textarea id="dia_chi" name="dia_chi" class="form-textarea" rows="3"><?= htmlspecialchars($_POST['dia_chi'] ?? $user->getDiaChi()) ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="vai_tro">Vai trò <span class="required">*</span></label>
                <select id="vai_tro" name="vai_tro" class="form-select" required>
                    <option value="khach_hang" <?= (($_POST['vai_tro'] ?? $user->getVaiTro()) === 'khach_hang') ? 'selected' : '' ?>>Khách hàng</option>
                    <option value="admin" <?= (($_POST['vai_tro'] ?? $user->getVaiTro()) === 'admin') ? 'selected' : '' ?>>Quản trị viên</option>
                    <option value="quan_tri_vien" <?= (($_POST['vai_tro'] ?? $user->getVaiTro()) === 'quan_tri_vien') ? 'selected' : '' ?>>Quản trị viên</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="<?= $baseUrl ?>admin?page=users" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.required {
    color: #ef4444;
}

.form-hint {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}
</style>

