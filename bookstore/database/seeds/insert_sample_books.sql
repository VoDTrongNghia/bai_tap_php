-- SQL script to insert sample books
-- Run this in your MySQL database
-- Database: ban_sach

USE ban_sach;

-- Make sure the books table exists with the following structure:
-- CREATE TABLE IF NOT EXISTS books (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     title VARCHAR(255) NOT NULL,
--     author VARCHAR(255) NOT NULL,
--     description TEXT,
--     price DECIMAL(10, 2) NOT NULL,
--     cover_image VARCHAR(255),
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- );

-- Insert sample books
INSERT INTO books (title, author, description, price, cover_image) VALUES
(
    'Đắc Nhân Tâm',
    'Dale Carnegie',
    'Đắc Nhân Tâm (How to Win Friends and Influence People) là cuốn sách nổi tiếng nhất của Dale Carnegie. Cuốn sách đã được dịch sang hầu hết các ngôn ngữ trên thế giới và có mặt ở hàng trăm quốc gia. Đây là cuốn sách liên tục đứng đầu danh mục sách bán chạy nhất do thời báo New York Times bình chọn suốt 10 năm liền.',
    89000,
    'datnhantam.jpg'
),
(
    'Muôn Kiếp Nhân Sinh',
    'Nguyễn Phong',
    'Muôn Kiếp Nhân Sinh là cuốn sách tâm linh nổi tiếng của tác giả Nguyễn Phong. Cuốn sách kể về những kiếp sống luân hồi, những bài học về nhân quả và nghiệp báo. Đây là một hành trình khám phá về ý nghĩa cuộc sống và mối liên hệ giữa các kiếp người.',
    120000,
    'muonkiepnhansinh.jpg'
),
(
    'Tuổi Trẻ Đáng Giá Bao Nhiêu?',
    'Rosie Nguyễn',
    'Tuổi Trẻ Đáng Giá Bao Nhiêu? là cuốn sách truyền cảm hứng cho giới trẻ của tác giả Rosie Nguyễn. Cuốn sách chia sẻ những trải nghiệm, suy nghĩ và bài học về tuổi trẻ, về việc sống sao cho đáng giá, về việc theo đuổi đam mê và không ngừng học hỏi.',
    95000,
    'tuoitredanggiabaonhieu.jpg'
);

