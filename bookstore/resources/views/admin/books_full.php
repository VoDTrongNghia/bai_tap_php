<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý sản phẩm</h2>
            <a href="<?= BASE_URL ?>admin/books/create" class="btn btn-primary">
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
                            <td colspan="6" class="text-center">Chưa có sách nào. <a href="<?= BASE_URL ?>admin/books/create">Thêm sách mới</a></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?= $book->getId() ?></td>
                                <td>
                                    <img src="<?= $book->getImageUrl() ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" style="width: 50px; height: 70px; object-fit: cover;">
                                </td>
                                <td><?= htmlspecialchars($book->getTitle()) ?></td>
                                <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                                <td><?= number_format($book->getPrice()) ?> đ</td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/books/edit/<?= $book->getId() ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>admin/books/delete/<?= $book->getId() ?>" style="display: inline;">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
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
