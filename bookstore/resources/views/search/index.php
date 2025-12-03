<?php /** @var App\Models\Book[] $books */ ?>
<?php /** @var string $keyword */ ?>
<?php /** @var int $count */ ?>
<div class="search-page">
    <h1>Kết quả tìm kiếm</h1>
    
    <?php if (!empty($keyword)): ?>
        <div class="search-info">
            <p>
                Tìm thấy <strong><?= $count ?></strong> kết quả cho từ khóa "<strong><?= htmlspecialchars($keyword) ?></strong>"
            </p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($keyword)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <p>Vui lòng nhập từ khóa để tìm kiếm.</p>
        </div>
    <?php elseif (empty($books)): ?>
        <div class="alert alert-info">
            <i class="fas fa-search"></i>
            <p>Không tìm thấy sách nào phù hợp với từ khóa "<strong><?= htmlspecialchars($keyword) ?></strong>"</p>
            <a href="<?= $baseUrl ?>books" class="btn">Xem tất cả sách</a>
        </div>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <a href="<?= $baseUrl ?>books/<?= urlencode($book->id) ?>" class="book-link">
                        <div class="book-image-wrapper">
                            <img 
                                src="<?= htmlspecialchars($book->getImageUrl()) ?>" 
                                alt="<?= htmlspecialchars($book->title) ?>" 
                                class="book-cover"
                                // TẠM TẮT onerror để tránh load placeholder vô hạn
                            // onerror="this.src='<?= $baseUrl ?>assets/images/books/placeholder.jpg'"
                            />
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?= htmlspecialchars($book->title) ?></h3>
                            <p class="book-author"><?= htmlspecialchars($book->author) ?></p>
                            <p class="book-price"><?= number_format($book->price, 0, ',', '.') ?> đ</p>
                        </div>
                    </a>
                    <form method="post" action="<?= $baseUrl ?>cart/add" class="add-to-cart-form" onsubmit="addToCart(event, this, '<?= htmlspecialchars($book->id) ?>')">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>" />
                        <input type="hidden" name="qty" value="1" />
                        <button type="submit" class="btn-add-cart">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Add to cart with AJAX
function addToCart(event, form, bookId) {
    event.preventDefault();
    
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
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
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            if (window.showToast) {
                window.showToast(data.message, 'success');
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
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<style>
.search-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.search-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 20px;
}

.search-info p {
    margin: 0;
    color: #1e40af;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.book-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.book-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.book-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.book-image-wrapper {
    width: 100%;
    height: 280px;
    overflow: hidden;
    background: #f3f4f6;
}

.book-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-info {
    padding: 16px;
}

.book-title {
    margin: 0 0 8px 0;
    font-size: 16px;
    line-height: 1.4;
    color: #1f2937;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-author {
    margin: 0 0 8px 0;
    font-size: 14px;
    color: #6b7280;
}

.book-price {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2b6cb0;
}

.add-to-cart-form {
    padding: 0 16px 16px;
}

.btn-add-cart {
    width: 100%;
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-add-cart:hover:not(:disabled) {
    background: #1e4a72;
}

.btn-add-cart:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

