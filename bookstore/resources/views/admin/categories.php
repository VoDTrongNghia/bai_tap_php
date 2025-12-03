<?php /** @var string[] $categories */ ?>
<?php
// Authentication is handled by AdminController via AdminHelper::requireAdmin()
// No need for manual session checks here
?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý Danh mục</h2>
            <button class="btn btn-primary" onclick="showAddCategoryModal()">
                <i class="fas fa-plus"></i> Thêm danh mục
            </button>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Chưa có danh mục nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($category['ten_danh_muc'] ?? '') ?></td>
                                <td><?= htmlspecialchars($category['mo_ta'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    // Count books in this category
                                    try {
                                        $pdo = \App\Database::getConnection();
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sach WHERE id_danh_muc = ?");
                                        $stmt->execute([$category['id']]);
                                        echo $stmt->fetchColumn();
                                    } catch (\Exception $e) {
                                        echo '0';
                                    }
                                    ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($category['ngay_tao'] ?? 'now')) ?></td>
                                <td class="actions">
                                    <button class="btn btn-sm btn-edit" onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['ten_danh_muc']) ?>', '<?= htmlspecialchars($category['mo_ta'] ?? '') ?>')">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <?php if (($category['book_count'] ?? 0) == 0): ?>
                                        <button class="btn btn-sm btn-delete" onclick="deleteCategory(<?= $category['id'] ?>)">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled title="Không thể xóa danh mục có sản phẩm">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thêm Danh mục Mới</h3>
            <span class="close" onclick="closeAddCategoryModal()">&times;</span>
        </div>
        <form action="<?= $baseUrl ?>admin/category-create" method="POST">
            <div class="form-group">
                <label for="ten_danh_muc">Tên danh mục <span class="required">*</span></label>
                <input type="text" id="ten_danh_muc" name="ten_danh_muc" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="mo_ta">Mô tả</label>
                <textarea id="mo_ta" name="mo_ta" class="form-input" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeAddCategoryModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Sửa Danh mục</h3>
            <span class="close" onclick="closeEditCategoryModal()">&times;</span>
        </div>
        <form action="<?= $baseUrl ?>admin/category-update" method="POST">
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
                <label for="edit_ten_danh_muc">Tên danh mục <span class="required">*</span></label>
                <input type="text" id="edit_ten_danh_muc" name="ten_danh_muc" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_mo_ta">Mô tả</label>
                <textarea id="edit_mo_ta" name="mo_ta" class="form-input" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeEditCategoryModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<style>
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
    margin: 10% auto;
    padding: 0;
    border-radius: 8px;
    width: 500px;
    max-width: 90%;
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

.modal-content form {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-input:focus {
    outline: none;
    border-color: #2b6cb0;
    box-shadow: 0 0 0 2px rgba(43, 108, 176, 0.2);
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.required {
    color: #ef4444;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-edit:hover {
    background: #d97706;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.text-center {
    text-align: center;
    padding: 40px;
    color: #6b7280;
}

.actions {
    display: flex;
    gap: 8px;
}
</style>

<script>
function showAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'block';
}

function closeAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'none';
}

function editCategory(id, name, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_ten_danh_muc').value = name;
    document.getElementById('edit_mo_ta').value = description;
    document.getElementById('editCategoryModal').style.display = 'block';
}

function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').style.display = 'none';
}

function deleteCategory(id) {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        window.location.href = '<?= $baseUrl ?>admin/category-delete?id=' + id;
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
    }
}
</script>

