<?php
require_once __DIR__ . '/config.php';

// Bắt đầu session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mock admin session để test
$_SESSION['user'] = [
    'id' => 1,
    'ten_dang_nhap' => 'admin',
    'vai_tro' => 'admin'
];
$_SESSION['vai_tro'] = 'admin';

echo "<h2>Test Admin Routes</h2>";

// Test các route admin
$adminRoutes = [
    'admin' => 'Dashboard',
    'admin?page=dashboard' => 'Dashboard (page)',
    'admin?page=products' => 'Quản lý sản phẩm',
    'admin?page=orders' => 'Quản lý đơn hàng',
    'admin?page=users' => 'Quản lý người dùng',
    'admin?page=vouchers' => 'Quản lý Voucher',
    'admin?page=categories' => 'Quản lý Danh mục',
    'admin?page=statistics' => 'Thống kê doanh thu'
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Route</th><th>Description</th><th>Test</th></tr>";

foreach ($adminRoutes as $route => $description) {
    $testUrl = BASE_URL . $route;
    echo "<tr>";
    echo "<td><a href='$testUrl' target='_blank'>$route</a></td>";
    echo "<td>$description</td>";
    echo "<td><a href='$testUrl' target='_blank' class='btn-test'>Test</a></td>";
    echo "</tr>";
}

echo "</table>";

echo "<style>";
echo ".btn-test { background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }";
echo ".btn-test:hover { background: #0056b3; }";
echo "</style>";

echo "<h3>Controllers Check</h3>";

// Kiểm tra các controller methods
$controllerFile = __DIR__ . '/app/Controllers/AdminController.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    $methods = [
        'index' => 'Dashboard',
        'books' => 'Quản lý sản phẩm',
        'orders' => 'Quản lý đơn hàng',
        'users' => 'Quản lý người dùng',
        'vouchers' => 'Quản lý Voucher',
        'categories' => 'Quản lý Danh mục',
        'statistics' => 'Thống kê doanh thu',
        'create' => 'Tạo sản phẩm',
        'saveBook' => 'Lưu sản phẩm',
        'edit' => 'Sửa sản phẩm',
        'update' => 'Cập nhật sản phẩm',
        'delete' => 'Xóa sản phẩm',
        'categoryForm' => 'Form danh mục',
        'saveCategory' => 'Lưu danh mục',
        'deleteCategory' => 'Xóa danh mục',
        'voucherForm' => 'Form voucher',
        'saveVoucher' => 'Lưu voucher',
        'deleteVoucher' => 'Xóa voucher',
        'getVoucher' => 'Lấy voucher',
        'orderDetail' => 'Chi tiết đơn hàng',
        'updateOrderStatus' => 'Cập nhật trạng thái đơn hàng',
        'cancelOrder' => 'Hủy đơn hàng',
        'deleteOrder' => 'Xóa đơn hàng',
        'bulkDeleteOrders' => 'Xóa nhiều đơn hàng',
        'userForm' => 'Form người dùng',
        'saveUser' => 'Lưu người dùng',
        'deleteUser' => 'Xóa người dùng'
    ];
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Method</th><th>Description</th><th>Status</th></tr>";
    
    foreach ($methods as $method => $description) {
        echo "<tr>";
        echo "<td>$method()</td>";
        echo "<td>$description</td>";
        echo "<td>" . (method_exists('App\Controllers\AdminController', $method) ? '✅ OK' : '❌ Missing') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p style='color: red;'>AdminController.php not found!</p>";
}

echo "<h3>Views Check</h3>";

// Kiểm tra các file view
$views = [
    'admin/dashboard_full.php' => 'Dashboard',
    'admin/books_full.php' => 'Quản lý sản phẩm',
    'admin/orders_full.php' => 'Quản lý đơn hàng',
    'admin/users_full.php' => 'Quản lý người dùng',
    'admin/vouchers.php' => 'Quản lý Voucher',
    'admin/categories_full.php' => 'Quản lý Danh mục',
    'admin/statistics_full.php' => 'Thống kê doanh thu',
    'layout/admin_header.php' => 'Admin Header',
    'layout/admin_footer.php' => 'Admin Footer'
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>View File</th><th>Description</th><th>Status</th></tr>";

foreach ($views as $view => $description) {
    $viewPath = __DIR__ . '/resources/views/' . $view;
    echo "<tr>";
    echo "<td>$view</td>";
    echo "<td>$description</td>";
    echo "<td>" . (file_exists($viewPath) ? '✅ OK' : '❌ Missing') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>BASE_URL Check</h3>";
echo "<p>BASE_URL: " . BASE_URL . "</p>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "</p>";

echo "<h3>Session Check</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<p><a href='" . BASE_URL . "logout'>Logout</a></p>";
?>
