<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chỉnh sửa Danh mục #<?= htmlspecialchars($category['id']) ?></h2>
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
        
        <form method="POST" action="<?= BASE_URL ?>admin?page=updateCategory&id=<?= $category['id'] ?>" class="category-form">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Thông tin danh mục</h3>
                    
                    <div class="form-group">
                        <label for="ten_danh_muc">Tên danh mục *</label>
                        <input type="text" id="ten_danh_muc" name="ten_danh_muc" value="<?= htmlspecialchars($category['ten_danh_muc']) ?>" required maxlength="150">
                        <small class="form-help">Tên danh mục phải là duy nhất và không vượt quá 150 ký tự</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Thông tin khác</label>
                        <div class="info-display">
                            <div class="info-row">
                                <span>ID:</span>
                                <span class="highlight">#<?= $category['id'] ?></span>
                            </div>
                            <div class="info-row">
                                <span>Ngày tạo:</span>
                                <span><?= date('d/m/Y H:i:s', strtotime($category['ngay_tao'])) ?></span>
                            </div>
                            <div class="info-row">
                                <span>Số lượng sách:</span>
                                <span class="highlight"><?= \App\Models\Category::getBookCount($category['id']) ?></span>
                            </div>
                        </div>
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
    window.location.href = '<?= BASE_URL ?>admin?page=categories';
}

// Form validation
document.querySelector('.category-form').addEventListener('submit', function(e) {
    const tenDanhMuc = document.getElementById('ten_danh_muc').value.trim();
    
    if (!tenDanhMuc) {
        alert('Vui lòng nhập tên danh mục');
        e.preventDefault();
        return;
    }
    
    if (tenDanhMuc.length > 150) {
        alert('Tên danh mục không được vượt quá 150 ký tự');
        e.preventDefault();
        return;
    }
});
</script>

<style>
.category-form {
    max-width: 100%;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr;
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

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: border-color 0.15s;
}

.form-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-help {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.info-display {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
    font-size: 0.875rem;
}

.info-row .highlight {
    font-weight: 600;
    color: #059669;
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
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions button {
        width: 100%;
    }
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
