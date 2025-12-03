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

// =============================================
// ADMIN ROUTES
// =============================================

// Admin dashboard
$router->get('/admin', ['App\Controllers\AdminController', 'index']);
$router->get('/admin/dashboard', ['App\Controllers\AdminController', 'index']);

// Book management
$router->get('/admin/books', ['App\Controllers\AdminController', 'index']);
$router->get('/admin/books/create', ['App\Controllers\AdminController', 'create']);
$router->post('/admin/books/save', ['App\Controllers\AdminController', 'saveBook']);
$router->get('/admin/books/edit/(\d+)', ['App\Controllers\AdminController', 'edit']);
$router->post('/admin/books/update', ['App\Controllers\AdminController', 'update']);
$router->post('/admin/books/delete/(\d+)', ['App\Controllers\AdminController', 'delete']);

// Category management
$router->get('/admin/categories', ['App\Controllers\AdminController', 'categories']);
$router->get('/admin/categories/add', ['App\Controllers\AdminController', 'categoryForm']);
$router->post('/admin/categories/save', ['App\Controllers\AdminController', 'saveCategory']);
$router->get('/admin/categories/edit/(\d+)', ['App\Controllers\AdminController', 'categoryForm']);
$router->post('/admin/categories/delete/(\d+)', ['App\Controllers\AdminController', 'deleteCategory']);

// Voucher management
$router->get('/admin/vouchers', ['App\Controllers\AdminController', 'vouchers']);
$router->get('/admin/vouchers/add', ['App\Controllers\AdminController', 'voucherForm']);
$router->get('/admin/vouchers/edit/(\d+)', ['App\Controllers\AdminController', 'voucherForm']);
$router->post('/admin/vouchers/save', ['App\Controllers\AdminController', 'saveVoucher']);
$router->post('/admin/vouchers/delete/(\d+)', ['App\Controllers\AdminController', 'deleteVoucher']);
$router->get('/admin/vouchers/get/(\d+)', ['App\Controllers\AdminController', 'getVoucher']);

// Order management
$router->get('/admin/orders', ['App\Controllers\AdminController', 'orders']);
$router->get('/admin/orders/(\d+)', ['App\Controllers\AdminController', 'orderDetail']);
$router->post('/admin/orders/update/(\d+)', ['App\Controllers\AdminController', 'updateOrderStatus']);
$router->post('/admin/orders/cancel/(\d+)', ['App\Controllers\AdminController', 'cancelOrder']);
$router->post('/admin/orders/delete/(\d+)', ['App\Controllers\AdminController', 'deleteOrder']);
$router->post('/admin/orders/bulk-delete', ['App\Controllers\AdminController', 'bulkDeleteOrders']);

// User management
$router->get('/admin/users', ['App\Controllers\AdminController', 'users']);
$router->get('/admin/users/add', ['App\Controllers\AdminController', 'userForm']);
$router->post('/admin/users/save', ['App\Controllers\AdminController', 'saveUser']);
$router->get('/admin/users/edit/(\d+)', ['App\Controllers\AdminController', 'userForm']);
$router->post('/admin/users/delete/(\d+)', ['App\Controllers\AdminController', 'deleteUser']);

// Dispatch router
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);