<?php /** @var array $user */ ?>
<?php /** @var App\Models\Order[] $orders */ ?>
<div class="account-page">
    <h1>Tài khoản của tôi</h1>
    
    <div class="account-container">
        <div class="account-sidebar">
            <nav class="account-nav">
                <a href="#profile" class="nav-item active" onclick="showSection('profile'); return false;">
                    <i class="fas fa-user"></i> Thông tin cá nhân
                </a>
                <a href="#address" class="nav-item" onclick="showSection('address'); return false;">
                    <i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng
                </a>
                <a href="<?= $baseUrl ?>orders" class="nav-item">
                    <i class="fas fa-shopping-bag"></i> Lịch sử đơn hàng
                </a>
                <a href="#password" class="nav-item" onclick="showSection('password'); return false;">
                    <i class="fas fa-lock"></i> Đổi mật khẩu
                </a>
            </nav>
        </div>
        
        <div class="account-content">
            <!-- Profile Section -->
            <div id="profile-section" class="account-section active">
                <h2><i class="fas fa-user"></i> Thông tin cá nhân</h2>
                <form id="profile-form" class="account-form">
                    <div class="form-group">
                        <label for="name">Họ và tên *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                            required
                            class="form-input"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                            required
                            class="form-input"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                            class="form-input"
                        />
                    </div>
                    
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Lưu thông tin
                    </button>
                </form>
            </div>
            
            <!-- Address Section -->
            <div id="address-section" class="account-section">
                <h2><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng</h2>
                <form id="address-form" class="account-form">
                    <div class="form-group">
                        <label for="address">Địa chỉ *</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="4" 
                            required
                            class="form-input"
                        ><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Lưu địa chỉ
                    </button>
                </form>
            </div>
            
            <!-- Orders Section -->
            <div id="orders-section" class="account-section">
                <h2><i class="fas fa-shopping-bag"></i> Lịch sử đơn hàng</h2>
                <div class="orders-list">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <p>Bạn chưa có đơn hàng nào.</p>
                            <a href="<?= $baseUrl ?>books" class="btn">Mua sắm ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="orders-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($order->orderCode) ?></strong></td>
                                            <td><?= date('d/m/Y H:i', strtotime($order->createdAt)) ?></td>
                                            <td><?= number_format($order->total, 0, ',', '.') ?> đ</td>
                                            <td>
                                                <span class="status-badge status-<?= htmlspecialchars($order->status) ?>">
                                                    <?php
                                                    $statusLabels = [
                                                        'pending' => 'Chờ xử lý',
                                                        'processing' => 'Đang xử lý',
                                                        'shipped' => 'Đã giao hàng',
                                                        'completed' => 'Hoàn thành',
                                                        'cancelled' => 'Đã hủy'
                                                    ];
                                                    echo $statusLabels[$order->status] ?? $order->status;
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= $baseUrl ?>account/order/<?= urlencode($order->orderCode) ?>" class="btn-view">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Password Section -->
            <div id="password-section" class="account-section">
                <h2><i class="fas fa-lock"></i> Đổi mật khẩu</h2>
                <form id="password-form" class="account-form">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại *</label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            required
                            class="form-input"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới *</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            required
                            class="form-input"
                            minlength="6"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới *</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            class="form-input"
                            minlength="6"
                        />
                    </div>
                    
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showSection(section) {
    // Hide all sections
    document.querySelectorAll('.account-section').forEach(sec => {
        sec.classList.remove('active');
    });
    
    // Remove active from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(section + '-section').classList.add('active');
    
    // Add active to selected nav item
    event.target.closest('.nav-item').classList.add('active');
}

// Profile form
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    
    fetch('<?= $baseUrl ?>account/profile', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showToast) {
                window.showToast(data.message, 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        if (window.showToast) {
            window.showToast(err.message || 'Có lỗi xảy ra khi cập nhật thông tin.', 'error');
        } else {
            alert(err.message || 'Có lỗi xảy ra khi cập nhật thông tin.');
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

// Address form
document.getElementById('address-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    
    fetch('<?= $baseUrl ?>account/address', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showToast) {
                window.showToast(data.message, 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        if (window.showToast) {
            window.showToast(err.message || 'Có lỗi xảy ra khi cập nhật địa chỉ.', 'error');
        } else {
            alert(err.message || 'Có lỗi xảy ra khi cập nhật địa chỉ.');
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

// Password form
document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đổi...';
    
    fetch('<?= $baseUrl ?>account/password', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showToast) {
                window.showToast(data.message, 'success');
            }
            this.reset();
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        if (window.showToast) {
            window.showToast(err.message || 'Có lỗi xảy ra khi đổi mật khẩu.', 'error');
        } else {
            alert(err.message || 'Có lỗi xảy ra khi đổi mật khẩu.');
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
</script>

<style>
.account-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.account-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
    margin-top: 20px;
}

.account-sidebar {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.account-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #1f2937;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s;
}

.nav-item:hover {
    background: #f3f4f6;
}

.nav-item.active {
    background: #2b6cb0;
    color: white;
}

.account-content {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.account-section {
    display: none;
}

.account-section.active {
    display: block;
}

.account-section h2 {
    margin: 0 0 24px 0;
    color: #2b6cb0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 24px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 12px;
}

.account-form {
    max-width: 600px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1f2937;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s;
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: #2b6cb0;
}

.btn-save {
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-save:hover:not(:disabled) {
    background: #1e4a72;
}

.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.empty-state p {
    margin: 0 0 20px 0;
    font-size: 16px;
}

.orders-table {
    overflow-x: auto;
}

.orders-table table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.orders-table th {
    background: #2b6cb0;
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
}

.orders-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.orders-table tbody tr:hover {
    background: #f8fafc;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-shipped {
    background: #d1fae5;
    color: #065f46;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.btn-view {
    background: #2b6cb0;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}

.btn-view:hover {
    background: #1e4a72;
}

@media (max-width: 768px) {
    .account-container {
        grid-template-columns: 1fr;
    }
    
    .account-sidebar {
        position: static;
    }
    
    .account-nav {
        flex-direction: row;
        overflow-x: auto;
    }
    
    .nav-item {
        white-space: nowrap;
    }
}
</style>

