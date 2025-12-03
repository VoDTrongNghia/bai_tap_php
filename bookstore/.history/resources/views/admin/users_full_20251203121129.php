<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý người dùng</h2>
            <a href="<?= BASE_URL ?>admin/users/add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm người dùng
            </a>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Chưa có người dùng nào. <a href="<?= BASE_URL ?>admin/users/add">Thêm người dùng mới</a></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?? '' ?></td>
                                <td><?= htmlspecialchars($user['ten_dang_nhap'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['ho_ten'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $user['vai_tro'] ?? 'user' ?>">
                                        <?= ucfirst($user['vai_tro'] ?? 'user') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <?php if ($user['vai_tro'] !== 'admin'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>admin/users/delete/<?= $user['id'] ?>" style="display: inline;">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
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

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
