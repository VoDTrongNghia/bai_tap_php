<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;

class NewsController
{
    public function index(): void
    {
        // Sample news data
        $news = [
            [
                'id' => 1,
                'title' => 'Ra mắt bộ sưu tập sách văn học mới',
                'excerpt' => 'Bộ sưu tập gồm 50 tác phẩm kinh điển của văn học Việt Nam và thế giới, được tái bản với bìa mới và nội dung bổ sung.',
                'content' => 'Nhà sách chúng tôi tự hào giới thiệu bộ sưu tập sách văn học mới nhất, bao gồm các tác phẩm kinh điển từ văn học Việt Nam như "Số Đỏ", "Chí Phèo", "Nhà Giả Kim" đến các tác phẩm văn học thế giới nổi tiếng.',
                'image' => 'news1.jpg',
                'date' => '2024-12-01',
                'author' => 'Admin',
                'category' => 'Sách mới'
            ],
            [
                'id' => 2,
                'title' => 'Khuyến mãi lớn nhân dịp Quốc Khánh 2/9',
                'excerpt' => 'Giảm giá đến 50% cho tất cả các đầu sách Việt Nam và tặng voucher mua sắm cho khách hàng thân thiết.',
                'content' => 'Nhân dịp kỷ niệm Quốc Khánh 2/9, nhà sách triển khai chương trình khuyến mãi đặc biệt với ưu đãi hấp dẫn cho tất cả độc giả.',
                'image' => 'news2.jpg',
                'date' => '2024-08-30',
                'author' => 'Marketing Team',
                'category' => 'Khuyến mãi'
            ],
            [
                'id' => 3,
                'title' => 'Workshop kỹ năng đọc sách hiệu quả',
                'excerpt' => 'Đăng ký tham gia workshop miễn phí về phương pháp đọc sách hiệu quả và ghi chép thông minh.',
                'content' => 'Chương trình workshop được tổ chức nhằm giúp độc giả phát triển kỹ năng đọc sách hiệu quả, chọn sách phù hợp và ghi chép thông minh.',
                'image' => 'news3.jpg',
                'date' => '2024-09-15',
                'author' => 'Education Team',
                'category' => 'Sự kiện'
            ],
            [
                'id' => 4,
                'title' => 'Tác giả Nguyễn Nhật Ánh gặp gỡ độc giả',
                'excerpt' => 'Sự kiện ký sách và giao lưu cùng tác giả Nguyễn Nhật Ánh vào ngày 20/10 tại nhà sách.',
                'content' => 'Nhà sách vinh dự đón tác giả Nguyễn Nhật Ánh trong sự kiện giao lưu và ký sách cho các tác phẩm mới nhất của ông.',
                'image' => 'news4.jpg',
                'date' => '2024-10-15',
                'author' => 'Event Team',
                'category' => 'Sự kiện'
            ]
        ];

        View::render('news/index', [
            'title' => 'Tin tức & Sự kiện',
            'news' => $news
        ]);
    }

    public function detail(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        // Sample news detail (in real app, this would come from database)
        $newsItem = [
            'id' => $id,
            'title' => 'Ra mắt bộ sưu tập sách văn học mới',
            'excerpt' => 'Bộ sưu tập gồm 50 tác phẩm kinh điển của văn học Việt Nam và thế giới, được tái bản với bìa mới và nội dung bổ sung.',
            'content' => '<p>Nhà sách chúng tôi tự hào giới thiệu bộ sưu tập sách văn học mới nhất, bao gồm các tác phẩm kinh điển từ văn học Việt Nam như "Số Đỏ", "Chí Phèo", "Nhà Giả Kim" đến các tác phẩm văn học thế giới nổi tiếng.</p>
            
            <h3>Điểm nổi bật của bộ sưu tập:</h3>
            <ul>
                <li>Bìa mới thiết kế hiện đại, sang trọng</li>
                <li>Giấy chất lượng cao, in sắc nét</li>
                <li>Nội dung được biên tập kỹ lưỡng, bổ sung chú thích</li>
                <li>Giá ưu đãi đặc biệt trong tháng ra mắt</li>
            </ul>
            
            <h3>Danh sách các tác phẩm:</h3>
            <p>Bộ sưu tập bao gồm 50 tác phẩm kinh điển được chia thành 3 chủ đề chính:</p>
            <ul>
                <li>Văn học Việt Nam: 20 tác phẩm</li>
                <li>Văn học kinh điển thế giới: 20 tác phẩm</li>
                <li>Văn học hiện đại: 10 tác phẩm</li>
            </ul>',
            'image' => 'news1.jpg',
            'date' => '2024-12-01',
            'author' => 'Admin',
            'category' => 'Sách mới',
            'views' => 1250,
            'likes' => 89
        ];

        // Related news
        $relatedNews = [
            ['id' => 2, 'title' => 'Khuyến mãi lớn nhân dịp Quốc Khánh 2/9', 'date' => '2024-08-30'],
            ['id' => 3, 'title' => 'Workshop kỹ năng đọc sách hiệu quả', 'date' => '2024-09-15']
        ];

        View::render('news/detail', [
            'title' => $newsItem['title'],
            'newsItem' => $newsItem,
            'relatedNews' => $relatedNews
        ]);
    }
}
