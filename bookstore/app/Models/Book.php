<?php
declare(strict_types=1);

namespace App\Models;

class Book
{
    public string $id;
    public string $title;
    public string $author;
    public string $category;
    public string $description;
    public float $price;
    public ?float $originalPrice;
    public float $discountPercentage;
    public string $coverImage;

    public function __construct(array $data)
    {
        $this->id = (string)($data['id'] ?? '');
        $this->title = (string)($data['title'] ?? $data['ten_sach'] ?? '');
        $this->author = (string)($data['author'] ?? $data['tac_gia'] ?? '');
        $this->category = (string)($data['category'] ?? 'Khác');
        $this->description = (string)($data['mo_ta'] ?? $data['description'] ?? '');
        $this->price = (float)($data['price'] ?? $data['gia_khuyen_mai'] ?? $data['gia_goc'] ?? 0);
        $this->originalPrice = isset($data['original_price']) ? (float)$data['original_price'] : (float)($data['gia_goc'] ?? 0);
        $this->discountPercentage = (float)($data['discount_percentage'] ?? $data['phan_tram_giam'] ?? 0);
        // Handle both camelCase and snake_case for coverImage
        $this->coverImage = (string)($data['cover_image'] ?? $data['coverImage'] ?? $data['anh_bia'] ?? '');
    }

    public function getImageUrl()
{
    if (!defined('BASE_URL')) {
        require_once __DIR__ . '/../../config.php';
    }
    $baseUrl = BASE_URL;
    
    // Nếu đã là đường dẫn cũ
    if (strpos($this->coverImage, 'assets/images/books/') !== false) {
        return $baseUrl . $this->coverImage;
    }
    
    // Nếu là ảnh mới
    if (strpos($this->coverImage, 'uploads/products/') !== false) {
        return $baseUrl . $this->coverImage;
    }
    
    // Fallback cho ảnh cũ không có prefix
    if (!empty($this->coverImage)) {
        return $baseUrl . 'assets/images/books/' . $this->coverImage;
    }
    
    // Placeholder
    return $baseUrl . 'assets/images/books/placeholder.jpg';
}
    
    /**
     * Get book title
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * Get book author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
    
    /**
     * Get book description
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Get book ID
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Check if the book has a discount
     */
    public function hasDiscount(): bool
    {
        return $this->discountPercentage > 0 && $this->originalPrice !== null;
    }
    
    /**
     * Get the original price formatted
     */
    public function getOriginalPrice(): ?float
    {
        return $this->originalPrice;
    }
    
    /**
     * Get the current price (after discount if applicable)
     */
    public function getPrice(): float
    {
        return $this->price;
    }
    
    /**
     * Get the discount percentage
     */
    public function getDiscountPercentage(): float
    {
        return $this->discountPercentage;
    }
    
    /**
     * Get formatted original price for display
     */
    public function getFormattedOriginalPrice(): string
    {
        return $this->originalPrice ? number_format($this->originalPrice) : '';
    }
    
    /**
     * Get formatted current price for display
     */
    public function getFormattedPrice(): string
    {
        return number_format($this->price);
    }
    
    /**
     * Get formatted discount percentage for display
     */
    public function getFormattedDiscount(): string
    {
        return $this->discountPercentage > 0 ? number_format($this->discountPercentage, 1) : '';
    }

    /**
     * Save book to database
     */
    public function save(): bool
    {
        $pdo = \App\Database::getConnection();
        
        try {
            if (!empty($this->id) && is_numeric($this->id)) {
                // Update existing book
                $stmt = $pdo->prepare("
                    UPDATE sach 
                    SET ten_sach = ?, tac_gia = ?, mo_ta = ?, gia_goc = ?, anh_bia = ? 
                    WHERE id = ?
                ");
                return $stmt->execute([
                    $this->title,
                    $this->author,
                    $this->description,
                    $this->price,
                    $this->coverImage,
                    $this->id
                ]);
            } else {
                // Insert new book
                $stmt = $pdo->prepare("
                    INSERT INTO sach (ten_sach, tac_gia, mo_ta, gia_goc, anh_bia) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $result = $stmt->execute([
                    $this->title,
                    $this->author,
                    $this->description,
                    $this->price,
                    $this->coverImage
                ]);
                
                if ($result) {
                    $this->id = (string)$pdo->lastInsertId();
                }
                
                return $result;
            }
        } catch (\Exception $e) {
            error_log("Error saving book: " . $e->getMessage());
            return false;
        }
    }
}


