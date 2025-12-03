<?php /** @var array<string,int> $cart */ ?>
<?php /** @var App\Models\Book[] $items */ ?>
<?php /** @var string|null $message */ ?>
<div class="cart-page">
    <h1>Giỏ hàng của bạn</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="<?= $baseUrl ?>books" class="btn-continue-shopping">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Sách</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $book):
                    $cartItem = $cart[$book->id] ?? null;
                    if (is_array($cartItem) && isset($cartItem['qty'])) {
                        $qty = (int)$cartItem['qty'];
                    } else {
                        $qty = (int)($cartItem ?? 0);
                    }
                    $line = $qty * $book->getPrice();
                ?>
                    <tr>
                        <td class="cart-item-image">
                            <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>">
                                <img 
                                    src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                                    alt="<?= htmlspecialchars($book->title) ?>" 
                                    class="cart-book-cover"
                                    // TẠM TẮT onerror để tránh load placeholder vô hạn
                                    // onerror="this.src='<?= $baseUrl ?>assets/images/books/placeholder.jpg'"
                                />
                            </a>
                        </td>
                        <td class="cart-item-info">
                            <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>">
                                <strong><?= htmlspecialchars($book->title) ?></strong>
                            </a>
                            <p class="cart-item-author"><?= htmlspecialchars($book->author) ?></p>
                        </td>
                        <td class="cart-item-price">
                            <?php if ($book->hasDiscount()): ?>
                                <span class="original-price"><?= $book->getFormattedOriginalPrice() ?> đ</span>
                                <span class="discount-price"><?= $book->getFormattedPrice() ?> đ</span>
                                <span class="discount-percent">-<?= $book->getFormattedDiscount() ?>%</span>
                            <?php else: ?>
                                <?= $book->getFormattedPrice() ?> đ
                            <?php endif; ?>
                        </td>
                        <td class="cart-item-quantity">
                            <form method="post" action="<?= $baseUrl ?>cart/update" class="quantity-form" style="display: inline-flex; align-items: center; gap: 8px;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                                <button type="button" class="qty-btn qty-decrease" data-id="<?= htmlspecialchars($book->id) ?>" data-qty="<?= $qty ?>">-</button>
                                <input 
                                    type="number" 
                                    name="qty" 
                                    value="<?= $qty ?>" 
                                    min="1" 
                                    class="qty-input-number"
                                    data-id="<?= htmlspecialchars($book->id) ?>"
                                    style="width: 60px; text-align: center; padding: 6px; border: 1px solid #ddd; border-radius: 4px;"
                                />
                                <button type="button" class="qty-btn qty-increase" data-id="<?= htmlspecialchars($book->id) ?>" data-qty="<?= $qty ?>">+</button>
                            </form>
                        </td>
                        <td class="cart-item-total" data-id="<?= htmlspecialchars($book->id) ?>">
                            <strong><?= number_format($line, 0, ',', '.') ?> đ</strong>
                        </td>
                        <td class="cart-item-actions">
                            <button 
                                type="button" 
                                class="btn-delete" 
                                onclick="deleteCartItem('<?= htmlspecialchars($book->id) ?>')"
                                style="background: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Tổng tiền sản phẩm:</th>
                        <th class="cart-subtotal" style="font-size: 18px; color: #1f2937;"><?= number_format($total, 0, ',', '.') ?> đ</th>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Voucher Section -->
            <div class="voucher-section">
                <h3><i class="fas fa-ticket-alt"></i> Mã giảm giá / Voucher</h3>
                <div class="voucher-form-container">
                    <?php
                    $voucher = $_SESSION['voucher'] ?? null;
                    if ($voucher):
                        $discountAmount = 0;
                        if ($voucher['type'] === 'percent') {
                            $discountAmount = ($total * $voucher['discount']) / 100;
                        } else {
                            $discountAmount = min($voucher['discount'], $total);
                        }
                        $finalTotal = max(0, $total - $discountAmount);
                    ?>
                        <div class="voucher-applied">
                            <div class="voucher-info">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                <span><strong><?= htmlspecialchars($voucher['code']) ?></strong> - 
                                <?php if ($voucher['type'] === 'percent'): ?>
                                    Giảm <?= $voucher['discount'] ?>%
                                <?php else: ?>
                                    Giảm <?= number_format($voucher['discount'], 0, ',', '.') ?> đ
                                <?php endif; ?>
                                </span>
                            </div>
                            <button type="button" class="btn-remove-voucher" onclick="removeVoucher()">
                                <i class="fas fa-times"></i> Xóa
                            </button>
                        </div>
                    <?php else: ?>
                        <form class="voucher-form" id="voucher-form" onsubmit="applyVoucher(event)">
                            <input 
                                type="text" 
                                name="code" 
                                id="voucher-code"
                                placeholder="Nhập mã voucher" 
                                class="voucher-input"
                                required
                            />
                            <button type="submit" class="btn-apply-voucher">
                                <i class="fas fa-check"></i> Áp dụng
                            </button>
                        </form>
                        <div id="voucher-message" class="voucher-message"></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="summary-row">
                    <span>Tổng tiền sản phẩm:</span>
                    <span class="summary-value" id="subtotal"><?= number_format($total, 0, ',', '.') ?> đ</span>
                </div>
                <?php if ($voucher): ?>
                    <div class="summary-row discount-row">
                        <span>Giảm giá (<?= htmlspecialchars($voucher['code']) ?>):</span>
                        <span class="summary-value discount-value" id="discount">-<?= number_format($discountAmount, 0, ',', '.') ?> đ</span>
                    </div>
                <?php endif; ?>
                <div class="summary-row total-row">
                    <span><strong>Tổng cộng:</strong></span>
                    <span class="summary-value total-value" id="final-total" style="font-size: 24px; color: #2b6cb0;">
                        <?= number_format($finalTotal ?? $total, 0, ',', '.') ?> đ
                    </span>
                </div>
            </div>
            
            <div class="cart-actions">
                <a href="<?= $baseUrl ?>" class="btn-continue-shopping">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
                <button class="btn-checkout" onclick="handleCheckout()">
                    <i class="fas fa-credit-card"></i> Thanh toán
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Define global functions outside DOMContentLoaded so they're available for onclick attributes
// Handle checkout function - use Router-based path
window.handleCheckout = function() {
    window.location.href = '<?= $baseUrl ?>checkout';
};

// Delete cart item function - using query string routing
window.deleteCartItem = function(productId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        return;
    }
    
    const deleteUrl = '<?= $baseUrl ?>cart/delete';
    const params = new URLSearchParams();
    params.set('id', productId);
    
    fetch(deleteUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            if (window.showToast) {
                window.showToast(data.message || 'Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
            }
            setTimeout(() => {
                window.location.reload();
            }, 300);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(err => {
        console.error('Error deleting item:', err);
        if (window.showToast) {
            window.showToast('Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại.', 'error');
        } else {
            alert('Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại.');
        }
    });
};

document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity increase/decrease buttons
    document.querySelectorAll('.qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`input.qty-input-number[data-id="${id}"]`);
            const currentQty = parseInt(input.value) || 1;
            input.value = currentQty + 1;
            updateQuantity(id, input.value);
        });
    });

    document.querySelectorAll('.qty-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`input.qty-input-number[data-id="${id}"]`);
            const currentQty = parseInt(input.value) || 1;
            if (currentQty > 1) {
                input.value = currentQty - 1;
                updateQuantity(id, input.value);
            }
        });
    });

    // Handle direct input change
    document.querySelectorAll('.qty-input-number').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const qty = parseInt(this.value) || 1;
            if (qty < 1) {
                this.value = 1;
            }
            updateQuantity(id, this.value);
        });
    });

    function updateQuantity(id, qty) {
        const input = document.querySelector(`input.qty-input-number[data-id="${id}"]`);
        const form = input ? input.closest('form') : null;
        if (!form) return;
        
        const formData = new FormData(form);
        formData.set('qty', qty);
        
        // Use form action URL directly
        const updateUrl = form.action;
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                const lineCell = document.querySelector(`td.cart-item-total[data-id="${id}"] strong`);
                if (lineCell && data.lineTotal !== null && data.lineTotal !== undefined) {
                    lineCell.textContent = Number(data.lineTotal).toLocaleString('vi-VN') + ' đ';
                }
                const subtotalEl = document.getElementById('subtotal');
                if (subtotalEl && data.subtotal !== undefined) {
                    subtotalEl.textContent = Number(data.subtotal).toLocaleString('vi-VN') + ' đ';
                }
                const finalTotalEl = document.getElementById('final-total');
                if (finalTotalEl && data.finalTotal !== undefined) {
                    finalTotalEl.textContent = Number(data.finalTotal).toLocaleString('vi-VN') + ' đ';
                }
                const discountEl = document.getElementById('discount');
                if (discountEl && data.discount !== undefined && data.discount > 0) {
                    discountEl.textContent = '-' + Number(data.discount).toLocaleString('vi-VN') + ' đ';
                }
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
                if (window.showToast) {
                    window.showToast(data.message, 'success');
                }
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        }).catch(err => {
            console.error('Error updating quantity:', err);
            if (window.showToast) {
                window.showToast('Có lỗi xảy ra khi cập nhật số lượng. Vui lòng thử lại.', 'error');
            } else {
                alert('Có lỗi xảy ra khi cập nhật số lượng. Vui lòng thử lại.');
            }
        });
    }
    
    // Voucher functions
    function applyVoucher(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const messageDiv = document.getElementById('voucher-message');
        
        fetch('<?= $baseUrl ?>cart/voucher/apply', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (window.showToast) {
                    window.showToast(data.message, 'success');
                }
                // KHÔNG reload trang sau khi áp voucher để tránh giật
                // setTimeout(() => {
                //     window.location.reload();
                // }, 500);
            } else {
                messageDiv.textContent = data.message;
                messageDiv.className = 'voucher-message error';
                if (window.showToast) {
                    window.showToast(data.message, 'error');
                }
            }
        })
        .catch(err => {
            console.error('Error applying voucher:', err);
            messageDiv.textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            messageDiv.className = 'voucher-message error';
        });
    }
    
    function removeVoucher() {
        fetch('<?= $baseUrl ?>cart/voucher/remove', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (window.showToast) {
                    window.showToast(data.message, 'success');
                }
                // KHÔNG reload trang sau khi xóa voucher để tránh giật
                // setTimeout(() => {
                //     window.location.reload();
                // }, 500);
            }
        })
        .catch(err => {
            console.error('Error removing voucher:', err);
        });
    }
    
});
</script>

<style>
.cart-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.cart-table th {
    background: #2b6cb0;
    color: white;
    padding: 16px;
    text-align: left;
    font-weight: 600;
}

.cart-table td {
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

.cart-table tbody tr:hover {
    background: #f8fafc;
}

.cart-item-image img {
    width: 80px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}

.cart-item-title strong {
    display: block;
    margin-bottom: 4px;
    color: #1f2937;
}

.cart-item-author {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.cart-item-price {
    font-weight: 600;
    color: #2b6cb0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cart-item-price .original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

.cart-item-price .discount-price {
    font-size: 14px;
    color: #e74c3c;
    font-weight: 700;
}

.cart-item-price .discount-percent {
    font-size: 11px;
    color: #fff;
    background: #e74c3c;
    padding: 2px 6px;
    border-radius: 10px;
    display: inline-block;
    font-weight: 600;
    align-self: flex-start;
}

.qty-btn {
    background: #2b6cb0;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.qty-btn:hover {
    background: #1e4a72;
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 16px;
}

.btn-continue-shopping {
    background: #6b7280;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-continue-shopping:hover {
    background: #4b5563;
}

.btn-checkout {
    background: #10b981;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-checkout:hover {
    background: #059669;
}

.empty-cart {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.voucher-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.voucher-section h3 {
    margin: 0 0 16px 0;
    color: #2b6cb0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.voucher-form {
    display: flex;
    gap: 10px;
}

.voucher-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s;
}

.voucher-input:focus {
    outline: none;
    border-color: #2b6cb0;
}

.btn-apply-voucher {
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-apply-voucher:hover {
    background: #1e4a72;
}

.voucher-applied {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #f0fdf4;
    border: 2px solid #10b981;
    border-radius: 6px;
}

.voucher-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #166534;
}

.btn-remove-voucher {
    background: #ef4444;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}

.btn-remove-voucher:hover {
    background: #dc2626;
}

.voucher-message {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
    font-size: 14px;
}

.voucher-message.error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.order-summary {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-summary h3 {
    margin: 0 0 16px 0;
    color: #2b6cb0;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 12px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total-row {
    border-top: 2px solid #e2e8f0;
    margin-top: 8px;
    padding-top: 16px;
}

.summary-value {
    font-weight: 600;
    color: #1f2937;
}

.discount-value {
    color: #10b981;
}

.total-value {
    font-size: 24px;
    color: #2b6cb0;
}

@media (max-width: 768px) {
    .cart-table {
        font-size: 14px;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 8px;
    }
    
    .cart-item-image img {
        width: 60px;
        height: 80px;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .btn-continue-shopping,
    .btn-checkout {
        width: 100%;
        justify-content: center;
    }
    
    .voucher-form {
        flex-direction: column;
    }
    
    .voucher-applied {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
</style>


