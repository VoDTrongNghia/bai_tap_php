<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;

class GiftController
{
    public function index(): void
    {
        // Sample gift categories
        $categories = [
            [
                'id' => 1,
                'name' => 'Quà tặng sinh nhật',
                'description' => 'Những món quà ý nghĩa cho ngày sinh nhật đặc biệt',
                'icon' => 'fa-birthday-cake',
                'count' => 25
            ],
            [
                'id' => 2,
                'name' => 'Quà tặng doanh nghiệp',
                'description' => 'Quà tặng sang trọng cho đối tác và khách hàng',
                'icon' => 'fa-briefcase',
                'count' => 18
            ],
            [
                'id' => 3,
                'name' => 'Quà tặng học tập',
                'description' => 'Món quà hữu ích cho học sinh, sinh viên',
                'icon' => 'fa-graduation-cap',
                'count' => 32
            ],
            [
                'id' => 4,
                'name' => 'Quà tặng sáng tạo',
                'description' => 'Những món quà độc đáo và sáng tạo',
                'icon' => 'fa-lightbulb',
                'count' => 15
            ]
        ];

        // Sample gift products
        $products = [
            [
                'id' => 1,
                'name' => 'Bộ sổ tay cao cấp',
                'price' => 180000,
                'old_price' => 220000,
                'image' => 'gift1.jpg',
                'category' => 'Quà tặng sinh nhật',
                'description' => 'Bộ sổ tay da thật cao cấp, thiết kế sang trọng',
                'discount' => 18,
                'rating' => 4.8,
                'reviews' => 45
            ],
            [
                'id' => 2,
                'name' => 'Bút ký kim loại',
                'price' => 350000,
                'image' => 'gift2.jpg',
                'category' => 'Quà tặng doanh nghiệp',
                'description' => 'Bút ký kim loại sang trọng, hộp quà đẹp',
                'rating' => 4.9,
                'reviews' => 28
            ],
            [
                'id' => 3,
                'name' => 'Bộ dụng cụ văn phòng',
                'price' => 120000,
                'old_price' => 150000,
                'image' => 'gift3.jpg',
                'category' => 'Quà tặng học tập',
                'description' => 'Bộ dụng cụ văn phòng đầy đủ, tiện dụng',
                'discount' => 20,
                'rating' => 4.6,
                'reviews' => 67
            ],
            [
                'id' => 4,
                'name' => 'Đồng hồ để bàn',
                'price' => 280000,
                'image' => 'gift4.jpg',
                'category' => 'Quà tặng doanh nghiệp',
                'description' => 'Đồng hồ để bàn sang trọng, chính xác cao',
                'rating' => 4.7,
                'reviews' => 33
            ],
            [
                'id' => 5,
                'name' => 'Bộ hộp đựng trà',
                'price' => 450000,
                'old_price' => 520000,
                'image' => 'gift5.jpg',
                'category' => 'Quà tặng sáng tạo',
                'description' => 'Bộ hộp đựng trà gốm sứ cao cấp',
                'discount' => 13,
                'rating' => 4.9,
                'reviews' => 19
            ],
            [
                'id' => 6,
                'name' => 'Sổ tay và bút set',
                'price' => 95000,
                'image' => 'gift6.jpg',
                'category' => 'Quà tặng học tập',
                'description' => 'Set sổ tay và bút xinh xắn',
                'rating' => 4.5,
                'reviews' => 52
            ],
            [
                'id' => 7,
                'name' => 'Lọ hoa thủy tinh',
                'price' => 180000,
                'image' => 'gift7.jpg',
                'category' => 'Quà tặng sáng tạo',
                'description' => 'Lọ hoa thủy tinh tinh xảo',
                'rating' => 4.7,
                'reviews' => 24
            ],
            [
                'id' => 8,
                'name' => 'Bộ bookmark độc đáo',
                'price' => 75000,
                'old_price' => 95000,
                'image' => 'gift8.jpg',
                'category' => 'Quà tặng học tập',
                'description' => 'Bộ bookmark thiết kế độc đáo',
                'discount' => 21,
                'rating' => 4.4,
                'reviews' => 38
            ]
        ];

        View::render('gifts/index', [
            'title' => 'Quà tặng',
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function category(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        // Sample category data
        $category = [
            'id' => $id,
            'name' => 'Quà tặng sinh nhật',
            'description' => 'Những món quà ý nghĩa và đặc biệt cho ngày sinh nhật của người thân, bạn bè',
            'image' => 'gift-category.jpg'
        ];

        $products = [
            [
                'id' => 1,
                'name' => 'Bộ sổ tay cao cấp',
                'price' => 180000,
                'old_price' => 220000,
                'image' => 'gift1.jpg',
                'description' => 'Bộ sổ tay da thật cao cấp, thiết kế sang trọng',
                'discount' => 18,
                'rating' => 4.8,
                'reviews' => 45
            ],
            [
                'id' => 9,
                'name' => 'Hộp chocolate cao cấp',
                'price' => 320000,
                'image' => 'gift9.jpg',
                'description' => 'Hộp chocolate nhập khẩu cao cấp',
                'rating' => 4.9,
                'reviews' => 31
            ],
            [
                'id' => 10,
                'name' => 'Bộ nến thơm',
                'price' => 250000,
                'old_price' => 290000,
                'image' => 'gift10.jpg',
                'description' => 'Bộ nến thơm thư giãn cao cấp',
                'discount' => 14,
                'rating' => 4.7,
                'reviews' => 27
            ]
        ];

        View::render('gifts/category', [
            'title' => $category['name'],
            'category' => $category,
            'products' => $products
        ]);
    }

    public function detail(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        // Sample product detail
        $product = [
            'id' => $id,
            'name' => 'Bộ sổ tay cao cấp',
            'price' => 180000,
            'old_price' => 220000,
            'image' => 'gift1.jpg',
            'gallery' => ['gift1.jpg', 'gift1-2.jpg', 'gift1-3.jpg'],
            'category' => 'Quà tặng sinh nhật',
            'brand' => 'Premium Gift',
            'description' => 'Bộ sổ tay cao cấp được làm từ da thật chất lượng cao, thiết kế sang trọng và tinh tế. Đây là món quà hoàn hảo cho những người yêu thích viết lách và muốn thể hiện phong cách chuyên nghiệp.',
            'features' => [
                'Bìa da thật 100%',
                'Khổ A5, 200 trang giấy cao cấp',
                'Thiết kế sang trọng, tinh tế',
                'Hộp quà tặng đẹp mắt',
                'Màu sắc đa dạng: đen, nâu, xanh navy'
            ],
            'specifications' => [
                'Thương hiệu' => 'Premium Gift',
                'Model' => 'Notebook-2024',
                'Chất liệu' => 'Da thật',
                'Kích thước' => 'A5 (21 x 14.8 cm)',
                'Số trang' => '200 trang',
                'Loại giấy' => 'Giấy cao cấp 80gsm',
                'Xuất xứ' => 'Italy',
                'Bảo hành' => '12 tháng'
            ],
            'stock' => 50,
            'sold' => 156,
            'rating' => 4.8,
            'reviews' => 45
        ];

        $relatedProducts = [
            ['id' => 2, 'name' => 'Bút ký kim loại', 'price' => 350000, 'image' => 'gift2.jpg'],
            ['id' => 5, 'name' => 'Bộ hộp đựng trà', 'price' => 450000, 'image' => 'gift5.jpg'],
            ['id' => 10, 'name' => 'Bộ nến thơm', 'price' => 250000, 'image' => 'gift10.jpg']
        ];

        View::render('gifts/detail', [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
