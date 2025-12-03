<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Repositories\BookRepository;

class CartController
{
    private BookRepository $books;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        \App\Helpers\Logger::debug("CartController initialized", [
            'session_status' => session_status(),
            'session_id' => session_id()
        ]);
        
        $this->books = new BookRepository();
    }

    public function index(): void
    {
        // KHÔNG gọi session_start() ở đây - đã được gọi trong bootstrap.php
        // if (session_status() !== PHP_SESSION_ACTIVE) {
        //     session_start();
        // }
        
        // Debug: Check for session ID in URL
        if (isset($_GET['PHPSESSID'])) {
            session_id($_GET['PHPSESSID']);
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        // Check if cart is in new format (with book details) or old format (just quantities)
        $items = [];
        $total = 0;
        
        if (!empty($cart)) {
            // Check if first item has book details (new format)
            $firstItem = reset($cart);
            if (is_array($firstItem) && isset($firstItem['title'])) {
                // New format: cart already contains book details
                foreach ($cart as $id => $item) {
                    // Create Book object with all necessary properties
                    $bookData = [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'author' => $item['author'],
                        'price' => $item['price'],
                        'original_price' => $item['original_price'],
                        'discount_percentage' => $item['discount_percentage'],
                        'coverImage' => $item['image'] ?? ''
                    ];
                    $book = new \App\Models\Book($bookData);
                    $items[] = $book;
                    $total += $item['price'] * $item['qty'];
                }
            } else {
                // Old format: cart only has quantities, need to fetch book details
                $ids = [];
                foreach ($cart as $id => $qty) {
                    $ids[] = (string)$id;
                }
                $items = $this->books->findByIds($ids);
                
                // Calculate total
                foreach ($items as $book) {
                    $qty = (int)($cart[$book->id] ?? 0);
                    $total += $qty * $book->getPrice();
                }
            }
        }
        
        // Get and clear cart message
        $message = $_SESSION['cart_message'] ?? null;
        if (isset($_SESSION['cart_message'])) {
            unset($_SESSION['cart_message']);
        }
        
        View::render('cart/index', [
            'title' => 'Giỏ hàng',
            'cart' => $cart,
            'items' => $items,
            'message' => $message,
            'total' => $total,
        ]);
    }

    public function add(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Log debug info
        \App\Helpers\Logger::debug("CartController::add() called", [
            'POST_data' => $_POST,
            'SERVER_data' => [
                'HTTP_X_REQUESTED_WITH' => $_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'not set',
                'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'not set',
                'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? 'not set'
            ]
        ]);
        
        $id = (string)($_POST['id'] ?? '');
        $qty = (int)($_POST['qty'] ?? 1);
        
        \App\Helpers\Logger::debug("Processing add to cart", [
            'book_id' => $id,
            'quantity' => $qty
        ]);
        
        if ($id === '') {
            $this->respondJson(['success' => false, 'message' => 'Thiếu mã sách'], 400);
        }
        
        // Get book details from database
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }
        
        try {
            $pdo = \App\Database::getConnection();
            \App\Helpers\Logger::debug("Database connection successful");
        } catch (Exception $e) {
            \App\Helpers\Logger::error("Database connection failed", [
                'error' => $e->getMessage()
            ]);
            
            $this->respondJson(['success' => false, 'message' => 'Lỗi kết nối database'], 500);
        }
        
        $stmt = $pdo->prepare("SELECT * FROM sach WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();
        
        \App\Helpers\Logger::debug("Database query result", [
            'book_id' => $id,
            'book_found' => !empty($book),
            'book_data' => $book ?: 'not found'
        ]);
        
        if (!$book) {
            $this->respondJson(['success' => false, 'message' => 'Sách không tồn tại'], 404);
        }
        
        try {
            // Initialize cart if needed
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Create base URL for images
            if (!defined('BASE_PATH')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_PATH, '/');
            
            // Get book details from database
            $bookRepo = new \App\Repositories\BookRepository();
            $bookObj = $bookRepo->findById($id);
            
            if (!$bookObj) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sách']);
                return;
            }
            
            // Add book details to cart
            if (!isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = [
                    'id' => $bookObj->id,
                    'title' => $bookObj->title,
                    'author' => $bookObj->author,
                    'price' => $bookObj->getPrice(),
                    'original_price' => $bookObj->originalPrice,
                    'discount_percentage' => $bookObj->discountPercentage,
                    'image' => $bookObj->getImageUrl(),
                    'qty' => 0
                ];
            }
            
            // Update quantity
            $_SESSION['cart'][$id]['qty'] += max(1, $qty);
            
            // Log the action
            \App\Helpers\Logger::debug("Book added to cart", [
                'book_id' => $bookObj->id,
                'book_title' => $bookObj->title,
                'book_author' => $bookObj->author,
                'quantity' => $qty,
                'cart_total' => count($_SESSION['cart'])
            ]);
            
        } catch (Exception $e) {
            \App\Helpers\Logger::error("Exception in CartController::add()", [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'book_id' => $id ?? 'not set',
                'quantity' => $qty ?? 'not set'
            ]);
            
            $this->respondJson(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
        
        $state = $this->calculateCartState($id);
        
        if ($this->isAjaxRequest()) {
            $response = [
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!',
                'cartCount' => $state['cartCount'],
                'subtotal' => $state['subtotal'],
                'finalTotal' => $state['finalTotal'],
                'discount' => $state['discount'],
                'lineTotal' => $state['lineTotal']
            ];
            
            \App\Helpers\Logger::debug("Sending JSON response", [
                'response' => $response,
                'cart_count' => $state['cartCount']
            ]);
            
            $this->respondJson($response);
        }
        
        // Fallback to redirect for non-AJAX requests
        $_SESSION['cart_message'] = 'Đã thêm sản phẩm vào giỏ hàng thành công!';
        $redirect = trim($_POST['redirect'] ?? '');
        $redirectPath = '/books';
        
        if (!empty($redirect)) {
            if (filter_var($redirect, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($redirect);
                $redirectPath = $parsed['path'] ?? '/books';
                if (!defined('BASE_PATH')) {
                    require_once __DIR__ . '/../../config.php';
                }
                $basePath = rtrim(parse_url(BASE_PATH, PHP_URL_PATH), '/');
                if ($basePath && $basePath !== '/' && strpos($redirectPath, $basePath) === 0) {
                    $redirectPath = substr($redirectPath, strlen($basePath));
                }
            } else {
                $redirectPath = $redirect;
            }
            
            if (empty($redirectPath) || $redirectPath[0] !== '/') {
                $redirectPath = '/' . ltrim($redirectPath, '/');
            }
            
            $allowedPatterns = [
                '/^\/$/', '/^\/books\/?$/', '/^\/books\/[0-9]+$/', '/^\/books\?/', '/^\/cart\/?$/',
            ];
            
            $isValid = false;
            foreach ($allowedPatterns as $pattern) {
                if (preg_match($pattern, $redirectPath)) {
                    $isValid = true;
                    break;
                }
            }
            
            if (!$isValid) {
                $redirectPath = '/books';
            }
        }
        
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }
        $baseUrl = rtrim(BASE_PATH, '/');
        header('Location: ' . $baseUrl . $redirectPath);
        exit;
    }

    public function update(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $id = (string)($_POST['id'] ?? '');
        $qty = (int)($_POST['qty'] ?? 0);
        
        if ($id === '') {
            $this->respondJson(['success' => false, 'message' => 'Thiếu mã sách'], 400);
        }
        
        if (isset($_SESSION['cart'][$id])) {
            if ($qty > 0) {
                // Check if cart is in new format
                if (is_array($_SESSION['cart'][$id]) && isset($_SESSION['cart'][$id]['title'])) {
                    // New format - update qty in the item array
                    $_SESSION['cart'][$id]['qty'] = $qty;
                } else {
                    // Old format - just store quantity
                    $_SESSION['cart'][$id] = $qty;
                }
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        
        $state = $this->calculateCartState($id);
        
        // Return JSON for AJAX requests
        if ($this->isAjaxRequest()) {
            $this->respondJson([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng!',
                'cartCount' => $state['cartCount'],
                'subtotal' => $state['subtotal'],
                'finalTotal' => $state['finalTotal'],
                'discount' => $state['discount'],
                'lineTotal' => $state['lineTotal']
            ]);
        }
        
        // Fallback to redirect for non-AJAX requests
        $_SESSION['cart_message'] = 'Đã cập nhật giỏ hàng!';
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }
        $baseUrl = rtrim(BASE_PATH, '/');
        header('Location: ' . $baseUrl . '/cart');
        exit;
    }

    public function delete(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $id = (string)($_POST['id'] ?? $_GET['id'] ?? '');
        
        if ($id === '') {
            $this->respondJson(['success' => false, 'message' => 'Thiếu mã sách'], 400);
        }
        
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        
        $state = $this->calculateCartState();
        
        // Return JSON for AJAX requests
        if ($this->isAjaxRequest()) {
            $this->respondJson([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cartCount' => $state['cartCount'],
                'subtotal' => $state['subtotal'],
                'finalTotal' => $state['finalTotal'],
                'discount' => $state['discount']
            ]);
        }
        
        // Fallback to redirect for non-AJAX requests - use query string routing
        $_SESSION['cart_message'] = 'Đã xóa sản phẩm khỏi giỏ hàng!';
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }
        $baseUrl = rtrim(BASE_PATH, '/');
        header('Location: ' . $baseUrl . '/index.php?page=cart');
        exit;
    }

    public function count(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Clean any previous output
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        
        // Check if cart is in new format (with book details) or old format (just quantities)
        if (!empty($cart)) {
            $firstItem = reset($cart);
            if (is_array($firstItem) && isset($firstItem['title'])) {
                // New format: sum up qty from each item
                foreach ($cart as $item) {
                    $total += (int)($item['qty'] ?? 0);
                }
            } else {
                // Old format: cart values are quantities
                foreach ($cart as $qty) {
                    $total += (int)$qty;
                }
            }
        }
        
        echo json_encode(['count' => $total]);
        exit;
    }

    public function applyVoucher(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Clean any previous output
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        $code = trim($_POST['code'] ?? '');
        
        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã voucher']);
            exit;
        }
        
        // Validate voucher code
        $voucher = $this->validateVoucher($code);
        
        if ($voucher) {
            $_SESSION['voucher'] = $voucher;
            echo json_encode([
                'success' => true,
                'message' => 'Áp dụng voucher thành công!',
                'voucher' => $voucher
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Voucher không đúng hoặc đã hết hạn']);
        }
        exit;
    }

    public function removeVoucher(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        unset($_SESSION['voucher']);
        
        // Clean any previous output
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Đã xóa voucher']);
        exit;
    }

    /**
     * Validate voucher code
     * In a real application, this would query a database
     */
    private function validateVoucher(string $code): ?array
    {
        // Sample voucher codes for demo
        $vouchers = [
            'WELCOME10' => ['discount' => 10, 'type' => 'percent', 'min_amount' => 0],
            'SAVE20' => ['discount' => 20, 'type' => 'percent', 'min_amount' => 100000],
            'FLAT50K' => ['discount' => 50000, 'type' => 'fixed', 'min_amount' => 200000],
            'NEW50' => ['discount' => 50, 'type' => 'percent', 'min_amount' => 500000],
        ];
        
        $code = strtoupper($code);
        if (isset($vouchers[$code])) {
            return array_merge(['code' => $code], $vouchers[$code]);
        }
        
        return null;
    }

    private function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function respondJson(array $payload, int $status = 200): void
    {
        http_response_code($status);
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }

    private function calculateCartState(?string $lastId = null): array
    {
        $cart = $_SESSION['cart'] ?? [];
        $cartCount = 0;
        $subtotal = 0;
        $lineTotal = null;
        $voucher = $_SESSION['voucher'] ?? null;
        
        foreach ($cart as $id => $item) {
            if (is_array($item) && isset($item['qty'])) {
                $qty = (int)($item['qty'] ?? 0);
                $price = (float)($item['price'] ?? 0);
            } else {
                $qty = (int)$item;
                $price = 0;
            }
            $cartCount += $qty;
            if ($price > 0) {
                $subtotal += $qty * $price;
                if ($lastId !== null && (string)$id === (string)$lastId) {
                    $lineTotal = $qty * $price;
                }
            }
        }
        
        $discount = 0;
        if ($voucher) {
            if (($voucher['type'] ?? '') === 'percent') {
                $discount = ($subtotal * ($voucher['discount'] ?? 0)) / 100;
            } else {
                $discount = min((float)($voucher['discount'] ?? 0), $subtotal);
            }
        }
        
        $finalTotal = max(0, $subtotal - $discount);
        
        return [
            'cartCount' => $cartCount,
            'subtotal' => $subtotal,
            'finalTotal' => $finalTotal,
            'discount' => $discount,
            'lineTotal' => $lineTotal,
        ];
    }
}


