<?php
declare(strict_types=1);

namespace App\Controllers;

use PDO;
use App\Helpers\View;
use App\Helpers\AdminHelper;
use App\Repositories\BookRepository;
use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use App\Models\Voucher;

class AdminController
{
    private BookRepository $bookRepository;
    
    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }
    
    /**
     * Hiển thị trang chủ admin
     */
    public function index(): void
    {
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Lấy thống kê
        $totalBooks = count($this->bookRepository->all());
        
        // Lấy tổng số người dùng
        $userModel = new User();
        $totalUsers = method_exists($userModel, 'countAll') ? $userModel->countAll() : 0;
        
        // Lấy thông tin đơn hàng
        $orderModel = new Order([]); // Khởi tạo với mảng rỗng
        $totalOrders = method_exists($orderModel, 'countAll') ? $orderModel->countAll() : 0;
        $recentOrders = method_exists($orderModel, 'getRecentOrders') ? $orderModel->getRecentOrders(5) : [];

        // Hiển thị view
        View::render('admin/dashboard_full', [
            'title' => 'Trang Quản Trị',
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => 0,
            'recentOrders' => $recentOrders
        ]);
    }
    
    // ... (giữ nguyên các phương thức khác)
    
    /**
     * Hiển thị trang quản lý voucher
     */
    public function vouchers(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header('Location: ' . BASE_PATH . 'login');
            exit;
        }

        $voucherModel = new Voucher();
        $errors = [];

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'ma_voucher' => trim($_POST['ma_voucher'] ?? ''),
                    'mo_ta' => trim($_POST['mo_ta'] ?? ''),
                    'giam_gia' => (float)($_POST['giam_gia'] ?? 0),
                    'kieu_giam' => ($_POST['kieu_giam'] ?? 'percent') === 'percent' ? 'percent' : 'fixed',
                    'gia_tri_toi_thieu' => (float)($_POST['gia_tri_toi_thieu'] ?? 0),
                    'so_luong' => (int)($_POST['so_luong'] ?? 1),
                    'ngay_bat_dau' => $_POST['ngay_bat_dau'] ?? date('Y-m-d H:i:s'),
                    'ngay_ket_thuc' => $_POST['ngay_ket_thuc'] ?? date('Y-m-d H:i:s', strtotime('+1 month')),
                    'trang_thai' => isset($_POST['trang_thai']) ? 1 : 0
                ];

                $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
                $errors = $this->validateVoucherData($data);

                if (empty($errors)) {
                    if ($id) {
                        // Update existing voucher
                        if ($voucherModel->update($id, $data)) {
                            $_SESSION['success'] = 'Cập nhật voucher thành công';
                        } else {
                            throw new Exception('Không thể cập nhật voucher');
                        }
                    } else {
                        // Create new voucher
                        if ($voucherModel->codeExists($data['ma_voucher'])) {
                            $errors[] = 'Mã voucher đã tồn tại';
                        } else if ($voucherModel->create($data)) {
                            $_SESSION['success'] = 'Thêm voucher thành công';
                        } else {
                            throw new Exception('Không thể thêm voucher');
                        }
                    }

                    // Redirect to prevent form resubmission
                    if (empty($errors)) {
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit;
                    }
                }
            } catch (Exception $e) {
                $errors[] = 'Lỗi: ' . $e->getMessage();
            }
        }

        // Get all vouchers for display
        $vouchers = $voucherModel->getAllVouchers();

        // Render the view
        View::render('admin/vouchers/index', [
            'vouchers' => $vouchers,
            'title' => 'Quản lý Voucher',
            'errors' => $errors
        ]);
    }

    /**
     * Validate voucher data
     */
    private function validateVoucherData(array $data): array
    {
        $errors = [];

        if (empty($data['ma_voucher'])) {
            $errors[] = 'Vui lòng nhập mã voucher';
        } elseif (!preg_match('/^[A-Z0-9_-]+$/i', $data['ma_voucher'])) {
            $errors[] = 'Mã voucher chỉ được chứa chữ cái, số, dấu gạch dưới và gạch ngang';
        }

        if ($data['giam_gia'] <= 0) {
            $errors[] = 'Giá trị giảm phải lớn hơn 0';
        }

        if ($data['kieu_giam'] === 'percent' && $data['giam_gia'] > 100) {
            $errors[] = 'Giảm giá phần trăm không được vượt quá 100%';
        }

        if ($data['so_luong'] < 1) {
            $errors[] = 'Số lượng phải lớn hơn 0';
        }

        try {
            $startDate = new \DateTime($data['ngay_bat_dau']);
            $endDate = new \DateTime($data['ngay_ket_thuc']);
            
            if ($endDate <= $startDate) {
                $errors[] = 'Ngày kết thúc phải sau ngày bắt đầu';
            }
        } catch (\Exception $e) {
            $errors[] = 'Định dạng ngày không hợp lệ';
        }

        return $errors;
    }
}
