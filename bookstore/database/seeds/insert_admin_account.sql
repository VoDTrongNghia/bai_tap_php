-- SQL script to insert default admin account
-- Run this in your MySQL database

USE ban_sach;

-- Insert admin account
-- Username: admin
-- Password: 123456 (hashed with password_hash)
-- Role: admin

-- Check if admin account already exists
-- If exists, update it; if not, insert new one

INSERT INTO nguoi_dung (
    ten_dang_nhap,
    ho_ten,
    email,
    mat_khau,
    so_dien_thoai,
    dia_chi,
    vai_tro,
    ngay_tao
) VALUES (
    'admin',
    'Administrator',
    'admin@bookstore.com',
    '$2y$10$mi265tc4nZZuO2xz5P1TIe8lSHP6xkEQu0cMWM9niMwa4xKtHSvKS', -- password: 123456
    '',
    '',
    'admin',
    NOW()
)
ON DUPLICATE KEY UPDATE
    ho_ten = 'Administrator',
    email = 'admin@bookstore.com',
    mat_khau = '$2y$10$mi265tc4nZZuO2xz5P1TIe8lSHP6xkEQu0cMWM9niMwa4xKtHSvKS', -- password: 123456
    vai_tro = 'admin';

-- Note: The password hash above is for password "123456"
-- If you want to generate a new hash, use PHP:
-- echo password_hash('123456', PASSWORD_DEFAULT);

