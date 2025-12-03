<?php /** @var string $title */ ?>
<?php /** @var string $appName */ ?>
<?php /** @var array $errors */ ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars(($title ?? 'Đăng ký') . ' - ' . ($appName ?? 'BookStore')) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
/* Import Poppins Font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* Hide header and footer on register page */
header.site-header,
footer.site-footer {
    display: none !important;
}

/* Reset body and main styles */
html {
    height: 100%;
}

body {
    margin: 0 !important;
    padding: 0 !important;
    font-family: 'Poppins', sans-serif !important;
    min-height: 100vh !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

main {
    min-height: calc(100vh - 0px) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    margin: 0 !important;
}

/* Register Container */
.register-container {
    width: 100%;
    max-width: 1200px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    min-height: 600px;
}

/* Left Side - Brand Section */
.register-brand {
    background: linear-gradient(135deg, #2b6cb0 0%, #1e4a72 100%);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.register-brand::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 8s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

.brand-content {
    position: relative;
    z-index: 1;
    text-align: center;
}

.brand-logo {
    font-size: 64px;
    margin-bottom: 20px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.brand-title {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
    letter-spacing: 1px;
}

.brand-subtitle {
    font-size: 18px;
    opacity: 0.9;
    line-height: 1.6;
    font-weight: 300;
}

.brand-features {
    margin-top: 40px;
    list-style: none;
    padding: 0;
}

.brand-features li {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 400;
}

.brand-features li i {
    margin-right: 12px;
    font-size: 20px;
    width: 24px;
}

/* Right Side - Register Form */
.register-form-section {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px 50px;
    overflow-y: auto;
    max-height: 600px;
}

.register-header {
    text-align: center;
    margin-bottom: 30px;
}

.register-title {
    font-size: 32px;
    font-weight: 700;
    color: #2b6cb0;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
}

.register-subtitle {
    color: #6b7280;
    font-size: 15px;
    font-weight: 400;
}

/* Form Styles */
.register-form {
    width: 100%;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
    z-index: 1;
}

.form-input {
    width: 100%;
    padding: 14px 16px 14px 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
    outline: none;
    background: #fafbfc;
    box-sizing: border-box;
}

.form-input:focus {
    border-color: #2b6cb0;
    background: white;
    box-shadow: 0 0 0 4px rgba(43, 108, 176, 0.1);
}

.form-input::placeholder {
    color: #9ca3af;
}

textarea.form-input {
    padding-left: 50px;
    resize: vertical;
    min-height: 80px;
}

.password-toggle-btn {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
    transition: color 0.2s;
    z-index: 1;
}

.password-toggle-btn:hover {
    color: #2b6cb0;
}

.form-options {
    margin-bottom: 25px;
    font-size: 14px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    cursor: pointer;
    user-select: none;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #2b6cb0;
}

.checkbox-label a {
    color: #2b6cb0;
    text-decoration: none;
    font-weight: 500;
}

.checkbox-label a:hover {
    text-decoration: underline;
}

.register-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #2b6cb0 0%, #1e4a72 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(43, 108, 176, 0.3);
}

.register-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(43, 108, 176, 0.4);
}

.register-btn:active {
    transform: translateY(0);
}

.login-link-section {
    text-align: center;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e5e7eb;
}

.login-link-text {
    color: #6b7280;
    font-size: 14px;
}

.login-link {
    color: #2b6cb0;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.login-link:hover {
    color: #1e4a72;
    text-decoration: underline;
}

/* Alert Messages */
.alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.alert-success {
    background: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
}

.alert i {
    font-size: 18px;
    flex-shrink: 0;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
    list-style: disc;
}

.alert li {
    margin-bottom: 5px;
}

/* Responsive Design */
@media (max-width: 968px) {
    .register-container {
        grid-template-columns: 1fr;
        max-width: 500px;
    }
    
    .register-brand {
        display: none;
    }
    
    .register-form-section {
        padding: 40px 35px;
        max-height: none;
    }
}

@media (max-width: 568px) {
    .register-container {
        border-radius: 16px;
    }
    
    .register-form-section {
        padding: 35px 25px;
    }
    
    .register-title {
        font-size: 26px;
    }
    
    .register-subtitle {
        font-size: 14px;
    }
}

/* Animation for form elements */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.register-form-section > * {
    animation: fadeIn 0.5s ease;
}

.register-form-section > *:nth-child(2) {
    animation-delay: 0.1s;
}

.register-form-section > *:nth-child(3) {
    animation-delay: 0.2s;
}
</style>
</head>
<body>
<main>
<div class="register-container">
    <!-- Left Side - Brand Section -->
    <div class="register-brand">
        <div class="brand-content">
            <div class="brand-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="brand-title"><?= htmlspecialchars($appName ?? 'BookStore') ?></h1>
            <p class="brand-subtitle">Tạo tài khoản mới để bắt đầu mua sắm</p>
            
            <ul class="brand-features">
                <li>
                    <i class="fas fa-check-circle"></i>
                    Đăng ký miễn phí và nhanh chóng
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    Nhận ưu đãi đặc biệt cho thành viên
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    Theo dõi đơn hàng dễ dàng
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    Lưu địa chỉ giao hàng
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Right Side - Register Form -->
    <div class="register-form-section">
        <div class="register-header">
            <h1 class="register-title">Đăng ký tài khoản</h1>
            <p class="register-subtitle">Điền thông tin để tạo tài khoản mới</p>
        </div>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form class="register-form" method="POST" action="<?= $baseUrl ?>register">
            <div class="form-group">
                <label class="form-label" for="ten_dang_nhap">
                    <i class="fas fa-user"></i> Tên đăng nhập *
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-user"></i>
                    <input 
                        type="text" 
                        id="ten_dang_nhap" 
                        name="ten_dang_nhap" 
                        class="form-input"
                        placeholder="Nhập tên đăng nhập"
                        value="<?= htmlspecialchars($_POST['ten_dang_nhap'] ?? '') ?>"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="ho_ten">
                    <i class="fas fa-user"></i> Họ và tên *
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-id-card"></i>
                    <input 
                        type="text" 
                        id="ho_ten" 
                        name="ho_ten" 
                        class="form-input"
                        placeholder="Nhập họ và tên của bạn"
                        value="<?= htmlspecialchars($_POST['ho_ten'] ?? $_POST['name'] ?? '') ?>"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope"></i> Email *
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-envelope"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input"
                        placeholder="Nhập email của bạn"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="so_dien_thoai">
                    <i class="fas fa-phone"></i> Số điện thoại
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-phone"></i>
                    <input 
                        type="tel" 
                        id="so_dien_thoai" 
                        name="so_dien_thoai" 
                        class="form-input"
                        placeholder="Nhập số điện thoại (tùy chọn)"
                        value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? $_POST['phone'] ?? '') ?>"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="dia_chi">
                    <i class="fas fa-map-marker-alt"></i> Địa chỉ
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-map-marker-alt"></i>
                    <textarea 
                        id="dia_chi" 
                        name="dia_chi" 
                        class="form-input"
                        rows="3"
                        placeholder="Nhập địa chỉ (tùy chọn)"
                    ><?= htmlspecialchars($_POST['dia_chi'] ?? $_POST['address'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock"></i> Mật khẩu *
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-lock"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input"
                        placeholder="Nhập mật khẩu (ít nhất 6 ký tự)"
                        required
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="confirm_password">
                    <i class="fas fa-lock"></i> Xác nhận mật khẩu *
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-lock"></i>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-input"
                        placeholder="Nhập lại mật khẩu"
                        required
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye" id="confirm_password-icon"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" required>
                    <span>Tôi đồng ý với <a href="<?= $baseUrl ?>terms" target="_blank">điều khoản sử dụng</a></span>
                </label>
            </div>
            
            <button type="submit" class="register-btn">
                <i class="fas fa-user-plus"></i>
                Đăng ký
            </button>
        </form>
        
        <div class="login-link-section">
            <p class="login-link-text">
                Đã có tài khoản? 
                <a href="<?= $baseUrl ?>login" class="login-link">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>
</main>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>
