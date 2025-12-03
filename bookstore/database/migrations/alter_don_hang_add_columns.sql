-- Ensure don_hang table has all columns the application requires
ALTER TABLE don_hang
    ADD COLUMN IF NOT EXISTS note TEXT NULL,
    ADD COLUMN IF NOT EXISTS order_code VARCHAR(50) NOT NULL,
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ADD INDEX IF NOT EXISTS idx_order_code (order_code),
    ADD INDEX IF NOT EXISTS idx_don_hang_created_at (created_at);

-- Some MySQL versions (older than 8.0.21) do not support multiple ADD statements with IF NOT EXISTS.
-- If your MySQL version does not support IF NOT EXISTS, comment out the block above and run the statements individually.
