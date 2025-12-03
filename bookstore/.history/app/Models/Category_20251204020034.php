<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Database;

class Category
{
    /**
     * Static methods for AdminController compatibility
     */
    public static function getAll(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM danh_muc WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public static function insert(array $data): bool
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO danh_muc (ten_danh_muc) VALUES (?)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['ten_danh_muc']
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE danh_muc SET ten_danh_muc = ? WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['ten_danh_muc'],
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        
        // Kiểm tra xem có sách nào thuộc danh mục này không
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sach WHERE danh_muc_id = ?");
        $stmt->execute([$id]);
        $bookCount = $stmt->fetchColumn();
        
        if ($bookCount > 0) {
            throw new \Exception("Không thể xóa danh mục này vì có {$bookCount} sách thuộc danh mục này");
        }
        
        $stmt = $pdo->prepare("DELETE FROM danh_muc WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function nameExists(string $name, ?int $excludeId = null): bool
    {
        $pdo = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM danh_muc WHERE ten_danh_muc = ?";
        $params = [$name];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function getBookCount(int $categoryId): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sach WHERE danh_muc_id = ?");
        $stmt->execute([$categoryId]);
        return (int)$stmt->fetchColumn();
    }
}
