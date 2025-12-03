<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý Danh mục</h2>
            <div class="admin-card-actions">
                <button class="btn btn-primary" onclick="addCategory()">
                    <i class="fas fa-plus"></i> Thêm danh mục mới
                </button>
                <button class="btn btn-secondary" onclick="refreshCategories()">
                    <i class="fas fa-sync"></i> Làm mới
                </button>
            </div>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Số lượng sách</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td>
                                <span class="category-id">#<?= htmlspecialchars($category['id']) ?></span>
                            </td>
                            <td>
                                <div class="category-name">
                                    <strong><?= htmlspecialchars($category['ten_danh_muc']) ?></strong>
                                </div>
                            </td>
                            <td>
                                <div class="book-count">
                                    <?php 
                                    $bookCount = \App\Models\Category::getBookCount($category['id']);
                                    echo $bookCount;
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($category['ngay_tao'])) ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewCategory(<?= $category['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editCategory(<?= $category['id'] ?>)" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= $category['id'] ?>, <?= $bookCount ?>)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-folder"></i>
                                    <p>Chưa có danh mục nào</p>
                                    <button class="btn btn-primary" onclick="addCategory()">Thêm danh mục đầu tiên</button>
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
function addCategory() {
    window.location.href = '<?= BASE_URL ?>admin?page=addCategory';
}

function viewCategory(categoryId) {
    window.location.href = '<?= BASE_URL ?>admin?page=categoryDetail&id=' + categoryId;
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

function refreshCategories() {
    location.reload();
}
</script>

<style>
.category-id {
    font-family: monospace;
    font-weight: bold;
    color: #6b7280;
}

.category-name {
    font-weight: 500;
    color: #374151;
}

.book-count {
    font-weight: 600;
    color: #059669;
    text-align: center;
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
}
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
