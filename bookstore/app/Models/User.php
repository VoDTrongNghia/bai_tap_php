<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use App\Helpers\Logger;

class User
{
    private int $id;
    private string $tenDangNhap;
    private string $hoTen;
    private string $email;
    private string $matKhau;
    private string $soDienThoai;
    private string $diaChi;
    private string $vaiTro;
    private string $ngayTao;

    public function __construct(array $data = [])
    {
        $this->id = (int)($data['id'] ?? $data['ma_nguoi_dung'] ?? 0);
        $this->tenDangNhap = (string)($data['ten_dang_nhap'] ?? '');
        $this->hoTen = (string)($data['ho_ten'] ?? '');
        $this->email = (string)($data['email'] ?? '');
        $this->matKhau = (string)($data['mat_khau'] ?? '');
        $this->soDienThoai = (string)($data['so_dien_thoai'] ?? '');
        $this->diaChi = (string)($data['dia_chi'] ?? '');
        $this->vaiTro = (string)($data['vai_tro'] ?? 'khach_hang');
        $this->ngayTao = (string)($data['ngay_tao'] ?? '');
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getTenDangNhap(): string { return $this->tenDangNhap; }
    public function getHoTen(): string { return $this->hoTen; }
    public function getEmail(): string { return $this->email; }
    public function getMatKhau(): string { return $this->matKhau; }
    public function getSoDienThoai(): string { return $this->soDienThoai; }
    public function getDiaChi(): string { return $this->diaChi; }
    public function getVaiTro(): string { return $this->vaiTro; }
    public function getNgayTao(): string { return $this->ngayTao; }

    // Setters
    public function setTenDangNhap(string $tenDangNhap): void { $this->tenDangNhap = $tenDangNhap; }
    public function setHoTen(string $hoTen): void { $this->hoTen = $hoTen; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setMatKhau(string $matKhau): void { $this->matKhau = $matKhau; }
    public function setSoDienThoai(string $soDienThoai): void { $this->soDienThoai = $soDienThoai; }
    public function setDiaChi(string $diaChi): void { $this->diaChi = $diaChi; }
    public function setVaiTro(string $vaiTro): void { $this->vaiTro = $vaiTro; }

    // Static methods for database operations
    public static function findByEmail(string $email): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return new User($data);
    }

    public static function findByTenDangNhap(string $tenDangNhap): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ?");
        $stmt->execute([$tenDangNhap]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return new User($data);
    }

    public static function findByTenDangNhapOrEmail(string $usernameOrEmail): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return new User($data);
    }

    public static function findById(int $id): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return new User($data);
    }

    public function save(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            if ($this->id > 0) {
                // Update existing user
                $stmt = $pdo->prepare("
                    UPDATE nguoi_dung 
                    SET ten_dang_nhap = ?, ho_ten = ?, email = ?, mat_khau = ?, 
                        so_dien_thoai = ?, dia_chi = ?, vai_tro = ? 
                    WHERE id = ?
                ");
                return $stmt->execute([
                    $this->tenDangNhap,
                    $this->hoTen,
                    $this->email,
                    $this->matKhau,
                    $this->soDienThoai,
                    $this->diaChi,
                    $this->vaiTro,
                    $this->id
                ]);
            } else {
                // Insert new user - Đảm bảo KHÔNG truyền id để MySQL tự động tạo
                // Kiểm tra lại id trước khi insert
                if ($this->id > 0) {
                    Logger::debug("User already exists, updating", [
                        'id' => $this->id,
                        'ten_dang_nhap' => $this->tenDangNhap,
                        'email' => $this->email
                    ]);
                }
                
                // Insert với danh sách cột rõ ràng, KHÔNG include id
                $stmt = $pdo->prepare("
                    INSERT INTO nguoi_dung 
                    (ten_dang_nhap, ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro, ngay_tao) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                
                $result = $stmt->execute([
                    $this->tenDangNhap,
                    $this->hoTen,
                    $this->email,
                    $this->matKhau,
                    $this->soDienThoai,
                    $this->diaChi,
                    $this->vaiTro
                ]);
                
                if ($result) {
                    $lastInsertId = $pdo->lastInsertId();
                    
                    if ($lastInsertId > 0) {
                        $this->id = (int)$lastInsertId;
                        Logger::debug("User inserted successfully", [
                            'new_id' => $this->id,
                            'ten_dang_nhap' => $this->tenDangNhap
                        ]);
                    } else {
                        // Nếu lastInsertId = 0, có vấn đề nghiêm trọng với AUTO_INCREMENT
                        Logger::dbError("CRITICAL: lastInsertId returned 0 after successful insert", [
                            'ten_dang_nhap' => $this->tenDangNhap,
                            'email' => $this->email,
                            'message' => 'AUTO_INCREMENT may be broken. Run fix_database_completely.php'
                        ]);
                        
                        // Thử query lại để lấy id
                        $checkStmt = $pdo->prepare("SELECT id FROM nguoi_dung WHERE ten_dang_nhap = ? AND email = ? ORDER BY id DESC LIMIT 1");
                        $checkStmt->execute([$this->tenDangNhap, $this->email]);
                        $checkResult = $checkStmt->fetch();
                        
                        if ($checkResult && isset($checkResult['id']) && $checkResult['id'] > 0) {
                            $this->id = (int)$checkResult['id'];
                            Logger::info("Recovered user id from database", [
                                'recovered_id' => $this->id,
                                'ten_dang_nhap' => $this->tenDangNhap
                            ]);
                        } else {
                            // Không thể recover id, nhưng insert đã thành công
                            Logger::error("Cannot recover user id after insert", [
                                'ten_dang_nhap' => $this->tenDangNhap,
                                'email' => $this->email
                            ]);
                        }
                    }
                }
                
                return $result;
            }
        } catch (\PDOException $e) {
            // Log detailed error using Logger
            Logger::dbError("Error saving user to database", [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'user_data' => [
                    'ten_dang_nhap' => $this->tenDangNhap,
                    'ho_ten' => $this->hoTen,
                    'email' => $this->email,
                    'vai_tro' => $this->vaiTro,
                    'has_id' => $this->id > 0
                ],
                'sql_state' => $e->getCode()
            ]);
            
            // Store error in session for display (if session is available)
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['db_error'] = $e->getMessage();
            }
            
            return false;
        } catch (\Exception $e) {
            Logger::error("Unexpected error saving user", [
                'error_message' => $e->getMessage(),
                'user_data' => [
                    'ten_dang_nhap' => $this->tenDangNhap,
                    'email' => $this->email
                ]
            ]);
            
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['db_error'] = $e->getMessage();
            }
            return false;
        }
    }

    public function updateAddress(string $phone, string $address): bool
    {
        if ($this->id <= 0) {
            return false;
        }
        
        $pdo = Database::getConnection();
        try {
            $stmt = $pdo->prepare("UPDATE nguoi_dung SET so_dien_thoai = ?, dia_chi = ? WHERE id = ?");
            $result = $stmt->execute([$phone, $address, $this->id]);
            if ($result) {
                $this->soDienThoai = $phone;
                $this->diaChi = $address;
            }
            return $result;
        } catch (\Exception $e) {
            error_log("Error updating address: " . $e->getMessage());
            return false;
        }
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->matKhau);
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ma_nguoi_dung' => $this->id,
            'ten_dang_nhap' => $this->tenDangNhap,
            'ho_ten' => $this->hoTen,
            'name' => $this->hoTen, // Alias for compatibility
            'email' => $this->email,
            'so_dien_thoai' => $this->soDienThoai,
            'phone' => $this->soDienThoai, // Alias for compatibility
            'dia_chi' => $this->diaChi,
            'address' => $this->diaChi, // Alias for compatibility
            'vai_tro' => $this->vaiTro,
            'ngay_tao' => $this->ngayTao,
        ];
    }

    /**
     * Get all users
     */
    public static function getAll(): array
    {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->query("SELECT * FROM nguoi_dung ORDER BY ngay_tao DESC");
        $users = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
        
        return $users;
    }

    /**
     * Get user by ID
     */
    public static function getById(int $userId): array
    {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->execute([$userId]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Update user information
     */
    public static function update(int $userId, array $data): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $fields = [];
            $values = [];
            
            // Allowed fields to update
            $allowedFields = ['ho_ten', 'email', 'so_dien_thoai', 'dia_chi', 'vai_tro'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $userId;
            
            $sql = "UPDATE nguoi_dung SET " . implode(', ', $fields) . ", ngay_cap_nhat = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute($values);
        } catch (\Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete user
     */
    public static function delete(int $userId): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM nguoi_dung WHERE id = ? AND vai_tro != 'quan_tri_vien'");
            return $stmt->execute([$userId]);
        } catch (\Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public static function checkExists(string $tenDangNhap, string $email): array
    {
        $pdo = Database::getConnection();
        
        // Check ten_dang_nhap separately
        $tenDangNhapExists = false;
        if (!empty($tenDangNhap)) {
            $stmt = $pdo->prepare("SELECT id FROM nguoi_dung WHERE ten_dang_nhap = ? LIMIT 1");
            $stmt->execute([$tenDangNhap]);
            $tenDangNhapExists = (bool)$stmt->fetch();
        }
        
        // Check email separately
        $emailExists = false;
        if (!empty($email)) {
            $stmt = $pdo->prepare("SELECT id FROM nguoi_dung WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $emailExists = (bool)$stmt->fetch();
        }
        
        return [
            'exists' => $tenDangNhapExists || $emailExists,
            'ten_dang_nhap_exists' => $tenDangNhapExists,
            'email_exists' => $emailExists
        ];
    }
}
