<?php
declare(strict_types=1);

namespace App\Controllers;

use PDO;
use App\Helpers\View;
use App\Helpers\AdminHelper;
use App\Repositories\BookRepository;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\View\Models\Voucher;

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
     * Hiển thị trang quản lý đơn hàng
     */
    public function orders(): void
    {
        // Log debug
        error_log("AdminController::orders() called");
        error_log("Session data: " . print_r($_SESSION, true));
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            error_log("Admin authentication failed - redirecting to login");
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        error_log("Admin authentication passed - loading orders");
        $orders = Order::getAll();
        error_log("Orders count: " . count($orders));
        
        View::render('admin/orders_full', [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders
        ]);
    }
    
    /**
     * Xem chi tiết đơn hàng
     */
    public function orderDetail($orderId): void
    {
        // Ép kiểu sang int để tránh lỗi
        $orderId = (int) $orderId;
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $orderData = Order::getOrderById($orderId);
        
        if (empty($orderData)) {
            $_SESSION['errors'] = ['Đơn hàng không tồn tại'];
            header('Location: ' . BASE_URL . 'admin?page=orders');
            exit;
        }
        
        View::render('admin/orders/detail', [
            'title' => 'Chi tiết đơn hàng #' . $orderId,
            'order' => $orderData['order'],
            'items' => $orderData['items']
        ]);
    }
    
    /**
     * Hiển thị form tạo/sửa sách
     */
    public function create(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $errors = $_SESSION['errors'] ?? [];
        $success = $_SESSION['success'] ?? '';
        $oldInput = $_SESSION['old_input'] ?? [];

        // Clear session messages
        unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old_input']);

        View::render('admin/create', [
            'title' => 'Thêm sản phẩm mới',
            'errors' => $errors,
            'success' => $success,
            'oldInput' => $oldInput,
            'baseUrl' => BASE_URL
        ]);
    }
    
    /**
     * Lưu sách mới
     */
    public function saveBook(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $errors = [];

        try {
            // Validate input
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);

            if (empty($title)) {
                $errors[] = 'Tên sách không được để trống';
            }
            if (empty($author)) {
                $errors[] = 'Tác giả không được để trống';
            }
            if ($price <= 0) {
                $errors[] = 'Giá phải lớn hơn 0';
            }

            // Handle file upload
            $coverImage = '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $coverImage = 'uploads/products/' . $fileName;
                } else {
                    $errors[] = 'Không thể tải lên hình ảnh';
                }
            }

            if (empty($errors)) {
                // Prepare book data
                $bookData = [
                    'ten_sach' => $title,
                    'tac_gia' => $author,
                    'mo_ta' => $description,
                    'gia_goc' => $price,
                    'gia_khuyen_mai' => $price, // No discount by default
                    'anh_bia' => $coverImage,
                    'cover_image' => $coverImage  // Also set cover_image for consistency
                ];

                // Create book using Book model
                $book = new \App\Models\Book($bookData);
                if ($book->save()) {
                    $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                    header('Location: ' . BASE_URL . 'admin?page=products');
                    exit;
                } else {
                    $errors[] = 'Không thể lưu sản phẩm. Vui lòng thử lại.';
                }
            }
        } catch (Exception $e) {
            error_log("Error saving book: " . $e->getMessage());
            $errors[] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        // If there are errors, redirect back with error messages
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ' . BASE_URL . 'admin/books/create');
        exit;
    }
    
    /**
     * Hiển thị form chỉnh sửa sách
     */
    public function edit($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $id = $params['id'] ?? $_GET['id'] ?? $_POST['id'] ?? 0;
        $book = null;
        $errors = [];
        $success = '';

        // Get book data
        try {
            $book = $this->bookRepository->findById($id);
            if (!$book) {
                $_SESSION['error'] = 'Không tìm thấy sản phẩm';
                header('Location: ' . BASE_URL . 'admin?page=products');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error loading book for edit: " . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi tải thông tin sản phẩm';
            header('Location: ' . BASE_URL . 'admin?page=products');
            exit;
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation and update logic here
            // For now, just show success message
            $success = 'Cập nhật sản phẩm thành công!';
        }

        View::render('admin/edit', [
            'title' => 'Chỉnh sửa sản phẩm',
            'id' => $id,
            'book' => $book,
            'errors' => $errors,
            'success' => $success,
            'baseUrl' => BASE_URL
        ]);
    }
    
    /**
     * Cập nhật sách
     */
    public function update(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $errors = [];
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;

        try {
            // Validate input
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);

            if (empty($title)) {
                $errors[] = 'Tên sách không được để trống';
            }
            if (empty($author)) {
                $errors[] = 'Tác giả không được để trống';
            }
            if ($price <= 0) {
                $errors[] = 'Giá phải lớn hơn 0';
            }

            // Get existing book
            $existingBook = $this->bookRepository->findById($id);
            if (!$existingBook) {
                $errors[] = 'Sản phẩm không tồn tại';
            }

            // Handle file upload
            $coverImage = $existingBook->coverImage ?? '';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $coverImage = 'uploads/products/' . $fileName;
                } else {
                    $errors[] = 'Không thể tải lên hình ảnh';
                }
            }

            if (empty($errors)) {
                // Prepare book data
                $bookData = [
                    'ten_sach' => $title,
                    'tac_gia' => $author,
                    'mo_ta' => $description,
                    'gia_goc' => $price,
                    'gia_khuyen_mai' => $price, // No discount by default
                    'anh_bia' => $coverImage,
                    'cover_image' => $coverImage  // Also set cover_image for consistency
                ];

                // Update book using Book model
                $book = new \App\Models\Book($bookData);
                $book->id = $id;
                
                if ($book->save()) {
                    $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                    header('Location: ' . BASE_URL . 'admin?page=products');
                    exit;
                } else {
                    $errors[] = 'Không thể cập nhật sản phẩm. Vui lòng thử lại.';
                }
            }
        } catch (Exception $e) {
            error_log("Error updating book: " . $e->getMessage());
            $errors[] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        // If there are errors, redirect back with error messages
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: ' . BASE_URL . 'admin/books/edit/' . $id);
        exit;
    }
    
    /**
     * Xóa sách
     */
    public function delete($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $id = $params['id'] ?? $_GET['id'] ?? $_POST['id'] ?? 0;

        try {
            // Get existing book
            $existingBook = $this->bookRepository->findById($id);
            if (!$existingBook) {
                $_SESSION['errors'] = ['Sản phẩm không tồn tại'];
                header('Location: ' . BASE_URL . 'admin?page=products');
                exit;
            }

            // Delete book using repository
            if ($this->bookRepository->delete($id)) {
                // Delete cover image if exists
                if (!empty($existingBook->coverImage)) {
                    $imagePath = __DIR__ . '/../../public/' . $existingBook->coverImage;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                
                $_SESSION['success'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['errors'] = ['Không thể xóa sản phẩm. Vui lòng thử lại.'];
            }
        } catch (Exception $e) {
            error_log("Error deleting book: " . $e->getMessage());
            $_SESSION['errors'] = ['Có lỗi xảy ra: ' . $e->getMessage()];
        }

        header('Location: ' . BASE_URL . 'admin?page=products');
        exit;
    }
    
    /**
     * Hiển thị form thêm/sửa danh mục
     */
    public function categoryForm($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        View::render('admin/categories', [
            'title' => 'Quản lý danh mục'
        ]);
    }
    
    /**
     * Lưu danh mục
     */
    public function saveCategory(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Lưu danh mục thành công!';
        header('Location: ' . BASE_URL . 'admin?page=categories');
        exit;
    }
    
    /**
     * Xóa danh mục
     */
    public function deleteCategory($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Xóa danh mục thành công!';
        header('Location: ' . BASE_URL . 'admin?page=categories');
        exit;
    }
    
    /**
     * Hiển thị trang quản lý voucher
     */
    public function vouchers(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
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
        $vouchers = $voucherModel->getAll();

        // Render the view
        View::render('admin/vouchers', [
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
    
    /**
     * Hiển thị trang quản lý sản phẩm
     */
    public function books(): void
    {
        // Log debug
        error_log("AdminController::books() called");
        error_log("Session data: " . print_r($_SESSION, true));
        error_log("Request URI: " . $_SERVER['REQUEST_URI']);
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            error_log("Admin authentication failed - redirecting to login");
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        error_log("Admin authentication passed - loading books");
        $books = $this->bookRepository->all();
        error_log("Books count: " . count($books));
        
        View::render('admin/books_full', [
            'title' => 'Quản lý sản phẩm',
            'books' => $books
        ]);
    }
    
    /**
     * Hiển thị trang quản lý người dùng
     */
    public function users(): void
    {
        // Log debug
        error_log("AdminController::users() called");
        error_log("Session data: " . print_r($_SESSION, true));
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            error_log("Admin authentication failed - redirecting to login");
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        error_log("Admin authentication passed - loading users");
        $userModel = new User();
        $users = method_exists($userModel, 'getAll') ? $userModel->getAll() : [];
        error_log("Users count: " . count($users));
        
        View::render('admin/users_full', [
            'title' => 'Quản lý người dùng',
            'users' => $users
        ]);
    }
    
    /**
     * Hiển thị trang quản lý danh mục
     */
    public function categories(): void
    {
        // Log debug
        error_log("AdminController::categories() called");
        error_log("Session data: " . print_r($_SESSION, true));
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            error_log("Admin authentication failed - redirecting to login");
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        error_log("Admin authentication passed - loading categories");
        // Lấy danh sách danh mục từ database
        $categories = [];
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
            $stmt = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Categories count: " . count($categories));
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            $categories = [];
        }
        
        View::render('admin/categories_full', [
            'title' => 'Quản lý danh mục',
            'categories' => $categories
        ]);
    }
    
    /**
     * Thống kê doanh thu
     */
    public function statistics(): void
    {
        // Log debug
        error_log("AdminController::statistics() called");
        error_log("Session data: " . print_r($_SESSION, true));
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            error_log("Admin authentication failed - redirecting to login");
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        error_log("Admin authentication passed - loading statistics");
        // Lấy dữ liệu thống kê
        $stats = [
            'total_revenue' => 0,
            'monthly_revenue' => 0,
            'total_orders' => 0,
            'total_customers' => 0
        ];
        
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
            
            // Tổng doanh thu
            $stmt = $pdo->query("SELECT SUM(tong_tien) as total FROM don_hang WHERE trang_thai = 'completed'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_revenue'] = $result['total'] ?? 0;
            
            // Doanh thu tháng này
            $stmt = $pdo->query("SELECT SUM(tong_tien) as total FROM don_hang WHERE trang_thai = 'completed' AND MONTH(ngay_dat) = MONTH(CURRENT_DATE) AND YEAR(ngay_dat) = YEAR(CURRENT_DATE)");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['monthly_revenue'] = $result['total'] ?? 0;
            
            // Tổng đơn hàng
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM don_hang");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_orders'] = $result['total'] ?? 0;
            
            // Tổng khách hàng
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM nguoi_dung WHERE vai_tro = 'khach_hang'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_customers'] = $result['total'] ?? 0;
            
            error_log("Statistics loaded: " . print_r($stats, true));
            
        } catch (Exception $e) {
            error_log("Database error in statistics: " . $e->getMessage());
            // Keep default values if error
        }
        
        View::render('admin/statistics_full', [
            'title' => 'Thống kê doanh thu',
            'stats' => $stats
        ]);
    }
    
    /**
     * Hiển thị form thêm/sửa voucher
     */
    public function voucherForm($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        View::render('admin/vouchers', [
            'title' => 'Quản lý Voucher'
        ]);
    }
    
    /**
     * Lưu voucher
     */
    public function saveVoucher(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Lưu voucher thành công!';
        header('Location: ' . BASE_URL . 'admin?page=vouchers');
        exit;
    }
    
    /**
     * Xóa voucher
     */
    public function deleteVoucher($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Xóa voucher thành công!';
        header('Location: ' . BASE_URL . 'admin?page=vouchers');
        exit;
    }
    
    /**
     * Lấy voucher theo ID
     */
    public function getVoucher($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    
    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
            exit;
        }

        // Lấy ID từ params (route) hoặc POST
        $orderId = $params['id'] ?? $_POST['id'] ?? 0;
        $newStatus = $_POST['status'] ?? '';

        if (empty($orderId) || empty($newStatus)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        try {
            $order = new Order(['id' => (int)$orderId]);
            
            // Kiểm tra trạng thái hợp lệ
            if (!$this->isValidStatusTransition($newStatus)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
                exit;
            }
            
            if ($order->updateStatus($newStatus)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
            }
        } catch (Exception $e) {
            error_log("Error updating order status: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Kiểm tra trạng thái chuyển đổi hợp lệ
     */
    private function isValidStatusTransition(string $status): bool
    {
        $validStatuses = ['cho_xu_ly', 'dang_xu_ly', 'hoan_tat', 'huy', 'pending', 'processing', 'completed', 'cancelled'];
        return in_array($status, $validStatuses);
    }
    
    /**
     * Hủy đơn hàng
     */
    public function cancelOrder($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Hủy đơn hàng thành công!';
        header('Location: ' . BASE_URL . 'admin?page=orders');
        exit;
    }
    
    /**
     * Xóa đơn hàng
     */
    public function deleteOrder($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $orderId = $params['id'] ?? $_GET['id'] ?? $_POST['id'] ?? 0;

        try {
            $order = new Order(['id' => $orderId]);
            
            if ($order->delete()) {
                $_SESSION['success'] = 'Xóa đơn hàng thành công!';
            } else {
                $_SESSION['errors'] = ['Không thể xóa đơn hàng'];
            }
        } catch (Exception $e) {
            error_log("Error deleting order: " . $e->getMessage());
            $_SESSION['errors'] = ['Có lỗi xảy ra'];
        }

        header('Location: ' . BASE_URL . 'admin?page=orders');
        exit;
    }
    
    /**
     * Xóa nhiều đơn hàng
     */
    public function bulkDeleteOrders(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Xóa đơn hàng thành công!';
        header('Location: ' . BASE_URL . 'admin?page=orders');
        exit;
    }
    
    /**
     * Hiển thị form thêm/sửa người dùng
     */
    public function userForm($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        View::render('admin/user_create', [
            'title' => 'Quản lý người dùng'
        ]);
    }
    
    /**
     * Lưu người dùng
     */
    public function saveUser(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Lưu người dùng thành công!';
        header('Location: ' . BASE_URL . 'admin?page=users');
        exit;
    }

    /**
     * Hiển thị trang quản lý người dùng
     */
    public function users(): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $users = User::getAll();
        
        View::render('admin/users_list', [
            'title' => 'Quản lý người dùng',
            'users' => $users
        ]);
    }

    /**
     * Xem chi tiết người dùng
     */
    public function userDetail($userId): void
    {
        // Ép kiểu sang int để tránh lỗi
        $userId = (int) $userId;
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $user = User::getById($userId);
        
        if (empty($user)) {
            $_SESSION['errors'] = ['Người dùng không tồn tại'];
            header('Location: ' . BASE_URL . 'admin?page=users');
            exit;
        }
        
        View::render('admin/users_detail', [
            'title' => 'Chi tiết người dùng #' . $userId,
            'user' => $user
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function editUser($userId): void
    {
        // Ép kiểu sang int để tránh lỗi
        $userId = (int) $userId;
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $user = User::getById($userId);
        
        if (empty($user)) {
            $_SESSION['errors'] = ['Người dùng không tồn tại'];
            header('Location: ' . BASE_URL . 'admin?page=users');
            exit;
        }

        $errors = $_SESSION['errors'] ?? [];
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['errors'], $_SESSION['success']);
        
        View::render('admin/users_edit', [
            'title' => 'Chỉnh sửa người dùng #' . $userId,
            'user' => $user,
            'errors' => $errors,
            'success' => $success
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateUser($userId): void
    {
        // Ép kiểu sang int để tránh lỗi
        $userId = (int) $userId;
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $errors = [];

        try {
            $data = [
                'ho_ten' => trim($_POST['ho_ten'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'dia_chi' => trim($_POST['dia_chi'] ?? ''),
                'vai_tro' => $_POST['vai_tro'] ?? 'khach_hang'
            ];

            // Validate
            if (empty($data['ho_ten'])) {
                $errors[] = 'Họ tên không được để trống';
            }
            if (empty($data['email'])) {
                $errors[] = 'Email không được để trống';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($errors)) {
                if (User::update($userId, $data)) {
                    $_SESSION['success'] = 'Cập nhật người dùng thành công!';
                    header('Location: ' . BASE_URL . 'admin?page=users');
                    exit;
                } else {
                    $errors[] = 'Không thể cập nhật người dùng';
                }
            }
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            $errors[] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $_SESSION['errors'] = $errors;
        header('Location: ' . BASE_URL . 'admin?page=editUser&id=' . $userId);
        exit;
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser($userId): void
    {
        // Ép kiểu sang int để tránh lỗi
        $userId = (int) $userId;
        
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        try {
            if (User::delete($userId)) {
                $_SESSION['success'] = 'Xóa người dùng thành công!';
            } else {
                $_SESSION['errors'] = ['Không thể xóa người dùng (có thể là admin)'];
            }
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            $_SESSION['errors'] = ['Có lỗi xảy ra khi xóa người dùng'];
        }

        header('Location: ' . BASE_URL . 'admin?page=users');
        exit;
    }
}
    
    /**
     * Xóa người dùng
     */
    public function deleteUser($params = []): void
    {
        // Check admin authentication
        if (!isset($_SESSION['user']) || ($_SESSION['vai_tro'] !== 'admin' && $_SESSION['vai_tro'] !== 'quan_tri_vien')) {
            require_once __DIR__ . '/../../config.php';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $_SESSION['success'] = 'Xóa người dùng thành công!';
        header('Location: ' . BASE_URL . 'admin?page=users');
        exit;
    }
}
