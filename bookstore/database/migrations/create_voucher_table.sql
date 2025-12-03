-- Create the voucher table
CREATE TABLE IF NOT EXISTS `voucher` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma_voucher` VARCHAR(50) NOT NULL UNIQUE,
    `mo_ta` TEXT,
    `giam_gia` DECIMAL(10,2) NOT NULL,
    `kieu_giam` ENUM('percent', 'fixed') NOT NULL DEFAULT 'percent',
    `gia_tri_toi_thieu` DECIMAL(10,2) DEFAULT 0,
    `so_luong` INT NOT NULL DEFAULT 0,
    `ngay_bat_dau` DATETIME NOT NULL,
    `ngay_ket_thuc` DATETIME NOT NULL,
    `trang_thai` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_ma_voucher` (`ma_voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add some sample data
INSERT INTO `voucher` (`ma_voucher`, `mo_ta`, `giam_gia`, `kieu_giam`, `gia_tri_toi_thieu`, `so_luong`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai`) VALUES
('WELCOME10', 'Giảm 10% cho đơn hàng đầu tiên', 10.00, 'percent', 100000.00, 100, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1),
('FREESHIP50', 'Miễn phí vận chuyển cho đơn từ 500K', 50000.00, 'fixed', 500000.00, 50, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1),
('SALE20', 'Giảm 20% cho tất cả đơn hàng', 20.00, 'percent', 200000.00, 200, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 1);
