-- SQL script to add category field to books table
-- Run this in your MySQL database

USE ban_sach;

-- Add category column to books table
ALTER TABLE books 
ADD COLUMN category VARCHAR(100) DEFAULT 'Khác' AFTER author;

-- Update existing books with sample categories
UPDATE books SET category = 'Kỹ năng sống' WHERE id = 1;
UPDATE books SET category = 'Tâm linh' WHERE id = 2;
UPDATE books SET category = 'Phát triển bản thân' WHERE id = 3;

