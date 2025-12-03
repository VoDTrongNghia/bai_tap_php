<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;

class OfficeController
{
    public function index(): void
    {
        // Sample office products data
        $categories = [
            [
                'id' => 1,
                'name' => 'Văn phòng phẩm cơ bản',
                'description' => 'Các dụng cụ văn phòng thiết yếu hàng ngày',
                'icon' => 'fa-pencil-alt'
            ],
            [
                'id' => 2,
                'name' => 'Giấy và sổ tay',
                'description' => 'Các loại giấy, sổ, tập ghi chép',
                'icon' => 'fa-book'
            ],
            [
                'id' => 3,
                'name' => 'Thiết bị điện tử',
                'description' => 'Máy tính, máy in, và các thiết bị văn phòng điện tử',
                'icon' => 'fa-laptop'
            ],
            [
                'id' => 4,
                'name' => 'Lưu trữ và tổ chức',
                'description' => 'Hồ sơ, bìa, và các dụng cụ lưu trữ tài liệu',
                'icon' => 'fa-folder'
            ]
        ];

        $products = [
            [
                'id' => 1,
                'name' => 'Bút bi Thiên Long TL-027',
                'price' => 5000,
                'image' => 'pen1.jpg',
                'category' => 'Văn phòng phẩm cơ bản',
                'description' => 'Bút bi mực đen, viết mượt, giá rẻ'
            ],
            [
                'id' => 2,
                'name' => 'Sổ tay A5 200 trang',
                'price' => 25000,
                'image' => 'notebook1.jpg',
                'category' => 'Giấy và sổ tay',
                'description' => 'Sổ tay A5, giấy chất lượng cao, bìa cứng'
            ],
            [
                'id' => 3,
                'name' => 'Bảng trắng mini 30x40cm',
                'price' => 120000,
                'image' => 'whiteboard1.jpg',
                'category' => 'Văn phòng phẩm cơ bản',
                'description' => 'Bảng trắng nhỏ gọn, dễ sử dụng'
            ],
            [
                'id' => 4,
                'name' => 'Hồ sơ bìa nhựa A4',
                'price' => 15000,
                'image' => 'folder1.jpg',
                'category' => 'Lưu trữ và tổ chức',
                'description' => 'Bìa nhựa đựng hồ sơ A4, độ bền cao'
            ],
            [
                'id' => 5,
                'name' => 'Bút highlight 6 màu',
                'price' => 35000,
                'image' => 'highlighter1.jpg',
                'category' => 'Văn phòng phẩm cơ bản',
                'description' => 'Bộ bút highlight 6 màu, mực chất lượng'
            ],
            [
                'id' => 6,
                'name' => 'Giấy A4 Double A 80gsm',
                'price' => 75000,
                'image' => 'paper1.jpg',
                'category' => 'Giấy và sổ tay',
                'description' => 'Giấy A4 Double A, 500 tờ/rương, chất lượng cao'
            ],
            [
                'id' => 7,
                'name' => 'Đồ gọt bút điện',
                'price' => 45000,
                'image' => 'sharpener1.jpg',
                'category' => 'Văn phòng phẩm cơ bản',
                'description' => 'Đồ gọt bút chạy điện, tiện lợi'
            ],
            [
                'id' => 8,
                'name' => 'Kẹp giấy 100 cái',
                'price' => 12000,
                'image' => 'clips1.jpg',
                'category' => 'Lưu trữ và tổ chức',
                'description' => 'Kẹp giấy inox, 100 cái/hộp'
            ]
        ];

        View::render('office/index', [
            'title' => 'Văn phòng phẩm',
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
            'name' => 'Văn phòng phẩm cơ bản',
            'description' => 'Các dụng cụ văn phòng thiết yếu hàng ngày cho công việc và học tập',
            'image' => 'office-category.jpg'
        ];

        $products = [
            [
                'id' => 1,
                'name' => 'Bút bi Thiên Long TL-027',
                'price' => 5000,
                'old_price' => 6000,
                'image' => 'pen1.jpg',
                'description' => 'Bút bi mực đen, viết mượt, giá rẻ',
                'discount' => 17
            ],
            [
                'id' => 3,
                'name' => 'Bảng trắng mini 30x40cm',
                'price' => 120000,
                'old_price' => 150000,
                'image' => 'whiteboard1.jpg',
                'description' => 'Bảng trắng nhỏ gọn, dễ sử dụng',
                'discount' => 20
            ],
            [
                'id' => 5,
                'name' => 'Bút highlight 6 màu',
                'price' => 35000,
                'image' => 'highlighter1.jpg',
                'description' => 'Bộ bút highlight 6 màu, mực chất lượng'
            ],
            [
                'id' => 7,
                'name' => 'Đồ gọt bút điện',
                'price' => 45000,
                'old_price' => 55000,
                'image' => 'sharpener1.jpg',
                'description' => 'Đồ gọt bút chạy điện, tiện lợi',
                'discount' => 18
            ]
        ];

        View::render('office/category', [
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
            'name' => 'Bút bi Thiên Long TL-027',
            'price' => 5000,
            'old_price' => 6000,
            'image' => 'pen1.jpg',
            'gallery' => ['pen1.jpg', 'pen1-2.jpg', 'pen1-3.jpg'],
            'category' => 'Văn phòng phẩm cơ bản',
            'brand' => 'Thiên Long',
            'description' => 'Bút bi Thiên Long TL-027 là dòng bút bi bán chạy nhất với thiết kế đơn giản, chất lượng ổn định và giá cả phải chăng.',
            'features' => [
                'Mực đen, độ bền cao',
                'Ngòi tròn 0.7mm viết mượt',
                'Thân bút nhựa ABS cao cấp',
                'Thiết kế ergonomics, cầm chắc tay',
                'Hộp 20 cây, tiện lợi mua sắm số lượng lớn'
            ],
            'specifications' => [
                'Thương hiệu' => 'Thiên Long',
                'Model' => 'TL-027',
                'Màu mực' => 'Đen',
                'Kích thước ngòi' => '0.7mm',
                'Chất liệu' => 'Nhựa ABS',
                'Xuất xứ' => 'Việt Nam',
                'Bảo hành' => '6 tháng'
            ],
            'stock' => 150,
            'sold' => 2340,
            'rating' => 4.5,
            'reviews' => 89
        ];

        $relatedProducts = [
            ['id' => 2, 'name' => 'Sổ tay A5 200 trang', 'price' => 25000, 'image' => 'notebook1.jpg'],
            ['id' => 5, 'name' => 'Bút highlight 6 màu', 'price' => 35000, 'image' => 'highlighter1.jpg'],
            ['id' => 8, 'name' => 'Kẹp giấy 100 cái', 'price' => 12000, 'image' => 'clips1.jpg']
        ];

        View::render('office/detail', [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
