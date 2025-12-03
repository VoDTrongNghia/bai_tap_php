<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Voucher
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \App\Database::getConnection();
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM voucher ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM voucher WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO voucher (ma_voucher, mo_ta, giam_gia, kieu_giam, gia_tri_toi_thieu, 
                so_luong, da_su_dung, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['ma_voucher'],
            $data['mo_ta'] ?? '',
            $data['giam_gia'],
            $data['kieu_giam'],
            $data['gia_tri_toi_thieu'] ?? 0,
            $data['so_luong'] ?? 1,
            $data['ngay_bat_dau'],
            $data['ngay_ket_thuc'],
            $data['trang_thai'] ?? 1
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE voucher SET 
                ma_voucher = ?,
                mo_ta = ?,
                giam_gia = ?,
                kieu_giam = ?,
                gia_tri_toi_thieu = ?,
                so_luong = ?,
                ngay_bat_dau = ?,
                ngay_ket_thuc = ?,
                trang_thai = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['ma_voucher'],
            $data['mo_ta'] ?? '',
            $data['giam_gia'],
            $data['kieu_giam'],
            $data['gia_tri_toi_thieu'] ?? 0,
            $data['so_luong'] ?? 1,
            $data['ngay_bat_dau'],
            $data['ngay_ket_thuc'],
            $data['trang_thai'] ?? 1,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM voucher WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM voucher WHERE ma_voucher = ?";
        $params = [$code];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getActiveVouchers(): array
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("
            SELECT * FROM voucher 
            WHERE trang_thai = 1 
            AND ngay_bat_dau <= ? 
            AND ngay_ket_thuc >= ? 
            AND (so_luong - da_su_dung) > 0
            ORDER BY created_at DESC
        ");
        $stmt->execute([$now, $now]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function useVoucher(string $code, int $userId): bool
    {
        $this->pdo->beginTransaction();
        
        try {
            // Get and lock the voucher
            $stmt = $this->pdo->prepare("
                SELECT * FROM voucher 
                WHERE ma_voucher = ? 
                AND trang_thai = 1 
                AND ngay_bat_dau <= NOW() 
                AND ngay_ket_thuc >= NOW() 
                AND (so_luong - da_su_dung) > 0
                FOR UPDATE
            ");
            $stmt->execute([$code]);
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$voucher) {
                throw new \Exception('Voucher không hợp lệ hoặc đã hết hạn');
            }
            
            // Check if user has already used this voucher
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM don_hang 
                WHERE user_id = ? AND ma_voucher = ?
            ");
            $stmt->execute([$userId, $voucher['id']]);
            $usedCount = $stmt->fetchColumn();
            
            if ($usedCount > 0) {
                throw new \Exception('Bạn đã sử dụng voucher này rồi');
            }
            
            // Update voucher usage
            $stmt = $this->pdo->prepare("
                UPDATE voucher 
                SET da_su_dung = da_su_dung + 1 
                WHERE id = ? AND (so_luong - da_su_dung) > 0
            ");
            
            if (!$stmt->execute([$voucher['id']]) || $stmt->rowCount() === 0) {
                throw new \Exception('Không thể sử dụng voucher này');
            }
            
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}