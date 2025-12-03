<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Repositories\BookRepository;

class SearchController
{
    private BookRepository $books;

    public function __construct()
    {
        $this->books = new BookRepository();
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $books = [];
        
        if (!empty($keyword)) {
            $books = $this->books->searchByTitle($keyword);
        }
        
        View::render('search/index', [
            'title' => 'Tìm kiếm',
            'books' => $books,
            'keyword' => $keyword,
            'count' => count($books),
        ]);
    }
}

