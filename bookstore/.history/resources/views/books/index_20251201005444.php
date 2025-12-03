<?php /** @var App\Models\Book[] $books */ ?>
<?php /** @var string[] $categories */ ?>
<?php /** @var string $currentCategory */ ?>
<?php /** @var string $currentKeyword */ ?>
<?php /** @var string $currentType */ ?>
<div class="books-page">
    <h1><?= htmlspecialchars($title ?? 'Danh sách sách') ?></h1>
    
    <!-- Search and Filter Section -->
    <div class="books-filter-section">
        <form method="get" action="<?= $baseUrl ?>index.php?page=books" class="books-filter-form">
            <!-- Hidden field to preserve type -->
            <?php if (!empty($currentType)): ?>
                <input type="hidden" name="type" value="<?= htmlspecialchars($currentType) ?>">
            <?php endif; ?>
            
            <div class="filter-group">
                <label for="search">
                    <i class="fas fa-search"></i> Tìm kiếm:
                </label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="<?= htmlspecialchars($currentKeyword ?? '') ?>" 
                    placeholder="Nhập tên sách..."
                    class="search-input"
                />
            </div>
            <div class="filter-group">
                <label for="category">
                    <i class="fas fa-filter"></i> Danh mục:
                </label>
                <select id="category" name="category" class="category-select">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= ($currentCategory ?? '') === $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <a href="<?= $baseUrl ?>index.php?page=books<?= !empty($currentType) ? '?type=' . htmlspecialchars($currentType) : '' ?>" class="btn-reset">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>
    
    <?php if (!empty($currentKeyword) || !empty($currentCategory)): ?>
        <div class="filter-results-info">
            <p>
                <i class="fas fa-info-circle"></i>
                <?php if (!empty($currentKeyword) && !empty($currentCategory)): ?>
                    Tìm thấy <strong><?= count($books) ?></strong> sách với từ khóa "<strong><?= htmlspecialchars($currentKeyword) ?></strong>" trong danh mục "<strong><?= htmlspecialchars($currentCategory) ?></strong>"
                <?php elseif (!empty($currentKeyword)): ?>
                    Tìm thấy <strong><?= count($books) ?></strong> sách với từ khóa "<strong><?= htmlspecialchars($currentKeyword) ?></strong>"
                <?php else: ?>
                    Tìm thấy <strong><?= count($books) ?></strong> sách trong danh mục "<strong><?= htmlspecialchars($currentCategory) ?></strong>"
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($books)): ?>
        <div class="alert alert-info" style="padding: 20px; margin: 20px 0; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px;">
            <?php if (!empty($currentKeyword) || !empty($currentCategory)): ?>
                <h3>Không tìm thấy sách</h3>
                <p>Không có sách nào phù hợp với bộ lọc của bạn.</p>
                <a href="<?= $baseUrl ?>books" class="btn">Xem tất cả sách</a>
            <?php else: ?>
                <h3>Chưa có sách trong database</h3>
                <p>Vui lòng:</p>
                <ol>
                    <li>Chạy file SQL: <code>database/create_books_table.sql</code> để tạo bảng</li>
                    <li>Chạy file SQL: <code>database/insert_sample_books.sql</code> để thêm dữ liệu mẫu</li>
                    <li>Hoặc truy cập <a href="<?= $baseUrl ?>admin/create">trang admin</a> để thêm sách mới</li>
                </ol>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="books-grid" id="debug-grid-books">
        <!-- DEBUG: Số lượng sách: <?= count($books) ?> -->
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
        <?php if (empty($books)): ?>
            <div class="no-books">
                <p>Chưa có sách trong danh mục.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- DEBUG SCRIPT -->
<script>
(function() {
    function debugBooksGrid() {
        console.log('=== DEBUG BOOKS GRID ===');
        const grid = document.getElementById('debug-grid-books');
        if (grid) {
            const styles = window.getComputedStyle(grid);
            console.log('Grid Info:', {
                display: styles.display,
                gridTemplateColumns: styles.gridTemplateColumns,
                gap: styles.gap,
                width: styles.width,
                children: grid.children.length
            });
            
            if (styles.display !== 'grid') {
                console.error('❌ Grid không hoạt động! Display:', styles.display);
                grid.style.display = 'grid';
                grid.style.gridTemplateColumns = 'repeat(4, 1fr)';
                grid.style.gap = '25px';
            } else {
                console.log('✅ Grid hoạt động đúng');
            }
        } else {
            console.error('❌ Không tìm thấy grid element');
        }
        
        const homeCss = Array.from(document.querySelectorAll('link[rel="stylesheet"]')).find(link => 
            link.href.includes('home.css')
        );
        console.log('home.css:', homeCss ? homeCss.href : '❌ NOT FOUND');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', debugBooksGrid);
    } else {
        debugBooksGrid();
    }
})();

// Add to cart with AJAX
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
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update cart count
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            // Show toast notification
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
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<style>
.books-filter-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.books-filter-form {
    display: flex;
    gap: 16px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1f2937;
}

.filter-group label i {
    margin-right: 6px;
    color: #2b6cb0;
}

.search-input,
.category-select {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s;
}

.search-input:focus,
.category-select:focus {
    outline: none;
    border-color: #2b6cb0;
    box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
}

.btn-filter {
    background: #2b6cb0;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-filter:hover {
    background: #1e4a72;
}

.btn-reset {
    background: #6b7280;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-reset:hover {
    background: #4b5563;
}

.filter-results-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 20px;
}

.filter-results-info p {
    margin: 0;
    color: #1e40af;
}

.filter-results-info i {
    margin-right: 8px;
}

@media (max-width: 768px) {
    .books-filter-form {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-actions {
        width: 100%;
    }
    
    .btn-filter,
    .btn-reset {
        flex: 1;
        justify-content: center;
    }
}
</style>


