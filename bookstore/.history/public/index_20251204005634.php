<?php

declare(strict_types=1);

// Tắt hiển thị lỗi trong production để tránh HTML output trước JSON
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// Log lỗi thay vì hiển thị trực tiếp
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Định nghĩa hằng số cho thư mục gốc của ứng dụng
define('ROOT_DIR', dirname(__DIR__));

// Load autoloader (nếu bạn đang sử dụng Composer)
// require ROOT_DIR . '/vendor/autoload.php';

// Load file bootstrap của ứng dụng
require_once ROOT_DIR . '/app/bootstrap.php';

use App\Router;

// Khởi tạo router
$router = new Router();

// Định nghĩa các routes
$router->get('/', ['App\Controllers\HomeController', 'index']);
$router->get('/books', ['App\Controllers\BooksController', 'index']);
$router->get('/books/{id}', ['App\Controllers\BooksController', 'show']); // Ví dụ route có tham số

$router->get('/login', ['App\Controllers\AuthController', 'login']);
$router->post('/login', ['App\Controllers\AuthController', 'login']);
$router->get('/register', ['App\Controllers\AuthController', 'register']);
$router->post('/register', ['App\Controllers\AuthController', 'register']);
$router->get('/logout', ['App\Controllers\AuthController', 'logout']); // ✅ đã sửa

$router->get('/cart', ['App\Controllers\CartController', 'index']);
$router->post('/cart/add', ['App\Controllers\CartController', 'add']);
$router->post('/cart/update', ['App\Controllers\CartController', 'update']);
$router->post('/cart/delete', ['App\Controllers\CartController', 'delete']);
$router->get('/cart/delete', ['App\Controllers\CartController', 'delete']);
$router->get('/cart/count', ['App\Controllers\CartController', 'count']);
$router->post('/cart/voucher/apply', ['App\Controllers\CartController', 'applyVoucher']);
$router->post('/cart/voucher/remove', ['App\Controllers\CartController', 'removeVoucher']);

// Checkout routes
$router->get('/checkout', ['App\Controllers\CheckoutController', 'index']);
$router->post('/checkout/process', ['App\Controllers\CheckoutController', 'process']);
$router->get('/checkout/success', ['App\Controllers\CheckoutController', 'success']);

// Search routes
$router->get('/search', ['App\Controllers\SearchController', 'index']);

// Account routes
$router->get('/account', ['App\Controllers\AccountController', 'index']);
$router->post('/account/profile', ['App\Controllers\AccountController', 'updateProfile']);
$router->post('/account/address', ['App\Controllers\AccountController', 'updateAddress']);
$router->post('/account/password', ['App\Controllers\AccountController', 'changePassword']);

// Order routes
$router->get('/orders', ['App\Controllers\OrderController', 'history']);
$router->get('/orders/detail', ['App\Controllers\OrderController', 'detail']);

// Admin routes
$router->get('/admin', ['App\Controllers\AdminController', 'index']);
$router->get('/admin/books', ['App\Controllers\AdminController', 'books']);
$router->get('/admin/books/create', ['App\Controllers\AdminController', 'create']);
$router->post('/admin/books/create', ['App\Controllers\AdminController', 'saveBook']);
$router->get('/admin/books/edit/{id}', ['App\Controllers\AdminController', 'edit']);
$router->post('/admin/books/update', ['App\Controllers\AdminController', 'update']);
$router->post('/admin/books/delete/{id}', ['App\Controllers\AdminController', 'delete']);

// Admin category management routes
$router->get('/admin/category-create', ['App\Controllers\AdminController', 'categoryCreate']);
$router->post('/admin/category-create', ['App\Controllers\AdminController', 'categoryCreate']);
$router->get('/admin/category-update', ['App\Controllers\AdminController', 'categoryUpdate']);
$router->post('/admin/category-update', ['App\Controllers\AdminController', 'categoryUpdate']);
$router->get('/admin/category-delete', ['App\Controllers\AdminController', 'categoryDelete']);

// Admin voucher management routes
$router->get('/admin/voucher-create', ['App\Controllers\AdminController', 'voucherCreate']);
$router->post('/admin/voucher-create', ['App\Controllers\AdminController', 'voucherCreate']);
$router->get('/admin/voucher-update', ['App\Controllers\AdminController', 'voucherUpdate']);
$router->post('/admin/voucher-update', ['App\Controllers\AdminController', 'voucherUpdate']);
$router->get('/admin/voucher-delete', ['App\Controllers\AdminController', 'voucherDelete']);

// Admin order management routes
$router->get('/admin/order-update', ['App\Controllers\AdminController', 'orderUpdate']);
$router->get('/admin/order-cancel', ['App\Controllers\AdminController', 'orderCancel']);

// Admin user management routes
$router->get('/admin/user-create', ['App\Controllers\AdminController', 'userCreate']);
$router->post('/admin/user-create', ['App\Controllers\AdminController', 'userCreate']);
$router->get('/admin/user-edit', ['App\Controllers\AdminController', 'userEdit']);
$router->post('/admin/user-edit', ['App\Controllers\AdminController', 'userEdit']);
$router->get('/admin/user-delete', ['App\Controllers\AdminController', 'userDelete']);

// Xử lý query string ?page= trước khi router
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'dashboard':
        case '':
            $controller = new \App\Controllers\AdminController();
            $controller->index();
            exit;
        case 'products':
        case 'books':
            $controller = new \App\Controllers\AdminController();
            $controller->books();
            exit;
        case 'orders':
            $controller = new \App\Controllers\AdminController();
            $controller->orders();
            exit;
        case 'users':
            $controller = new \App\Controllers\AdminController();
            $controller->users();
            exit;
        case 'vouchers':
            $controller = new \App\Controllers\AdminController();
            $controller->vouchers();
            exit;
        case 'categories':
            $controller = new \App\Controllers\AdminController();
            $controller->categories();
            exit;
        case 'statistics':
            $controller = new \App\Controllers\AdminController();
            $controller->statistics();
            exit;
        case 'login':
            $controller = new \App\Controllers\AuthController();
            $controller->login();
            exit;
        case 'register':
            $controller = new \App\Controllers\AuthController();
            $controller->register();
            exit;
        case 'cart':
            $controller = new \App\Controllers\CartController();
            $controller->index();
            exit;
        case 'checkout':
            $controller = new \App\Controllers\CheckoutController();
            $controller->index();
            exit;
        case 'checkout/success':
            $controller = new \App\Controllers\CheckoutController();
            $controller->success();
            exit;
        case 'search':
            $controller = new \App\Controllers\SearchController();
            $controller->index();
            exit;
        case 'account':
            $controller = new \App\Controllers\AccountController();
            $controller->index();
            exit;
    }
}

// Dispatch router
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
