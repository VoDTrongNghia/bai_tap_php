<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\AdminHelper;
use App\Helpers\Logger;
use App\Models\User;

class AuthController
{
    public function login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Nếu đã đăng nhập, redirect
        if (isset($_SESSION['user'])) {
            $vaiTro = trim($_SESSION['vai_tro'] ?? '');
            if ($vaiTro === 'admin' || $vaiTro === 'quan_tri_vien') {
                // Admin redirect to admin dashboard
                require_once __DIR__ . '/../../config.php';
                header('Location: ' . BASE_URL . 'admin');
                exit;
            } else {
                // Regular user redirect to home
                require_once __DIR__ . '/../../config.php';
                header('Location: ' . BASE_URL);
                exit;
            }
        }

        $title = 'Đăng nhập';
        $error = null;
        $success = null;

        // Handle login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = trim($_POST['username'] ?? $_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Simple validation
            if (empty($usernameOrEmail) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin';
            } else {
                // Find user by ten_dang_nhap or email
                $user = User::findByTenDangNhapOrEmail($usernameOrEmail);

                if ($user && $user->verifyPassword($password)) {
                    // Đăng nhập thành công
                    Logger::login("User logged in successfully", [
                        'user_id' => $user->getId(),
                        'ten_dang_nhap' => $user->getTenDangNhap(),
                        'vai_tro' => $user->getVaiTro(),
                        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
                    
                    $userData = $user->toArray();
                    
                    // Sử dụng AdminHelper để khởi tạo session
                    AdminHelper::initAdminSession($userData);
                    
                    $_SESSION['success'] = 'Đăng nhập thành công!';

                    // Include config for base URL
                    require_once __DIR__ . '/../../config.php';
                    $baseUrl = rtrim(BASE_URL, '/');

                    // Check role and redirect accordingly
                    $vaiTro = trim($user->getVaiTro());
                    if ($vaiTro === 'admin' || $vaiTro === 'quan_tri_vien') {
                        // Admin redirect to admin dashboard
                        $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . 'admin';
                        unset($_SESSION['redirect_after_login']);
                        header('Location: ' . $redirect);
                        exit;
                    } else {
                        // Regular user redirect to home or checkout
                        $redirect = $_SESSION['checkout_redirect'] ?? BASE_URL;
                        unset($_SESSION['checkout_redirect']);
                        header('Location: ' . $redirect);
                        exit;
                    }
                } else {
                    // Đăng nhập thất bại
                    Logger::authError("Login failed", [
                        'username_or_email' => $usernameOrEmail,
                        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                        'user_found' => $user !== null,
                        'password_correct' => $user ? $user->verifyPassword($password) : false
                    ]);
                    $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
                }
            }
        }

        // Hiển thị trang đăng nhập
        View::render('auth/login', [
            'title' => $title,
            'error' => $error,
            'success' => $success,
            'baseUrl' => BASE_URL,
            'appName' => APP_NAME
        ]);
    }

    public function register(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $title = 'Đăng ký';
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDangNhap = trim($_POST['ten_dang_nhap'] ?? $_POST['username'] ?? '');
            $hoTen = trim($_POST['ho_ten'] ?? $_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? $_POST['phone'] ?? '');
            $diaChi = trim($_POST['dia_chi'] ?? $_POST['address'] ?? '');
            $vaiTro = trim($_POST['vai_tro'] ?? 'khach_hang');

            // Validation
            if (empty($tenDangNhap)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            }

            if (empty($hoTen)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }

            if (empty($email)) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            // Check if ten_dang_nhap or email already exists
            // Only check if both fields are not empty
            if (!empty($tenDangNhap) && !empty($email)) {
                $exists = User::checkExists($tenDangNhap, $email);
                if ($exists['exists']) {
                    if ($exists['ten_dang_nhap_exists']) {
                        $errors[] = 'Tên đăng nhập này đã được sử dụng';
                    }
                    if ($exists['email_exists']) {
                        $errors[] = 'Email này đã được sử dụng';
                    }
                }
            }

            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }

            // Nếu không có lỗi thì lưu người dùng
            if (empty($errors)) {
                // Double check before saving (race condition protection)
                $exists = User::checkExists($tenDangNhap, $email);
                if ($exists['exists']) {
                    Logger::registration("Registration attempt with existing data", [
                        'ten_dang_nhap' => $tenDangNhap,
                        'email' => $email,
                        'ten_dang_nhap_exists' => $exists['ten_dang_nhap_exists'],
                        'email_exists' => $exists['email_exists'],
                        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
                    
                    if ($exists['ten_dang_nhap_exists']) {
                        $errors[] = 'Tên đăng nhập này đã được sử dụng';
                    }
                    if ($exists['email_exists']) {
                        $errors[] = 'Email này đã được sử dụng';
                    }
                } else {
                    // Proceed with registration
                    $user = new User();
                    $user->setTenDangNhap($tenDangNhap);
                    $user->setHoTen($hoTen);
                    $user->setEmail($email);
                    $user->setMatKhau(User::hashPassword($password));
                    $user->setSoDienThoai($soDienThoai);
                    $user->setDiaChi($diaChi);
                    $user->setVaiTro($vaiTro);

                    if ($user->save()) {
                        Logger::registration("User registered successfully", [
                            'user_id' => $user->getId(),
                            'ten_dang_nhap' => $user->getTenDangNhap(),
                            'email' => $user->getEmail(),
                            'vai_tro' => $user->getVaiTro(),
                            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                        ]);
                        
                        $userData = $user->toArray();
                        $_SESSION['user'] = $userData;
                        $_SESSION['user_id'] = $user->getId();
                        $_SESSION['ten_dang_nhap'] = $user->getTenDangNhap();
                        $_SESSION['vai_tro'] = $user->getVaiTro();
                        $_SESSION['success'] = 'Đăng ký thành công!';

                        require_once __DIR__ . '/../../config.php';
                        header('Location: ' . BASE_URL);
                        exit;
                    } else {
                        Logger::registration("User registration failed", [
                            'ten_dang_nhap' => $tenDangNhap,
                            'email' => $email,
                            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                            'db_error' => $_SESSION['db_error'] ?? 'unknown error'
                        ]);
                        // Hiển thị lỗi chi tiết từ database nếu có
                        $dbError = $_SESSION['db_error'] ?? null;
                        if ($dbError) {
                            // Kiểm tra các lỗi phổ biến
                            if (strpos($dbError, 'Duplicate entry') !== false) {
                                if (strpos($dbError, 'ten_dang_nhap') !== false || strpos($dbError, 'PRIMARY') !== false) {
                                    $errors[] = 'Tên đăng nhập này đã được sử dụng';
                                } elseif (strpos($dbError, 'email') !== false) {
                                    $errors[] = 'Email này đã được sử dụng';
                                } else {
                                    $errors[] = 'Thông tin đã tồn tại trong hệ thống';
                                }
                            } elseif (strpos($dbError, 'SQLSTATE') !== false) {
                                // Lỗi SQL - có thể do cấu trúc bảng
                                $errors[] = 'Lỗi cơ sở dữ liệu. Vui lòng kiểm tra lại cấu trúc bảng nguoi_dung.';
                                error_log("SQL Error during registration: " . $dbError);
                            } else {
                                $errors[] = 'Có lỗi xảy ra khi đăng ký: ' . htmlspecialchars(substr($dbError, 0, 200));
                            }
                            unset($_SESSION['db_error']);
                        } else {
                            $errors[] = 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.';
                        }
                    }
                }
            }
        }

        View::render('auth/register', compact('title', 'errors', 'success'));
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Lưu thông báo trước khi hủy session
        $wasAdmin = (trim($_SESSION['role'] ?? '') === 'admin' || trim($_SESSION['vai_tro'] ?? '') === 'admin' || trim($_SESSION['vai_tro'] ?? '') === 'quan_tri_vien');
        
        // Sử dụng AdminHelper để hủy session
        AdminHelper::destroySession();
        
        // Start a new session to show success message
        session_start();
        $_SESSION['success'] = 'Đăng xuất thành công!';
        
        require_once __DIR__ . '/../../config.php';
        $baseUrl = BASE_URL;
        
        // Redirect về login route (không phải login.php)
        header('Location: ' . $baseUrl . 'login');
        exit;
    }
}
