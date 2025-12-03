<?php
require_once __DIR__ . '/config.php';

// Start session
session_start();

// Simulate admin login
$_SESSION['vai_tro'] = 'quan_tri_vien';
$_SESSION['user'] = [
    'id' => 1,
    'ten_dang_nhap' => 'admin',
    'vai_tro' => 'quan_tri_vien'
];

echo "=== ADMIN ACCESS TEST ===\n\n";
echo "Session role: " . $_SESSION['vai_tro'] . "\n";
echo "BASE_URL: " . BASE_URL . "\n\n";

// Test BookRepository
try {
    require_once __DIR__ . '/app/Repositories/BookRepository.php';
    $bookRepo = new \App\Repositories\BookRepository();
    
    echo "=== BookRepository Tests ===\n";
    
    // Test all()
    $books = $bookRepo->all();
    echo "all() method: " . count($books) . " books found\n";
    
    if (!empty($books)) {
        $firstBook = $books[0];
        echo "First book: " . $firstBook->getTitle() . "\n";
        echo "Author: " . $firstBook->getAuthor() . "\n";
        echo "Price: " . $firstBook->getPrice() . "\n";
        echo "Image: " . $firstBook->getImageUrl() . "\n";
    }
    
    // Test getBestSellingBooks()
    $bestSelling = $bookRepo->getBestSellingBooks();
    echo "getBestSellingBooks(): " . count($bestSelling) . " books\n";
    
    // Test getNewBooks()
    $newBooks = $bookRepo->getNewBooks();
    echo "getNewBooks(): " . count($newBooks) . " books\n";
    
    // Test getDiscountBooks()
    $discountBooks = $bookRepo->getDiscountBooks();
    echo "getDiscountBooks(): " . count($discountBooks) . " books\n";
    
} catch (Exception $e) {
    echo "BookRepository error: " . $e->getMessage() . "\n";
}

echo "\n=== ACCESS TEST COMPLETE ===\n";
?>
