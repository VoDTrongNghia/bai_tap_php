<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Order
{
    public int $id;
    public int $userId;
    public string $orderCode;
    public string $customerName;
    public string $customerPhone;
    public string $customerAddress;
    public string $email;
    public string $paymentMethod;
    public float $subtotal;
    public float $discount;
    public float $total;
    public string $status;
    public string $note;
    public string $createdAt;

    /** @var array<string,bool> */
    private static array $tableColumns = [];
    /** @var array<string,bool> */
    private static array $itemTableColumns = [];
    private static ?string $orderItemsTable = null;

    public function __construct(array $data = [])
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->userId = (int)($data['user_id'] ?? $data['userId'] ?? 0);
        $this->orderCode = (string)($data['order_code'] ?? $data['orderCode'] ?? $data['ma_don_hang'] ?? '');
        $this->customerName = (string)($data['customer_name'] ?? $data['customerName'] ?? $data['ten_khach_hang'] ?? '');
        $this->customerPhone = (string)($data['customer_phone'] ?? $data['customerPhone'] ?? $data['so_dien_thoai'] ?? '');
        $this->customerAddress = (string)($data['customer_address'] ?? $data['customerAddress'] ?? $data['dia_chi'] ?? '');
        $this->email = (string)($data['email'] ?? '');
        $this->paymentMethod = (string)($data['payment_method'] ?? $data['paymentMethod'] ?? $data['phuong_thuc_thanh_toan'] ?? '');
        $this->subtotal = (float)($data['subtotal'] ?? $data['tam_tinh'] ?? $data['tong_tien_truoc_giam'] ?? 0);
        $this->discount = (float)($data['discount'] ?? $data['giam_gia'] ?? 0);
        $this->total = (float)($data['total'] ?? $data['tong_tien'] ?? $data['thanh_tien'] ?? 0);
        $this->status = (string)($data['status'] ?? $data['trang_thai'] ?? 'pending');
        $this->note = (string)($data['note'] ?? $data['ghi_chu'] ?? '');
        $this->createdAt = (string)($data['created_at'] ?? $data['createdAt'] ?? $data['ngay_dat'] ?? '');
    }

    public function save(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            if ($this->id > 0) {
                // Update existing order
                $statusColumn = self::resolveColumn(['status', 'trang_thai']);
                $noteColumn = self::resolveColumn(['note', 'ghi_chu']);
                if (!$statusColumn && !$noteColumn) {
                    return true;
                }
                $setParts = [];
                $params = [];
                if ($statusColumn) {
                    $setParts[] = "$statusColumn = ?";
                    $params[] = $this->status;
                }
                if ($noteColumn) {
                    $setParts[] = "$noteColumn = ?";
                    $params[] = $this->note;
                }
                $params[] = $this->id;
                $stmt = $pdo->prepare('UPDATE don_hang SET ' . implode(', ', $setParts) . ' WHERE id = ?');
                return $stmt->execute($params);
            } else {
                // Insert new order (user_id can be 0 for guest orders)
                $columnValueMap = [];
                $columnValueMap[self::resolveColumn(['user_id', 'id_nguoi_dung'])] = $this->userId > 0 ? $this->userId : null;
                $columnValueMap[self::resolveColumn(['order_code', 'ma_don_hang'])] = $this->orderCode;
                $columnValueMap[self::resolveColumn(['customer_name', 'ten_khach_hang'])] = $this->customerName;
                $columnValueMap[self::resolveColumn(['email'])] = $this->email;
                $columnValueMap[self::resolveColumn(['customer_phone', 'so_dien_thoai', 'dien_thoai'])] = $this->customerPhone;
                $columnValueMap[self::resolveColumn(['customer_address', 'dia_chi'])] = $this->customerAddress;
                $columnValueMap[self::resolveColumn(['payment_method', 'phuong_thuc_thanh_toan'])] = $this->paymentMethod;
                $columnValueMap[self::resolveColumn(['subtotal', 'tam_tinh', 'tong_tien_truoc_giam'])] = $this->subtotal;
                $columnValueMap[self::resolveColumn(['discount', 'giam_gia'])] = $this->discount;
                $columnValueMap[self::resolveColumn(['total', 'tong_tien', 'thanh_tien'])] = $this->total;
                $columnValueMap[self::resolveColumn(['status', 'trang_thai'])] = $this->status;
                $columnValueMap[self::resolveColumn(['note', 'ghi_chu'])] = $this->note;
                $createdColumn = self::resolveColumn(['created_at', 'ngay_dat']);
                if ($createdColumn) {
                    $this->createdAt = date('Y-m-d H:i:s');
                    $columnValueMap[$createdColumn] = $this->createdAt;
                }

                $columns = [];
                $placeholders = [];
                $values = [];
                foreach ($columnValueMap as $column => $value) {
                    if ($column === null) {
                        continue;
                    }
                    $columns[] = $column;
                    $placeholders[] = '?';
                    $values[] = $value;
                }

                $sql = 'INSERT INTO don_hang (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute($values);
                
                if ($result) {
                    $this->id = (int)$pdo->lastInsertId();
                }
                
                return $result;
            }
        } catch (\Exception $e) {
            error_log("Error saving order: " . $e->getMessage());
            return false;
        }
    }

    public function addItem(int $bookId, int $quantity, float $price): bool
    {
        if ($this->id <= 0) {
            return false;
        }

        $pdo = Database::getConnection();
        $subtotal = $quantity * $price;

        $columnValueMap = [];
        $columnValueMap[self::resolveItemColumn(['order_id', 'don_hang_id', 'id_don_hang'])] = $this->id;
        $columnValueMap[self::resolveItemColumn(['book_id', 'sach_id', 'id_sach'])] = $bookId;
        $columnValueMap[self::resolveItemColumn(['quantity', 'so_luong'])] = $quantity;
        $columnValueMap[self::resolveItemColumn(['price', 'gia', 'don_gia'])] = $price;
        $columnValueMap[self::resolveItemColumn(['subtotal', 'thanh_tien', 'tong_tien'])] = $subtotal;

        $columns = [];
        $placeholders = [];
        $values = [];

        foreach ($columnValueMap as $column => $value) {
            if ($column === null) {
                continue;
            }
            $columns[] = $column;
            $placeholders[] = '?';
            $values[] = $value;
        }

        if (empty($columns)) {
            return false;
        }

        $table = self::getOrderItemsTable();

        try {
            $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($values);
        } catch (\Exception $e) {
            error_log("Error adding order item: " . $e->getMessage());
            return false;
        }
    }

    public static function findByCode(string $orderCode): ?Order
    {
        $pdo = Database::getConnection();
        $codeColumn = self::resolveColumn(['order_code', 'ma_don_hang']);
        if (!$codeColumn) {
            return null;
        }
        $stmt = $pdo->prepare("SELECT * FROM don_hang WHERE $codeColumn = ? LIMIT 1");
        $stmt->execute([$orderCode]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return new Order($data);
    }

    public static function findByUserId(int $userId): array
    {
        $pdo = Database::getConnection();
        $userColumn = self::resolveColumn(['user_id', 'id_nguoi_dung']);
        if (!$userColumn) {
            return [];
        }
        $createdColumn = self::resolveColumn(['created_at', 'ngay_dat']);
        $orderClause = $createdColumn ? " ORDER BY $createdColumn DESC" : '';
        $stmt = $pdo->prepare("SELECT * FROM don_hang WHERE $userColumn = ?$orderClause");
        $stmt->execute([$userId]);
        $results = $stmt->fetchAll();
        
        $orders = [];
        foreach ($results as $data) {
            $orders[] = new Order($data);
        }
        
        return $orders;
    }

    public static function findByPhoneOrCode(string $phone, string $code): ?Order
    {
        $pdo = Database::getConnection();
        
        if (!empty($code)) {
            return self::findByCode($code);
        }
        
        $phoneColumn = self::resolveColumn(['customer_phone', 'so_dien_thoai', 'dien_thoai']);
        if (!empty($phone) && $phoneColumn) {
            $createdColumn = self::resolveColumn(['created_at', 'ngay_dat']);
            $orderClause = $createdColumn ? " ORDER BY $createdColumn DESC" : '';
            $stmt = $pdo->prepare("SELECT * FROM don_hang WHERE $phoneColumn = ?$orderClause LIMIT 1");
            $stmt->execute([$phone]);
            $data = $stmt->fetch();
            
            if ($data) {
                return new Order($data);
            }
        }
        
        return null;
    }

    private static function resolveItemColumn(array $candidates): ?string
    {
        if (empty(self::$itemTableColumns)) {
            self::loadItemTableColumns();
        }

        foreach ($candidates as $column) {
            if (!empty($column) && isset(self::$itemTableColumns[$column])) {
                return $column;
            }
        }

        if (!empty(self::$itemTableColumns)) {
            return null;
        }

        foreach ($candidates as $column) {
            if (!empty($column)) {
                return $column;
            }
        }

        return null;
    }

    public function getItems(): array
    {
        if ($this->id <= 0) {
            return [];
        }
        
        $pdo = Database::getConnection();
        $table = self::getOrderItemsTable();
        $orderColumn = self::resolveItemColumn(['order_id', 'don_hang_id', 'id_don_hang']);

        if (!$orderColumn) {
            return [];
        }

        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $orderColumn = ?");
        $stmt->execute([$this->id]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        return array_map([self::class, 'normalizeItemRow'], $rows);
    }

    private static function resolveColumn(array $candidates): ?string
    {
        if (empty(self::$tableColumns)) {
            self::loadTableColumns();
        }

        foreach ($candidates as $column) {
            if (!empty($column) && isset(self::$tableColumns[$column])) {
                return $column;
            }
        }

        // If DESCRIBE succeeded but none of the columns exist, return null to skip the assignment
        if (!empty(self::$tableColumns)) {
            return null;
        }

        // Fallback only when we could not load table metadata at all
        foreach ($candidates as $column) {
            if (!empty($column)) {
                return $column;
            }
        }

        return null;
    }

    private static function loadTableColumns(): void
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query('DESCRIBE don_hang');

            if ($stmt) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    if (!empty($row['Field'])) {
                        self::$tableColumns[$row['Field']] = true;
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log('Order::loadTableColumns error: ' . $e->getMessage());
        }
    }

    private static function loadItemTableColumns(): void
    {
        $table = self::getOrderItemsTable();

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query('DESCRIBE ' . $table);

            if ($stmt) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    if (!empty($row['Field'])) {
                        self::$itemTableColumns[$row['Field']] = true;
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log('Order::loadItemTableColumns error: ' . $e->getMessage());
        }
    }

    private static function getOrderItemsTable(): string
    {
        if (self::$orderItemsTable !== null) {
            return self::$orderItemsTable;
        }

        $pdo = Database::getConnection();
        $candidates = ['chi_tiet_don_hang', 'order_items'];

        foreach ($candidates as $table) {
            try {
                $query = "SHOW TABLES LIKE " . $pdo->quote($table);
                $stmt = $pdo->query($query);
                if ($stmt && $stmt->fetchColumn()) {
                    self::$orderItemsTable = $table;
                    return $table;
                }
            } catch (\Throwable $e) {
                // Continue trying other candidates
            }
        }

        self::$orderItemsTable = 'order_items';
        return self::$orderItemsTable;
    }

    /**
     * Normalize order item rows so the rest of the code can rely on book_id/quantity/price/subtotal keys.
     *
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    private static function normalizeItemRow(array $row): array
    {
        $row['book_id'] = (int)($row['book_id'] ?? $row['sach_id'] ?? $row['id_sach'] ?? 0);
        $row['quantity'] = (int)($row['quantity'] ?? $row['so_luong'] ?? 0);
$row['price'] = (float)(
    $row['price']
    ?? $row['gia']
    ?? $row['don_gia']
    ?? 0
);
        $row['subtotal'] = (float)($row['subtotal'] ?? $row['thanh_tien'] ?? $row['tong_tien'] ?? ($row['quantity'] * $row['price']));

        return $row;
    }

    /**
     * Get all orders with user information
     */
    public static function getAll(): array
    {
        $pdo = Database::getConnection();
        
        $query = "SELECT o.*, u.email as user_email, u.ho_ten as user_name
                  FROM don_hang o
                  LEFT JOIN nguoi_dung u ON o.user_id = u.id
                  ORDER BY o.ngay_dat DESC";
        
        $stmt = $pdo->query($query);
        $orders = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
        
        return $orders;
    }

    /**
     * Update order status
     */
    public function updateStatus(string $newStatus): bool
    {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("UPDATE don_hang SET trang_thai = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $this->id]);
    }

    /**
     * Delete order and its items
     */
    public function delete(): bool
    {
        $pdo = Database::getConnection();
        $orderTable = self::getOrderTable();
        $itemsTable = self::getOrderItemsTable();
        
        try {
            $pdo->beginTransaction();
            
            // Delete order items first
            $stmt = $pdo->prepare("DELETE FROM {$itemsTable} WHERE don_hang_id = ?");
            $stmt->execute([$this->id]);
            
            // Delete order
            $stmt = $pdo->prepare("DELETE FROM {$orderTable} WHERE id = ?");
            $result = $stmt->execute([$this->id]);
            
            $pdo->commit();
            return $result;
        } catch (\Exception $e) {
            $pdo->rollback();
            error_log("Error deleting order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order details with items
     */
    public function getDetails(): array
    {
        $pdo = Database::getConnection();
        $orderTable = self::getOrderTable();
        $itemsTable = self::getOrderItemsTable();
        
        // Get order info
        $stmt = $pdo->prepare("SELECT * FROM {$orderTable} WHERE id = ?");
        $stmt->execute([$this->id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return [];
        }
        
        // Get order items
        $stmt = $pdo->prepare("SELECT * FROM {$itemsTable} WHERE don_hang_id = ?");
        $stmt->execute([$this->id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'order' => $order,
            'items' => $items
        ];
    }
}

