<?php /** @var array $users */ ?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý người dùng</h2>
            <a href="<?= $baseUrl ?>admin/user-create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm người dùng mới
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
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Chưa có người dùng nào. <a href="<?= $baseUrl ?>admin/user-create">Thêm người dùng mới</a></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id'] ?? $user['ma_nguoi_dung'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['ten_dang_nhap'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['ho_ten'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['so_dien_thoai'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge badge-<?= (($user['vai_tro'] ?? 'user') === 'admin' || ($user['vai_tro'] ?? '') === 'quan_tri_vien') ? 'danger' : 'info' ?>">
                                        <?= htmlspecialchars($user['vai_tro'] ?? 'user') ?>
                                    </span>
                                </td>
                                <td><?= !empty($user['ngay_tao']) ? date('d/m/Y', strtotime($user['ngay_tao'])) : 'N/A' ?></td>
                                <td class="actions">
                                    <a href="<?= $baseUrl ?>admin/user-edit?id=<?= urlencode($user['id'] ?? $user['ma_nguoi_dung'] ?? '') ?>" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <?php if (isset($_SESSION['user_id']) && ($user['id'] ?? $user['ma_nguoi_dung'] ?? 0) !== (int)$_SESSION['user_id']): ?>
                                        <a href="<?= $baseUrl ?>admin/user-delete?id=<?= urlencode($user['id'] ?? $user['ma_nguoi_dung'] ?? '') ?>" 
                                           class="btn btn-sm btn-delete"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
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

<style>
.text-center {
    text-align: center;
    padding: 40px;
    color: #6b7280;
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
</style>

