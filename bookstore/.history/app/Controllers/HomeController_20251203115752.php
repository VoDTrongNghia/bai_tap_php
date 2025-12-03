<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Repositories\BookRepository;
// use App\Repositories\NewsRepository; // Nếu sau này bạn có NewsRepository

class HomeController
{
    private BookRepository $bookRepository;
    // private NewsRepository $newsRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
        // $this->newsRepository = new NewsRepository();
    }

    public function index(): void
    {
        // Lấy dữ liệu từ repository
        $bestSellingBooks = $this->bookRepository->getBestSellingBooks(); 
        $newBooks = $this->bookRepository->getNewBooks(); 
        $discountBooks = $this->bookRepository->getDiscountBooks(); 
        
        // Sample news data (temporary solution until NewsRepository is created)
        $news = [
            (object)[
                'id' => 1,
                'title' => 'Ra mắt bộ sưu tập sách văn học mới',
                'excerpt' => 'Bộ sưu tập gồm 50 tác phẩm kinh điển của văn học Việt Nam và thế giới',
                'image' => 'news1.jpg',
                'createdAt' => date('Y-m-d', strtotime('-3 days'))
            ],
            (object)[
                'id' => 2,
                'title' => 'Khuyến mãi đặc biệt tháng 12',
                'excerpt' => 'Giảm giá đến 50% cho các sách bestseller trong tháng 12',
                'image' => 'news2.jpg',
                'createdAt' => date('Y-m-d', strtotime('-5 days'))
            ],
            (object)[
                'id' => 3,
                'title' => 'Tác giả nổi tiếng gặp gỡ độc giả',
                'excerpt' => 'Sự kiện ký sách và giao lưu với tác giả Nguyễn Nhật Ánh',
                'image' => 'news3.jpg',
                'createdAt' => date('Y-m-d', strtotime('-1 week'))
            ]
        ];

        // Gọi View hiển thị trang chủ
        View::render('pages/home', [
            'title' => 'Trang chủ',
            'bestSellingBooks' => $bestSellingBooks,
            'newBooks' => $newBooks,
            'discountBooks' => $discountBooks,
            'news' => $news,
        ]);
    }
}
