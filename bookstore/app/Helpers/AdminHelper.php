<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Admin Helper - Kiểm tra quyền admin và session timeout
 */
class AdminHelper
{
    // Session timeout: 30 phút (1800 giây)
    private const SESSION_TIMEOUT = 1800;

    /**
     * Kiểm tra xem người dùng có phải admin và session còn hợp lệ không
     * @return bool
     */
    public static function checkAdminAccess(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Kiểm tra session timeout
        if (isset($_SESSION['last_activity'])) {
            $timeSinceLastActivity = time() - $_SESSION['last_activity'];
            if ($timeSinceLastActivity > self::SESSION_TIMEOUT) {
                // Session đã hết hạn
                self::destroySession();
                return false;
            }
        }

        // Cập nhật last activity
        $_SESSION['last_activity'] = time();

        // Kiểm tra quyền admin
        $role = $_SESSION['role'] ?? $_SESSION['vai_tro'] ?? null;
        // Xử lý cả 'admin', 'quan_tri_vien' và 'quan_tri_vien ' (có thể có khoảng trắng)
        $role = trim($role ?? '');
        $isAdmin = ($role === 'admin' || $role === 'quan_tri_vien');
        
        // Kiểm tra user session
        $hasUser = isset($_SESSION['user']) || isset($_SESSION['user_id']);

        return $isAdmin && $hasUser;
    }

    /**
     * Lấy thông tin admin hiện tại
     * @return array|null
     */
    public static function getCurrentAdmin(): ?array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!self::checkAdminAccess()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['ten_dang_nhap'] ?? $_SESSION['user']['ten_dang_nhap'] ?? 'Admin',
            'name' => $_SESSION['user']['ho_ten'] ?? $_SESSION['user']['name'] ?? $_SESSION['user_name'] ?? 'Administrator',
            'email' => $_SESSION['user']['email'] ?? '',
            'role' => $_SESSION['role'] ?? $_SESSION['vai_tro'] ?? 'admin',
        ];
    }

    /**
     * Redirect về trang login nếu không phải admin
     * @param string $message
     */
    public static function requireAdmin(string $message = 'Bạn không có quyền truy cập trang này. Vui lòng đăng nhập.'): void
    {
        if (!self::checkAdminAccess()) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['error'] = $message;
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
            
            if (!defined('BASE_PATH')) {
                require_once __DIR__ . '/../../config.php';
            }
            $baseUrl = rtrim(BASE_PATH, '/');
            header('Location: ' . $baseUrl . '/login');
            exit;
        }
    }

    /**
     * Hủy session
     */
    public static function destroySession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }
    }

    /**
     * Khởi tạo session cho admin sau khi đăng nhập
     * @param array $userData
     */
    public static function initAdminSession(array $userData): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user'] = $userData;
        $_SESSION['user_id'] = $userData['id'] ?? $userData['ma_nguoi_dung'] ?? null;
        $_SESSION['ten_dang_nhap'] = $userData['ten_dang_nhap'] ?? '';
        $_SESSION['user_name'] = $userData['ho_ten'] ?? $userData['name'] ?? 'Administrator';
        $vaiTro = trim($userData['vai_tro'] ?? 'admin');
        $_SESSION['vai_tro'] = $vaiTro;
        // Set role: 'admin' nếu vai_tro là 'admin' hoặc 'quan_tri_vien' (có thể có khoảng trắng)
        $_SESSION['role'] = ($vaiTro === 'admin' || $vaiTro === 'quan_tri_vien') ? 'admin' : 'user';
        $_SESSION['last_activity'] = time();
    }
}

