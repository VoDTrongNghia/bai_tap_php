<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Book;
use App\Database;

class BookRepository
{
    public function getBestSellingBooks(): array
    {
        try {
            $pdo = Database::getConnection();
            // Get books with different criteria to avoid duplication
            $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC LIMIT 4 OFFSET 0");
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getNewBooks(): array
    {
        try {
            $pdo = Database::getConnection();
            // Get newest books with different offset to avoid duplication
            $stmt = $pdo->query("SELECT * FROM sach ORDER BY id DESC LIMIT 4 OFFSET 4");
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getDiscountBooks(): array
    {
        try {
            $pdo = Database::getConnection();
            // Get books with discount (discount_percentage > 0)
            $stmt = $pdo->query("SELECT * FROM sach WHERE phan_tram_giam > 0 ORDER BY phan_tram_giam DESC, id DESC LIMIT 4");
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function all(): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT * FROM sach ORDER BY id DESC");
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            // Return empty array if database error or table doesn't exist
            return [];
        }
    }

    public function findById(string $id): ?Book
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM sach WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if (!$data) {
                return null;
            }
            
            return new Book($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        
        try {
            $pdo = Database::getConnection();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT * FROM sach WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function create(array $data): ?Book
    {
        $book = new Book($data);
        if ($book->save()) {
            return $book;
        }
        return null;
    }

    public function delete(string $id): bool
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("DELETE FROM sach WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Search books by title
     */
    public function searchByTitle(string $keyword): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM sach WHERE ten_sach LIKE ? ORDER BY id DESC");
            $searchTerm = '%' . $keyword . '%';
            $stmt->execute([$searchTerm]);
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Filter books by category
     */
    public function filterByCategory(string $category): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM sach WHERE id_danh_muc = ? ORDER BY id DESC");
            $stmt->execute([$category]);
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all unique categories
     */
    public function getAllCategories(): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT DISTINCT id_danh_muc FROM sach ORDER BY id_danh_muc");
            $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            return $results ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Search and filter books (combined)
     */
    public function searchAndFilter(?string $keyword = null, ?string $category = null): array
    {
        try {
            $pdo = Database::getConnection();
            $conditions = [];
            $params = [];

            if (!empty($keyword)) {
                $conditions[] = "ten_sach LIKE ?";
                $params[] = '%' . $keyword . '%';
            }

            if (!empty($category)) {
                $conditions[] = "id_danh_muc = ?";
                $params[] = $category;
            }

            $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
            $sql = "SELECT * FROM sach $whereClause ORDER BY id DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            
            $books = [];
            foreach ($results as $row) {
                $books[] = new Book($row);
            }
            return $books;
        } catch (\Exception $e) {
            return [];
        }
    }
}
