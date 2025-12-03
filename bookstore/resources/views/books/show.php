<?php /** @var App\Models\Book $book */ ?>
<article class="book-detail-page">
    <div class="book-detail-container">
        <div class="book-detail-media">
            <img 
                src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                alt="<?= htmlspecialchars($book->title) ?>" 
                class="book-detail-image"
                // TẠM TẮT onerror để tránh load placeholder vô hạn
            // onerror="this.src='<?= $baseUrl ?>assets/images/books/placeholder.jpg'"
            />
        </div>
        <div class="book-detail-content">
            <h1 class="book-detail-title"><?= htmlspecialchars($book->title) ?></h1>
            <p class="book-detail-author">
                <i class="fas fa-user"></i> Tác giả: <strong><?= htmlspecialchars($book->author) ?></strong>
            </p>
            <div class="book-detail-description">
                <h3>Mô tả sách</h3>
                <p><?= nl2br(htmlspecialchars($book->description ?: 'Chưa có mô tả cho cuốn sách này.')) ?></p>
            </div>
            <div class="book-detail-price">
                <span class="price-label">Giá bán:</span>
                <span class="price-value"><?= number_format($book->price, 0, ',', '.') ?> đ</span>
            </div>
            <form method="post" action="<?= $baseUrl ?>cart/add" class="book-detail-form" onsubmit="addToCart(event, this, '<?= htmlspecialchars($book->id) ?>')">
                <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                <div class="quantity-selector">
                    <label for="qty">Số lượng:</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn qty-decrease" onclick="decreaseQty()">-</button>
                        <input 
                            type="number" 
                            id="qty" 
                            name="qty" 
                            value="1" 
                            min="1" 
                            class="qty-input-number"
                        />
                        <button type="button" class="qty-btn qty-increase" onclick="increaseQty()">+</button>
                    </div>
                </div>
                <button type="submit" class="btn-add-cart-large">
                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                </button>
            </form>
            <script>
            // Quantity controls
            function decreaseQty() {
                const input = document.getElementById('qty');
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            }
            
            function increaseQty() {
                const input = document.getElementById('qty');
                const currentValue = parseInt(input.value) || 1;
                input.value = currentValue + 1;
            }
            
            // Add to cart function
            function addToCart(event, form, bookId) {
                event.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, assume success
                        return { success: true, message: 'Đã thêm vào giỏ hàng!' };
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        if (window.updateCartCount) {
                            window.updateCartCount();
                        }
                        // Show toast notification
                        if (window.showToast) {
                            window.showToast(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                        } else {
                            alert(data.message || 'Đã thêm vào giỏ hàng!');
                        }
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(err => {
                    console.error('Error adding to cart:', err);
                    let errorMessage = 'Có lỗi xảy ra khi thêm vào giỏ hàng. Vui lòng thử lại.';
                    
                    // Try to extract more detailed error message
                    if (err.message) {
                        if (err.message.includes('Unexpected token')) {
                            errorMessage = 'Lỗi định dạng dữ liệu từ server. Vui lòng thử lại.';
                        } else if (err.message.includes('HTTP error')) {
                            errorMessage = 'Lỗi kết nối đến server. Vui lòng thử lại.';
                        } else {
                            errorMessage = err.message;
                        }
                    }
                    
                    if (window.showToast) {
                        window.showToast(errorMessage, 'error');
                    } else {
                        alert(errorMessage);
                    }
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
            </script>
        </div>
    </div>
</article>


