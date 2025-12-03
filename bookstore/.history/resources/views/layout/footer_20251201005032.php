</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Column 1: Company Information -->
            <div class="footer-column">
                <h3>Thông tin công ty</h3>
                <div class="company-info">
                    <p><strong><?= htmlspecialchars($appName ?? APP_NAME ?? 'Bookstore') ?></strong></p>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận XYZ, TP.HCM</p>
                    <p><i class="fas fa-phone"></i> Hotline: 1900 1234</p>
                    <p><i class="fas fa-envelope"></i> Email: info@bookstore.com</p>
                    <p><i class="fas fa-clock"></i> Giờ làm việc: 8:00 - 22:00 (T2-CN)</p>
                </div>
            </div>
            
            <!-- Column 2: Customer Support -->
            <div class="footer-column">
                <h3>Hỗ trợ khách hàng</h3>
                <ul class="footer-links">
                    <li><a href="<?= $baseUrl ?>books">Hướng dẫn mua hàng</a></li>
                    <li><a href="<?= $baseUrl ?>books">Thông tin giao hàng</a></li>
                    <li><a href="<?= $baseUrl ?>books">Phương thức thanh toán</a></li>
                    <li><a href="<?= $baseUrl ?>books">Đổi trả hàng</a></li>
                    <li><a href="<?= $baseUrl ?>books">Câu hỏi thường gặp</a></li>
                    <li><a href="<?= $baseUrl ?>books">Liên hệ</a></li>
                    <li><a href="<?= $baseUrl ?>books">Góp ý</a></li>
                </ul>
            </div>
            
            <!-- Column 3: Social Media -->
            <div class="footer-column">
                <h3>Kết nối mạng xã hội</h3>
                <div class="social-links">
                    <a href="#" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="social-link youtube">
                        <i class="fab fa-youtube"></i>
                        <span>YouTube</span>
                    </a>
                    <a href="#" class="social-link tiktok">
                        <i class="fab fa-tiktok"></i>
                        <span>TikTok</span>
                    </a>
                    <a href="#" class="social-link zalo">
                        <i class="fab fa-zalo"></i>
                        <span>Zalo</span>
                    </a>
                </div>
                
                <div class="payment-methods">
                    <h4>Phương thức thanh toán</h4>
                    <div class="payment-icons">
                        <i class="fas fa-credit-card"></i>
                        <i class="fas fa-university"></i>
                        <i class="fab fa-paypal"></i>
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                </div>
            </div>
            
            <!-- Column 4: Newsletter Subscription -->
            <div class="footer-column">
                <h3>Đăng ký nhận tin</h3>
                <div class="newsletter">
                    <p>Nhận thông tin về sách mới, khuyến mãi và sự kiện đặc biệt</p>
                    <form class="newsletter-form" action="<?= $baseUrl ?>books" method="POST">
                        <input type="email" name="email" placeholder="Nhập email của bạn" required>
                        <button type="submit">Đăng ký</button>
                    </form>
                    <p class="newsletter-note">Chúng tôi cam kết không spam email</p>
                </div>
                
                <div class="app-download">
                    <h4>Tải ứng dụng</h4>
                    <div class="download-links">
                        <a href="#" class="app-link">
                            <i class="fab fa-apple"></i> App Store
                        </a>
                        <a href="#" class="app-link">
                            <i class="fab fa-google-play"></i> Google Play
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($appName ?? APP_NAME ?? 'Bookstore') ?>. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="footer-bottom-links">
                    <a href="<?= $baseUrl ?>books">Chính sách bảo mật</a>
                    <a href="<?= $baseUrl ?>books">Điều khoản sử dụng</a>
                    <a href="<?= $baseUrl ?>books">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Notification Script -->
<script>
// Toast Notification System
(function() {
    const toastContainer = document.getElementById('toast-container');
    
    function showToast(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            </div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Tạm tắt animations để kiểm tra giật
        toast.classList.add('show');
        
        // Auto remove after duration - đơn giản hóa
        setTimeout(() => {
            toast.remove();
        }, duration);
    }
    
    // Check for session flash messages
    <?php if (isset($_SESSION['cart_message'])): ?>
        showToast('<?= addslashes($_SESSION['cart_message']) ?>', 'success', 3000);
        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>
    
    // Expose globally for use in other scripts
    window.showToast = showToast;
    
    // Update cart count function with debounce
    let updateCartTimeout;
    function updateCartCount() {
        // Clear previous timeout
        if (updateCartTimeout) {
            clearTimeout(updateCartTimeout);
        }
        
        // Debounce - wait 1000ms before calling (tăng từ 300ms)
        updateCartTimeout = setTimeout(() => {
            fetch('<?= $baseUrl ?>cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count || 0;
                    }
                })
                .catch(err => console.error('Error updating cart count:', err));
        }, 1000); // Đổi từ 300ms thành 1000ms
    }
    
    // Update cart count on page load - TẮT TRONG TRANG CART
    // Chỉ update ở các trang khác, không phải trang cart
    if (!window.location.pathname.includes('cart')) {
        updateCartCount();
    }
    
    // Expose globally
    window.updateCartCount = updateCartCount;
})();

// User Dropdown Toggle Function
function toggleUserDropdown() {
    const dropdown = document.querySelector('.user-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.user-dropdown');
    const toggle = document.querySelector('.user-dropdown-toggle');
    
    if (dropdown && toggle && !dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
    }
    
    // Close navigation dropdowns when clicking outside
    const navDropdowns = document.querySelectorAll('.nav-menu .dropdown');
    navDropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
});

// Handle navigation dropdown on mobile (click to toggle)
function setupMobileDropdowns() {
    const navDropdowns = document.querySelectorAll('.dropdown');
    
    navDropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('a');
        
        // Xóa event listener cũ trước khi thêm mới
        if (link && link.hasAttribute('data-click-added')) {
            return; // Đã được thêm rồi
        }
        
        // On mobile, use click instead of hover
        if (window.innerWidth <= 768) {
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                });
                link.setAttribute('data-click-added', 'true');
            }
        }
    });
}

// Chỉ đăng ký resize event một lần
let resizeTimeout;
window.addEventListener('resize', function() {
    // Debounce resize event
    if (resizeTimeout) {
        clearTimeout(resizeTimeout);
    }
    
    resizeTimeout = setTimeout(() => {
        setupMobileDropdowns();
    }, 250);
});

// Setup khi load trang
document.addEventListener('DOMContentLoaded', function() {
    setupMobileDropdowns();
});
</script>

<!-- Toast Notification Styles -->
<style>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 16px 20px;
    min-width: 300px;
    max-width: 400px;
    display: flex;
    align-items: center;
    gap: 12px;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.toast-success .toast-icon {
    color: #10b981;
}

.toast-error .toast-icon {
    color: #ef4444;
}

.toast-info .toast-icon {
    color: #3b82f6;
}

.toast-message {
    flex: 1;
    font-size: 14px;
    line-height: 1.5;
    color: #1f2937;
}

.toast-close {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
    flex-shrink: 0;
}

.toast-close:hover {
    color: #1f2937;
}

@media (max-width: 768px) {
    .toast-container {
        top: 10px;
        right: 10px;
        left: 10px;
    }
    
    .toast {
        min-width: auto;
        max-width: none;
        width: 100%;
    }
}
</style>

</body>
</html>


