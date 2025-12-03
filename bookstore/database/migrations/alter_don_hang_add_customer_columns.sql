-- Add customer/payment columns required by the PHP models
ALTER TABLE don_hang
    ADD COLUMN IF NOT EXISTS customer_name VARCHAR(255) NOT NULL AFTER order_code,
    ADD COLUMN IF NOT EXISTS customer_phone VARCHAR(20) NOT NULL AFTER customer_name,
    ADD COLUMN IF NOT EXISTS customer_address TEXT NOT NULL AFTER customer_phone,
    ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) NOT NULL DEFAULT 'cod' AFTER customer_address,
    ADD COLUMN IF NOT EXISTS subtotal DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER payment_method,
    ADD COLUMN IF NOT EXISTS discount DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER subtotal,
    ADD COLUMN IF NOT EXISTS total DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER discount,
    ADD COLUMN IF NOT EXISTS status VARCHAR(50) NOT NULL DEFAULT 'pending' AFTER total;
