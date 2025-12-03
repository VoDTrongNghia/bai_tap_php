<?php require_once __DIR__ . '/../layout/admin_header.php'; ?>

<div class="admin-dashboard">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Chi tiết người dùng #<?= htmlspecialchars($user['id']) ?></h2>
            <div class="admin-card-actions">
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                <button class="btn btn-warning" onclick="editUser(<?= $user['id'] ?>)">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </button>
                <?php if ($user['vai_tro'] !== 'quan_tri_vien'): ?>
                <button class="btn btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                    <i class="fas fa-trash"></i> Xóa
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="user-detail-grid">
            <!-- Thông tin cá nhân -->
            <div class="user-info-section">
                <h3>Thông tin cá nhân</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>ID:</label>
                        <span>#<?= htmlspecialchars($user['id']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tên đăng nhập:</label>
                        <span><?= htmlspecialchars($user['ten_dang_nhap']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Họ tên:</label>
                        <span><?= htmlspecialchars($user['ho_ten'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= htmlspecialchars($user['email'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Số điện thoại:</label>
                        <span><?= htmlspecialchars($user['so_dien_thoai'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Địa chỉ:</label>
                        <span><?= htmlspecialchars($user['dia_chi'] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Thông tin tài khoản -->
            <div class="user-account-section">
                <h3>Thông tin tài khoản</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Vai trò:</label>
                        <?php 
                        $role = $user['vai_tro'] ?? 'khach_hang';
                        $roleClass = $role === 'quan_tri_vien' ? 'role-admin' : 'role-user';
                        $roleText = $role === 'quan_tri_vien' ? 'Admin' : 'Khách hàng';
                        ?>
                        <span class="role-badge <?= $roleClass ?>"><?= $roleText ?></span>
                    </div>
                    <div class="info-item">
                        <label>Ngày tạo:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($user['ngay_tao'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Cập nhật lần cuối:</label>
                        <span><?= date('d/m/Y H:i:s', strtotime($user['ngay_cap_nhat'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Trạng thái:</label>
                        <span class="status-badge status-active">Hoạt động</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lịch sử đơn hàng (nếu có) -->
        <div class="user-orders-section">
            <h3>Lịch sử đơn hàng</h3>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Chức năng đang được phát triển...
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
    window.location.href = '<?= BASE_URL ?>admin?page=users';
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
</script>

<style>
.user-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.user-info-section,
.user-account-section,
.user-orders-section {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.user-info-section h3,
.user-account-section h3,
.user-orders-section h3 {
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

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-info {
    background-color: #dbeafe;
    color: #1e40af;
    border: 1px solid #3b82f6;
}

@media (max-width: 768px) {
    .user-detail-grid {
        grid-template-columns: 1fr;
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
