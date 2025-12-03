<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/app/bootstrap.php';

use App\Repositories\BookRepository;

echo "<h2>Test kết nối và hiển thị sách từ database ban_sach</h2>\n";

try {
    $bookRepository = new BookRepository();
    
    // Test lấy sách bán chạy
    echo "<h3>Sách bán chạy:</h3>\n";
    $bestSellingBooks = $bookRepository->getBestSellingBooks();
    echo "<p>Số lượng sách bán chạy: " . count($bestSellingBooks) . "</p>\n";
    
    foreach ($bestSellingBooks as $book) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>\n";
        echo "<h4>" . htmlspecialchars($book->title) . "</h4>\n";
        echo "<p><strong>Tác giả:</strong> " . htmlspecialchars($book->author) . "</p>\n";
        echo "<p><strong>Giá:</strong> " . number_format($book->getPrice()) . " VNĐ</p>\n";
        echo "<p><strong>Giá gốc:</strong> " . number_format($book->originalPrice) . " VNĐ</p>\n";
        echo "<p><strong>Giảm giá:</strong> " . $book->discountPercentage . "%</p>\n";
        echo "<p><strong>Mô tả:</strong> " . htmlspecialchars(substr($book->description, 0, 100)) . "...</p>\n";
        echo "<p><strong>Ảnh bìa:</strong> " . htmlspecialchars($book->coverImage) . "</p>\n";
        echo "<p><strong>Image URL:</strong> <img src='" . $book->getImageUrl() . "' width='50' height='70' onerror='this.src=\"data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 50 70\'%3E%3Crect fill=\'%23f8f9fa\' width=\'50\' height=\'70\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-size=\'8\'%3ENo Image%3C/text%3E%3C/svg%3E\"' /></p>\n";
        echo "</div>\n";
    }
    
    // Test lấy sách mới
    echo "<h3>Sách mới:</h3>\n";
    $newBooks = $bookRepository->getNewBooks();
    echo "<p>Số lượng sách mới: " . count($newBooks) . "</p>\n";
    
    foreach ($newBooks as $book) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>\n";
        echo "<h4>" . htmlspecialchars($book->title) . "</h4>\n";
        echo "<p><strong>Tác giả:</strong> " . htmlspecialchars($book->author) . "</p>\n";
        echo "<p><strong>Giá:</strong> " . number_format($book->getPrice()) . " VNĐ</p>\n";
        echo "</div>\n";
    }
    
    // Test lấy sách giảm giá
    echo "<h3>Sách giảm giá:</h3>\n";
    $discountBooks = $bookRepository->getDiscountBooks();
    echo "<p>Số lượng sách giảm giá: " . count($discountBooks) . "</p>\n";
    
    foreach ($discountBooks as $book) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>\n";
        echo "<h4>" . htmlspecialchars($book->title) . "</h4>\n";
        echo "<p><strong>Tác giả:</strong> " . htmlspecialchars($book->author) . "</p>\n";
        echo "<p><strong>Giá:</strong> " . number_format($book->getPrice()) . " VNĐ</p>\n";
        echo "<p><strong>Giảm giá:</strong> " . $book->discountPercentage . "%</p>\n";
        echo "</div>\n";
    }
    
    echo "<h2>✅ Kết nối database và hiển thị sách thành công!</h2>\n";
    echo "<p><a href='/bookstore/public/'>Trang chủ</a> | <a href='/bookstore/public/home'>Trang home</a></p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<p>Stack trace:</p>\n";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>\n";
}
?>
