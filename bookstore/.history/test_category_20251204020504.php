<?php
require_once 'config.php';
require_once 'app/Database.php';

// Test Category model
require_once 'app/Models/Category.php';

use App\Models\Category;

try {
    echo "<h3>Test Category Model</h3>";
    
    // Test getAll
    $categories = Category::getAll();
    echo "<p>Số lượng danh mục: " . count($categories) . "</p>";
    
    if (!empty($categories)) {
        $firstCategory = $categories[0];
        echo "<p>Danh mục đầu tiên: " . htmlspecialchars($firstCategory['ten_danh_muc']) . "</p>";
        
        // Test getById
        $category = Category::getById($firstCategory['id']);
        echo "<p>GetById: " . htmlspecialchars($category['ten_danh_muc']) . "</p>";
        
        // Test getBookCount
        $bookCount = Category::getBookCount($firstCategory['id']);
        echo "<p>Số sách trong danh mục: " . $bookCount . "</p>";
        
        // Test nameExists
        $exists = Category::nameExists($firstCategory['ten_danh_muc']);
        echo "<p>Tên tồn tại: " . ($exists ? 'true' : 'false') . "</p>";
        
        // Test nameExists with exclude
        $existsExclude = Category::nameExists($firstCategory['ten_danh_muc'], $firstCategory['id']);
        echo "<p>Tên tồn tại (exclude): " . ($existsExclude ? 'true' : 'false') . "</p>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
