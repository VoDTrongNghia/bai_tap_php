<?php
// Authentication is handled by AdminController via AdminHelper::requireAdmin()
// No need for manual session checks here
?>
<?php /** @var App\Models\Book[] $books */ ?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý sản phẩm</h2>
            <a href="<?= $baseUrl ?>admin/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sách mới
            </a>
        </div>

        <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Giá</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Chưa có sách nào. <a href="<?= $baseUrl ?>admin/create">Thêm sách mới</a></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book->id) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($book->imageUrl ?? $book->getImageUrl()) ?>" 
                                     alt="<?= htmlspecialchars($book->title) ?>" 
                                     class="admin-thumbnail"
                                     // TẠM TẮT onerror để tránh load placeholder vô hạn
                                     // onerror="this.src='<?= $baseUrl ?>assets/images/books/placeholder.jpg'">
                            </td>
                            <td><?= htmlspecialchars($book->title) ?></td>
                            <td><?= htmlspecialchars($book->author) ?></td>
                            <td><?= number_format($book->price, 0, ',', '.') ?> đ</td>
                            <td class="actions">
                                <a href="<?= $baseUrl ?>admin/edit?id=<?= urlencode($book->id) ?>" class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="<?= $baseUrl ?>admin/delete?id=<?= urlencode($book->id) ?>" 
                                   class="btn btn-sm btn-delete"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<style>
.admin-thumbnail {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-edit {
    background: #3b82f6;
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.text-center {
    text-align: center;
    padding: 40px;
    color: #6b7280;
}
</style>

