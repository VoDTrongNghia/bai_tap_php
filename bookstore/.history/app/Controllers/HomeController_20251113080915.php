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
        // $news = $this->newsRepository->getLatestNews(); 

        // Gọi View hiển thị trang chủ
        View::render('pages/home', [
            'title' => 'Trang chủ',
            'bestSellingBooks' => $bestSellingBooks,
            'newBooks' => $newBooks,
            'discountBooks' => $discountBooks,
            // 'news' => $news,
        ]);
    }
}
