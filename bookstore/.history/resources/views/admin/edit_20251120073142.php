<?php /** @var App\Models\Book $book */ ?>
<?php
// Authentication is handled by AdminController via AdminHelper::requireAdmin()
// No need for manual session checks here
?>
<div class="admin-page">
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Sửa sách</h2>
            <a href="<?= $baseUrl ?>admin?page=products" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

        <form method="POST" class="admin-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Tên sách <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-input" required 
                       value="<?= htmlspecialchars($_POST['title'] ?? $book->title) ?>">
            </div>

            <div class="form-group">
                <label for="author" class="form-label">Tác giả <span class="required">*</span></label>
                <input type="text" id="author" name="author" class="form-input" required 
                       value="<?= htmlspecialchars($_POST['author'] ?? $book->author) ?>">
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Danh mục</label>
                <input type="text" id="category" name="category" class="form-input" 
                       placeholder="Ví dụ: Văn học, Kinh tế, Kỹ năng..."
                       value="<?= htmlspecialchars($_POST['category'] ?? $book->category ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Mô tả</label>
                <textarea id="description" name="description" class="form-textarea" rows="5"><?= htmlspecialchars($_POST['description'] ?? $book->description) ?></textarea>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Giá (VNĐ) <span class="required">*</span></label>
                <input type="number" id="price" name="price" class="form-input" step="1000" min="0" required 
                       value="<?= htmlspecialchars($_POST['price'] ?? $book->price) ?>">
            </div>

            <div class="form-group">
                <label for="cover_image" class="form-label">Hình ảnh sách</label>
                <input type="file" id="cover_image" name="cover_image" class="form-input" accept="image/*">
                <?php if ($book->coverImage): ?>
                    <div class="current-image" style="margin-top: 12px;">
                        <p style="margin-bottom: 8px; font-weight: 600;">Hình ảnh hiện tại:</p>
                        <img src="<?= htmlspecialchars($book->imageUrl ?? $book->getImageUrl()) ?>" 
                             alt="<?= htmlspecialchars($book->title) ?>" 
                             class="preview-image" style="max-width: 200px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    </div>
                <?php endif; ?>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 13px;">
                    Chọn ảnh mới (JPG, PNG, GIF, tối đa ~2MB) nếu muốn thay đổi. Ảnh sẽ được lưu vào <code>public/assets/images/books/</code>
                </small>
            </div>

            <div class="form-actions" style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="<?= $baseUrl ?>admin?page=products" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>

