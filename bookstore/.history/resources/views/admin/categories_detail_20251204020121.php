<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chi tiết Danh mục #<?= htmlspecialchars($category['id']) ?></h2>
            <div class="admin-card-actions">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button class="btn btn-warning" onclick="editCategory(<?= $category['id'] ?>)">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </button>
                <button class="btn btn-danger" onclick="deleteCategory(<?= $category['id'] ?>, <?= $bookCount ?>)">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
        
        <div class="category-detail-grid">
            <!-- Thông tin cơ bản -->
            <div class="category-info-section">
                <h3>Thông tin cơ bản</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>ID:</label>
                        <span>#<?= htmlspecialchars($category['id']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tên danh mục:</label>
                        <span class="category-name"><?= htmlspecialchars($category['ten_danh_muc']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Ngày tạo:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($category['ngay_tao'])) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Thống kê -->
            <div class="category-stats-section">
                <h3>Thống kê</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= $bookCount ?></div>
                        <div class="stat-label">Số lượng sách</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $bookCount > 0 ? 'Có' : 'Không' ?></div>
                        <div class="stat-label">Trạng thái</div>
                    </div>
                </div>
                
                <?php if ($bookCount > 0): ?>
                    <div class="warning-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Danh mục này có <?= $bookCount ?> sách. Bạn cần xóa hoặc chuyển sách sang danh mục khác trước khi xóa danh mục này.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
    window.location.href = '<?= BASE_URL ?>admin?page=categories';
}

function editCategory(categoryId) {
    window.location.href = '<?= BASE_URL ?>admin?page=editCategory&id=' + categoryId;
}

function deleteCategory(categoryId, bookCount) {
    if (bookCount > 0) {
        alert('Không thể xóa danh mục này vì có ' + bookCount + ' sách thuộc danh mục này!');
        return;
    }
    
    if (!confirm('Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác!')) {
        return;
    }
    
    window.location.href = '<?= BASE_URL ?>admin?page=deleteCategory&id=' + categoryId;
}
</script>

<style>
.category-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.category-info-section,
.category-stats-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.category-info-section h3,
.category-stats-section h3 {
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

.category-name {
    font-weight: 600;
    color: #059669;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
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

.warning-box {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #f59e0b;
    border-radius: 0.5rem;
}

.warning-box i {
    font-size: 1.25rem;
    color: #f59e0b;
}

@media (max-width: 768px) {
    .category-detail-grid {
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
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
