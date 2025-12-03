<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Models\User;
use App\Models\Order;

class AccountController
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function index(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem tài khoản';
            if (!defined('BASE_PATH')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_PATH, '/');
            header('Location: ' . $baseUrl . '/login');
            exit;
        }

        $user = $_SESSION['user'];
        
        // Get user orders from database
        $userId = (int)($user['id'] ?? 0);
        $orders = [];
        if ($userId > 0) {
            try {
                $orders = Order::findByUserId($userId);
            } catch (\Exception $e) {
                error_log("Error fetching orders: " . $e->getMessage());
            }
        }
        
        View::render('account/index', [
            'title' => 'Tài khoản',
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function updateProfile(): void
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($name) || empty($email)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            exit;
        }

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
        exit;
    }

    public function updateAddress(): void
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $address = trim($_POST['address'] ?? '');

        if (empty($address)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập địa chỉ']);
            exit;
        }

        $_SESSION['user']['address'] = $address;

        // Update address in database if user exists
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        if ($userId > 0) {
            try {
                $user = User::findById($userId);
                if ($user) {
                    $phone = $_SESSION['user']['phone'] ?? '';
                    $user->updateAddress($phone, $address);
                }
            } catch (\Exception $e) {
                error_log("Error updating address in database: " . $e->getMessage());
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Cập nhật địa chỉ thành công!']);
        exit;
    }

    public function changePassword(): void
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới không khớp']);
            exit;
        }

        // In real app, verify current password from database
        // For now, just update
        $_SESSION['user']['password'] = password_hash($newPassword, PASSWORD_DEFAULT);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
        exit;
    }
}

