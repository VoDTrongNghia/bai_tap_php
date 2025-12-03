<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\Order;
use App\Repositories\BookRepository;

class OrderController
{
    private BookRepository $books;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->books = new BookRepository();
    }

    public function history(): void
    {
        $isLoggedIn = isset($_SESSION['user']);
        $orders = [];
        $searchOrder = null;

        if ($isLoggedIn) {
            // Get orders for logged in user
            $userId = (int)($_SESSION['user']['id'] ?? 0);
            if ($userId > 0) {
                try {
                    $orders = Order::findByUserId($userId);
                } catch (\Exception $e) {
                    error_log("Error fetching user orders: " . $e->getMessage());
                }
            }
        } else {
            // Guest can search by phone or order code
            $searchPhone = trim($_GET['phone'] ?? '');
            $searchCode = trim($_GET['code'] ?? '');

            if (!empty($searchPhone) || !empty($searchCode)) {
                try {
                    $searchOrder = Order::findByPhoneOrCode($searchPhone, $searchCode);
                } catch (\Exception $e) {
                    error_log("Error searching order: " . $e->getMessage());
                }
            }
        }

        View::render('orders/history', [
            'title' => 'Lịch sử đơn hàng',
            'orders' => $orders,
            'searchOrder' => $searchOrder,
            'isLoggedIn' => $isLoggedIn,
        ]);
    }

    public function detail(): void
    {
        $orderCode = $_GET['code'] ?? '';
        
        if (empty($orderCode)) {
            if (!defined('BASE_PATH')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_PATH, '/');
            header('Location: ' . $baseUrl . '/orders');
            exit;
        }

        try {
            $order = Order::findByCode($orderCode);
            
            if (!$order) {
                $_SESSION['error'] = 'Không tìm thấy đơn hàng';
                if (!defined('BASE_PATH')) {
                    require_once __DIR__ . '/../../config.php';
                }
                $baseUrl = rtrim(BASE_PATH, '/');
                header('Location: ' . $baseUrl . '/orders');
                exit;
            }

            // Get order items
            $items = $order->getItems();
            $books = [];
            if (!empty($items)) {
                $bookIds = array_column($items, 'book_id');
                $books = $this->books->findByIds($bookIds);
            }

            View::render('orders/detail', [
                'title' => 'Chi tiết đơn hàng',
                'order' => $order,
                'items' => $items,
                'books' => $books,
            ]);
        } catch (\Exception $e) {
            error_log("Error fetching order detail: " . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi tải đơn hàng';
            if (!defined('BASE_PATH')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_PATH, '/');
            header('Location: ' . $baseUrl . '/orders');
            exit;
        }
    }
}

