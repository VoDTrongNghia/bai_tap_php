-- SQL script to create nguoi_dung table
-- Run this in your MySQL database

USE ban_sach;

-- Create nguoi_dung table if it doesn't exist
CREATE TABLE IF NOT EXISTS nguoi_dung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_dang_nhap VARCHAR(100) UNIQUE NOT NULL,
    ho_ten VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mat_khau VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20) DEFAULT NULL,
    dia_chi TEXT DEFAULT NULL,
    vai_tro VARCHAR(50) DEFAULT 'khach_hang',
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ten_dang_nhap (ten_dang_nhap),
    INDEX idx_email (email),
    INDEX idx_vai_tro (vai_tro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Note: If your table uses ma_nguoi_dung instead of id, you can use:
-- CREATE TABLE IF NOT EXISTS nguoi_dung (
--     ma_nguoi_dung INT AUTO_INCREMENT PRIMARY KEY,
--     ten_dang_nhap VARCHAR(100) UNIQUE NOT NULL,
--     ho_ten VARCHAR(255) NOT NULL,
--     email VARCHAR(255) UNIQUE NOT NULL,
--     mat_khau VARCHAR(255) NOT NULL,
--     so_dien_thoai VARCHAR(20) DEFAULT NULL,
--     dia_chi TEXT DEFAULT NULL,
--     vai_tro VARCHAR(50) DEFAULT 'khach_hang',
--     ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     INDEX idx_ten_dang_nhap (ten_dang_nhap),
--     INDEX idx_email (email),
--     INDEX idx_vai_tro (vai_tro)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

