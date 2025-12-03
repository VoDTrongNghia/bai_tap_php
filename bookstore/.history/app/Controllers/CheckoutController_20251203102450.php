<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Repositories\BookRepository;
use App\Models\Order;
use App\Models\User;

class CheckoutController
{
    private BookRepository $books;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->books = new BookRepository();
    }

    public function index(): void
    {
        // Check if cart is empty
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn đang trống!';
            if (!defined('BASE_URL')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_URL, '/');
            header('Location: ' . $baseUrl . 'cart');
            exit;
        }

        // Get cart items
        $ids = [];
        foreach ($cart as $id => $qty) {
            $ids[] = (string)$id;
        }
        $items = $this->books->findByIds($ids);

        // Calculate totals
        $total = 0;
        foreach ($items as $book) {
            $qty = (int)($cart[$book->id] ?? 0);
            $total += $qty * $book->price;
        }

        // Apply voucher if exists
        $voucher = $_SESSION['voucher'] ?? null;
        $discountAmount = 0;
        $finalTotal = $total;
        
        if ($voucher) {
            if ($voucher['type'] === 'percent') {
                $discountAmount = ($total * $voucher['discount']) / 100;
            } else {
                $discountAmount = min($voucher['discount'], $total);
            }
            $finalTotal = max(0, $total - $discountAmount);
        }

        // Check if user is logged in
        $isLoggedIn = isset($_SESSION['user']);
        $user = $_SESSION['user'] ?? [];
        $hasAddress = !empty($user['dia_chi'] ?? $user['address'] ?? '');

        View::render('checkout/index', [
            'title' => 'Thanh toán',
            'cart' => $cart,
            'items' => $items,
            'total' => $total,
            'discountAmount' => $discountAmount,
            'finalTotal' => $finalTotal,
            'voucher' => $voucher,
            'user' => $user,
            'hasAddress' => $hasAddress,
            'isLoggedIn' => $isLoggedIn,
        ]);
    }

    public function success(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Get last order
        $order = $_SESSION['last_order'] ?? null;
        
        if (!$order) {
            if (!defined('BASE_URL')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_URL, '/');
            header('Location: ' . $baseUrl);
            exit;
        }

        // Clear last order from session
        unset($_SESSION['last_order']);

        View::render('checkout/success', [
            'title' => 'Đặt hàng thành công',
            'order' => $order,
        ]);
    }

    public function process(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Validate required fields (allow guest checkout)
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? '');

        if (empty($name) || empty($phone) || empty($address) || empty($paymentMethod)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            exit;
        }

        // Get cart and calculate totals
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng đang trống']);
            exit;
        }

        $ids = [];
        foreach ($cart as $id => $qty) {
            $ids[] = (string)$id;
        }
        $items = $this->books->findByIds($ids);

        $total = 0;
        foreach ($items as $book) {
            $qty = (int)($cart[$book->id] ?? 0);
            $total += $qty * $book->price;
        }

        // Apply voucher if exists
        $voucher = $_SESSION['voucher'] ?? null;
        $discountAmount = 0;
        $finalTotal = $total;
        
        if ($voucher) {
            if ($voucher['type'] === 'percent') {
                $discountAmount = ($total * $voucher['discount']) / 100;
            } else {
                $discountAmount = min($voucher['discount'], $total);
            }
            $finalTotal = max(0, $total - $discountAmount);
        }

        // Save address to user session and database (if logged in)
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        
        if ($userId > 0) {
            // User is logged in, update their info
            $_SESSION['user']['ho_ten'] = $name;
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['so_dien_thoai'] = $phone;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['dia_chi'] = $address;
            $_SESSION['user']['address'] = $address;
            
            // Update user address in database
            try {
                $user = User::findById($userId);
                if ($user) {
                    $user->updateAddress($phone, $address);
                }
            } catch (\Exception $e) {
                // Log error but continue with order processing
                error_log("Error updating user address: " . $e->getMessage());
            }
        }

        // Generate order code
        $orderCode = 'ORD' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Create order
        $order = new Order([
            'user_id' => $userId,
            'order_code' => $orderCode,
            'customer_name' => $name,
            'customer_phone' => $phone,
            'customer_address' => $address,
            'payment_method' => $paymentMethod,
            'subtotal' => $total,
            'discount' => $discountAmount,
            'total' => $finalTotal,
            'status' => 'pending',
            'note' => trim($_POST['note'] ?? ''),
        ]);
        
        // Save order to database
        $orderSaved = false;
        try {
            $orderSaved = $order->save();
            
            // Save order items
            if ($orderSaved && $order->id > 0) {
                foreach ($items as $book) {
                    $qty = (int)($cart[$book->id] ?? 0);
                    if ($qty > 0) {
                        $order->addItem((int)$book->id, $qty, $book->price);
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("Error saving order to database: " . $e->getMessage());
            // Continue with session-based order if database save fails
        }
        
        // Prepare order data for response
        $orderData = [
            'order_id' => $orderCode,
            'order_code' => $orderCode,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'payment_method' => $paymentMethod,
            'total' => $total,
            'discount' => $discountAmount,
            'final_total' => $finalTotal,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $userId,
        ];
        
        // Clear cart and voucher
        $_SESSION['cart'] = [];
        unset($_SESSION['voucher']);
        $_SESSION['last_order'] = $orderData;

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm.',
            'redirect' => '/checkout/success'
        ]);
        exit;
    }
}

