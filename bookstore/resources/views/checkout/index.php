<?php /** @var array<string,int> $cart */ ?>
<?php /** @var App\Models\Book[] $items */ ?>
<?php /** @var float $total */ ?>
<?php /** @var float $discountAmount */ ?>
<?php /** @var float $finalTotal */ ?>
<?php /** @var array|null $voucher */ ?>
<?php /** @var array $user */ ?>
<?php /** @var bool $hasAddress */ ?>
<?php /** @var bool $isLoggedIn */ ?>
<div class="checkout-page">
    <h1>Thanh toán</h1>
    
    <?php if (!$isLoggedIn): ?>
        <div class="checkout-notice">
            <i class="fas fa-info-circle"></i>
            <span>Bạn đang thanh toán với tư cách khách vãng lai. Vui lòng điền đầy đủ thông tin bên dưới.</span>
        </div>
    <?php endif; ?>
    
    <div class="checkout-container">
        <div class="checkout-main">
            <!-- Shipping Information -->
            <div class="checkout-section">
                <h2><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h2>
                <form id="checkout-form" class="checkout-form">
                    <div class="form-group">
                        <label for="name">Họ và tên *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= htmlspecialchars($user['ho_ten'] ?? $user['name'] ?? '') ?>" 
                            required
                            class="form-input"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= htmlspecialchars($user['so_dien_thoai'] ?? $user['phone'] ?? '') ?>" 
                            required
                            class="form-input"
                        />
                    </div>
                    
                    <?php if (!$isLoggedIn): ?>
                    <div class="form-group">
                        <label for="email">Email (tùy chọn)</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                            class="form-input"
                            placeholder="Email để nhận thông báo đơn hàng"
                        />
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="address">Địa chỉ giao hàng *</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3" 
                            required
                            class="form-input"
                        ><?= htmlspecialchars($user['dia_chi'] ?? $user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="note">Ghi chú (tùy chọn)</label>
                        <textarea 
                            id="note" 
                            name="note" 
                            rows="2" 
                            class="form-input"
                            placeholder="Ghi chú thêm cho đơn hàng..."
                        ></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Payment Method -->
            <div class="checkout-section">
                <h2><i class="fas fa-credit-card"></i> Phương thức thanh toán</h2>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked />
                        <span class="payment-label">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                                <small>Thanh toán bằng tiền mặt khi nhận hàng</small>
                            </div>
                        </span>
                    </label>
                    
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank_transfer" />
                        <span class="payment-label">
                            <i class="fas fa-university"></i>
                            <div>
                                <strong>Chuyển khoản ngân hàng</strong>
                                <small>Chuyển khoản qua tài khoản ngân hàng</small>
                            </div>
                        </span>
                    </label>
                    
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="vnpay" />
                        <span class="payment-label">
                            <i class="fas fa-mobile-alt"></i>
                            <div>
                                <strong>VNPay</strong>
                                <small>Thanh toán qua VNPay</small>
                            </div>
                        </span>
                    </label>
                </div>
                
                <!-- Payment Info (shown when bank_transfer or vnpay is selected) -->
                <div id="payment-info" class="payment-info" style="display: none;">
                    <div class="payment-details">
                        <h3><i class="fas fa-qrcode"></i> Thông tin thanh toán</h3>
                        
                        <div id="bank-transfer-info" style="display: none;">
                            <div class="bank-account">
                                <p><strong>Chuyển khoản đến tài khoản:</strong></p>
                                <div class="account-details">
                                    <p><strong>Ngân hàng:</strong> Vietcombank</p>
                                    <p><strong>Số tài khoản:</strong> <span class="account-number">1234567890</span> 
                                       <button type="button" class="btn-copy" onclick="copyToClipboard('1234567890')">
                                           <i class="fas fa-copy"></i> Copy
                                       </button>
                                    </p>
                                    <p><strong>Chủ tài khoản:</strong> CÔNG TY TNHH BOOKSTORE</p>
                                    <p><strong>Nội dung chuyển khoản:</strong> <span class="order-code-preview">ORD<?= date('Ymd') ?>XXXX</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="vnpay-info" style="display: none;">
                            <div class="qr-code-section">
                                <p><strong>Quét mã QR để thanh toán:</strong></p>
                                <div class="qr-code-placeholder">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=VNPAY_<?= date('YmdHis') ?>" 
                                         alt="VNPay QR Code" 
                                         style="width: 200px; height: 200px; border: 1px solid #ddd; padding: 10px; background: white;">
                                </div>
                                <p class="qr-note">Hoặc chuyển khoản đến:</p>
                                <div class="account-details">
                                    <p><strong>Số tài khoản VNPay:</strong> <span class="account-number">9876543210</span>
                                       <button type="button" class="btn-copy" onclick="copyToClipboard('9876543210')">
                                           <i class="fas fa-copy"></i> Copy
                                       </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="checkout-sidebar">
            <div class="order-summary-box">
                <h3>Tóm tắt đơn hàng</h3>
                
                <div class="order-items">
                    <?php foreach ($items as $book): 
                        $qty = (int)($cart[$book->id] ?? 0);
                        $line = $qty * $book->price;
                    ?>
                        <div class="order-item">
                            <div class="item-info">
                                <strong><?= htmlspecialchars($book->title) ?></strong>
                                <span>x<?= $qty ?></span>
                            </div>
                            <div class="item-price"><?= number_format($line, 0, ',', '.') ?> đ</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Tạm tính:</span>
                        <span><?= number_format($total, 0, ',', '.') ?> đ</span>
                    </div>
                    <?php if ($voucher): ?>
                        <div class="total-row discount">
                            <span>Giảm giá:</span>
                            <span>-<?= number_format($discountAmount, 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endif; ?>
                    <div class="total-row final">
                        <span><strong>Tổng cộng:</strong></span>
                        <span><strong><?= number_format($finalTotal, 0, ',', '.') ?> đ</strong></span>
                    </div>
                </div>
                
                <button type="button" class="btn-place-order" onclick="placeOrder()">
                    <i class="fas fa-check"></i> Đặt hàng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide payment info based on selected method
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentInfo = document.getElementById('payment-info');
    const bankTransferInfo = document.getElementById('bank-transfer-info');
    const vnpayInfo = document.getElementById('vnpay-info');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'bank_transfer') {
                paymentInfo.style.display = 'block';
                bankTransferInfo.style.display = 'block';
                vnpayInfo.style.display = 'none';
            } else if (this.value === 'vnpay') {
                paymentInfo.style.display = 'block';
                bankTransferInfo.style.display = 'none';
                vnpayInfo.style.display = 'block';
            } else {
                paymentInfo.style.display = 'none';
            }
        });
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        if (window.showToast) {
            window.showToast('Đã sao chép số tài khoản!', 'success');
        } else {
            alert('Đã sao chép số tài khoản!');
        }
    }).catch(function(err) {
        console.error('Failed to copy:', err);
    });
}

function placeOrder() {
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    
    // Get payment method
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        if (window.showToast) {
            window.showToast('Vui lòng chọn phương thức thanh toán', 'error');
        } else {
            alert('Vui lòng chọn phương thức thanh toán');
        }
        return;
    }
    formData.append('payment_method', paymentMethod.value);
    
    // Get note
    const note = document.getElementById('note');
    if (note && note.value) {
        formData.append('note', note.value);
    }
    
    // Get email if exists (for guest checkout)
    const emailInput = document.getElementById('email');
    if (emailInput && emailInput.value) {
        formData.append('email', emailInput.value);
    }
    
    const submitBtn = document.querySelector('.btn-place-order');
    const originalText = submitBtn.innerHTML;
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    
    fetch('<?= $baseUrl ?>checkout/process', {
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
            setTimeout(() => {
                const redirectUrl = data.redirect || '/';
                window.location.href = '<?= $baseUrl ?>' + redirectUrl.replace(/^\//, '');
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        console.error('Error placing order:', err);
        if (window.showToast) {
            window.showToast(err.message || 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.', 'error');
        } else {
            alert(err.message || 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<style>
.checkout-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.checkout-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
    margin-top: 20px;
}

.checkout-section {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.checkout-section h2 {
    margin: 0 0 20px 0;
    color: #2b6cb0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
}

.checkout-form .form-group {
    margin-bottom: 20px;
}

.checkout-form label {
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

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: center;
    padding: 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-option:hover {
    border-color: #2b6cb0;
    background: #f0f9ff;
}

.payment-option input[type="radio"] {
    margin-right: 12px;
}

.payment-option input[type="radio"]:checked + .payment-label {
    color: #2b6cb0;
}

.payment-label {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.payment-label i {
    font-size: 24px;
    color: #2b6cb0;
}

.payment-label div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.payment-label strong {
    font-size: 16px;
}

.payment-label small {
    color: #6b7280;
    font-size: 14px;
}

.checkout-sidebar {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.order-summary-box {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-summary-box h3 {
    margin: 0 0 20px 0;
    color: #2b6cb0;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 12px;
}

.order-items {
    margin-bottom: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.item-info strong {
    font-size: 14px;
    color: #1f2937;
}

.item-info span {
    font-size: 12px;
    color: #6b7280;
}

.item-price {
    font-weight: 600;
    color: #2b6cb0;
}

.order-totals {
    border-top: 2px solid #e2e8f0;
    padding-top: 16px;
    margin-bottom: 20px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #1f2937;
}

.total-row.discount {
    color: #10b981;
}

.total-row.final {
    border-top: 2px solid #e2e8f0;
    margin-top: 8px;
    padding-top: 16px;
    font-size: 18px;
}

.btn-place-order {
    width: 100%;
    background: #10b981;
    color: white;
    border: none;
    padding: 16px;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-place-order:hover:not(:disabled) {
    background: #059669;
}

.btn-place-order:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.checkout-notice {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #92400e;
}

.checkout-notice i {
    font-size: 20px;
}

.payment-info {
    margin-top: 20px;
    padding: 20px;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
}

.payment-details h3 {
    margin: 0 0 16px 0;
    color: #1e40af;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bank-account,
.qr-code-section {
    margin-top: 16px;
}

.account-details {
    background: white;
    padding: 16px;
    border-radius: 6px;
    margin-top: 12px;
}

.account-details p {
    margin: 8px 0;
    color: #1f2937;
}

.account-number {
    font-family: monospace;
    font-size: 18px;
    font-weight: 700;
    color: #2b6cb0;
    margin: 0 8px;
}

.btn-copy {
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 4px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background 0.2s;
}

.btn-copy:hover {
    background: #1e4a72;
}

.qr-code-placeholder {
    text-align: center;
    margin: 16px 0;
}

.qr-note {
    margin: 16px 0 8px 0;
    color: #6b7280;
}

.order-code-preview {
    font-family: monospace;
    font-weight: 600;
    color: #2b6cb0;
}

@media (max-width: 968px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
    
    .checkout-sidebar {
        position: static;
    }
}
</style>

