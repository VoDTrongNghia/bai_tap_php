-- Add missing user_id column to don_hang table so Order model can save records
ALTER TABLE don_hang
    ADD COLUMN user_id INT NULL AFTER id,
    ADD INDEX idx_user_id (user_id);

ALTER TABLE don_hang
    ADD CONSTRAINT fk_don_hang_user
    FOREIGN KEY (user_id) REFERENCES nguoi_dung(id)
    ON DELETE SET NULL;
