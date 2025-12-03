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
        
        // Get all categories for filter dropdown
        $categories = $this->books->getAllCategories();
        
        // Get books based on search and filter
        if (!empty($keyword) || !empty($category)) {
            $all = $this->books->searchAndFilter(
                !empty($keyword) ? $keyword : null,
                !empty($category) ? $category : null
            );
        } else {
            $all = $this->books->all();
        }
        
        View::render('books/index', [
            'title' => 'Sách',
            'books' => $all,
            'categories' => $categories,
            'currentCategory' => $category,
            'currentKeyword' => $keyword,
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


