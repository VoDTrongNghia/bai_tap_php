<div class="container my-5">
    <div class="alert alert-danger">
        <h1>Lỗi hệ thống</h1>
        <p><?= htmlspecialchars($message ?? 'Đã xảy ra lỗi không xác định.') ?></p>
        <a href="/bookstore/public/home" class="btn btn-primary">Về trang chủ</a>
    </div>
</div>
