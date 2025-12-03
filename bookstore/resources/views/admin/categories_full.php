<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý danh mục</h2>
            <a href="<?= BASE_URL ?>admin/categories/add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Chưa có danh mục nào. <a href="<?= BASE_URL ?>admin/categories/add">Thêm danh mục mới</a></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category['id'] ?></td>
                                <td><?= htmlspecialchars($category['ten_danh_muc']) ?></td>
                                <td><?= htmlspecialchars($category['mo_ta'] ?? '') ?></td>
                                <td><?= $category['so_luong_san_pham'] ?? 0 ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/categories/edit/<?= $category['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>admin/categories/delete/<?= $category['id'] ?>" style="display: inline;">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
