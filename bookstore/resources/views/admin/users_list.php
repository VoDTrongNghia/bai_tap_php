<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Quản lý người dùng</h2>
            <div class="admin-card-actions">
                <button class="btn btn-primary" onclick="refreshUsers()">
                    <i class="fas fa-sync"></i> Làm mới
                </button>
            </div>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <span class="user-id">#<?= htmlspecialchars($user['id']) ?></span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-name"><?= htmlspecialchars($user['ho_ten'] ?? $user['ten_dang_nhap'] ?? 'N/A') ?></div>
                                    <small class="text-muted">@<?= htmlspecialchars($user['ten_dang_nhap']) ?></small>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['so_dien_thoai'] ?? 'N/A') ?></td>
                            <td>
                                <?php 
                                $role = $user['vai_tro'] ?? 'khach_hang';
                                $roleClass = $role === 'quan_tri_vien' ? 'role-admin' : 'role-user';
                                $roleText = $role === 'quan_tri_vien' ? 'Admin' : 'Khách hàng';
                                ?>
                                <span class="role-badge <?= $roleClass ?>">
                                    <?= $roleText ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d/m/Y H:i', strtotime($user['ngay_tao'] ?? 'now')) ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-info" onclick="viewUser(<?= $user['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editUser(<?= $user['id'] ?>)" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($role !== 'quan_tri_vien'): ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>Chưa có người dùng nào</p>
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
function viewUser(userId) {
    window.location.href = '<?= BASE_URL ?>admin?page=userDetail&id=' + userId;
}

function editUser(userId) {
    window.location.href = '<?= BASE_URL ?>admin?page=editUser&id=' + userId;
}

function deleteUser(userId) {
    if (!confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác!')) {
        return;
    }
    
    window.location.href = '<?= BASE_URL ?>admin?page=deleteUser&id=' + userId;
}

function refreshUsers() {
    location.reload();
}
</script>

<style>
.user-id {
    font-family: monospace;
    font-weight: bold;
    color: #6b7280;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 500;
    color: #374151;
}

.user-info .text-muted {
    color: #9ca3af;
    font-size: 0.75rem;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.role-admin {
    background-color: #fef3c7;
    color: #92400e;
}

.role-user {
    background-color: #dbeafe;
    color: #1e40af;
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
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>
