<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;
use App\Repositories\BookRepository;

class BooksController
{
    private BookRepository $books;

    public function __construct()
    {
        $this->books = new BookRepository();
    }

    public function index(): void
    {
        $keyword = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $type = $_GET['type'] ?? ''; // bestselling, discount, new
        
        // Get all categories for filter dropdown
        $categories = $this->books->getAllCategories();
        
        // Get books based on search, filter and type
        if (!empty($keyword) || !empty($category)) {
            $all = $this->books->searchAndFilter(
                !empty($keyword) ? $keyword : null,
                !empty($category) ? $category : null
            );
        } elseif (!empty($type)) {
            // Filter by type (bestselling, discount, new)
            switch ($type) {
                case 'bestselling':
                    $all = $this->books->getBestSellingBooks(100); // Get more books for "view all"
                    break;
                case 'discount':
                    $all = $this->books->getDiscountBooks(100);
                    break;
                case 'new':
                    $all = $this->books->getNewBooks(100);
                    break;
                default:
                    $all = $this->books->all();
            }
        } else {
            $all = $this->books->all();
        }
        
        // Set page title based on type
        $pageTitle = 'Sách';
        switch ($type) {
            case 'bestselling':
                $pageTitle = 'Sách bán chạy';
                break;
            case 'discount':
                $pageTitle = 'Sách đang giảm giá';
                break;
            case 'new':
                $pageTitle = 'Sách mới phát hành';
                break;
        }
        
        View::render('books/index', [
            'title' => $pageTitle,
            'books' => $all,
            'categories' => $categories,
            'currentCategory' => $category,
            'currentKeyword' => $keyword,
            'currentType' => $type,
        ]);
    }

    /**
     * @param array{id?:string} $params
     */
    public function show(array $params): void
    {
        $id = (string)($params['id'] ?? '');
        $book = $this->books->findById($id);
        if (!$book) {
            http_response_code(404);
            echo 'Không tìm thấy sách';
            return;
        }
        View::render('books/show', [
            'title' => $book->title,
            'book' => $book,
        ]);
    }
}


