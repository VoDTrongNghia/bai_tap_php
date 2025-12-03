<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\View;

class PromotionsController
{
    public function index(): void
    {
        // Sample promotions data
        $promotions = [
            [
                'id' => 1,
                'title' => 'Flash Sale - Giảm giá đến 70%',
                'description' => 'Chương trình flash sale với ưu đãi lên đến 70% cho các sách bestseller',
                'discount' => 70,
                'image' => 'promotion1.jpg',
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-31',
                'type' => 'flash_sale',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'title' => 'Mua 2 tặng 1 - Sách văn học',
                'description' => 'Mua 2 sách văn học bất kỳ, tặng ngay 1 sách cùng danh mục',
                'discount' => 33,
                'image' => 'promotion2.jpg',
                'start_date' => '2024-11-15',
                'end_date' => '2024-12-15',
                'type' => 'buy_2_get_1',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'title' => 'Freeship cho đơn từ 200k',
                'description' => 'Miễn phí vận chuyển cho tất cả đơn hàng từ 200.000đ',
                'discount' => 0,
                'image' => 'promotion3.jpg',
                'start_date' => '2024-11-01',
                'end_date' => '2024-12-31',
                'type' => 'freeship',
                'status' => 'active'
            ],
            [
                'id' => 4,
                'title' => 'Tặng voucher 50k cho khách hàng mới',
                'description' => 'Khách hàng mới nhận ngay voucher giảm 50.000đ cho lần mua đầu tiên',
                'discount' => 50000,
                'image' => 'promotion4.jpg',
                'start_date' => '2024-11-01',
                'end_date' => '2024-12-31',
                'type' => 'voucher',
                'status' => 'active'
            ],
            [
                'id' => 5,
                'title' => 'Combo học tập - Tiết kiệm 30%',
                'description' => 'Bộ combo sách kỹ năng sống với giá ưu đãi, giảm 30% so với giá gốc',
                'discount' => 30,
                'image' => 'promotion5.jpg',
                'start_date' => '2024-11-20',
                'end_date' => '2024-12-20',
                'type' => 'combo',
                'status' => 'active'
            ],
            [
                'id' => 6,
                'title' => 'Tuần lễ sách thiếu nhi',
                'description' => 'Ưu đãi đặc biệt cho sách thiếu nhi, giảm giá 25% và tặng quà',
                'discount' => 25,
                'image' => 'promotion6.jpg',
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-07',
                'type' => 'weekly',
                'status' => 'active'
            ]
        ];

        // Categorize promotions
        $activePromotions = array_filter($promotions, fn($p) => $p['status'] === 'active');
        $flashSales = array_filter($promotions, fn($p) => $p['type'] === 'flash_sale');
        $vouchers = array_filter($promotions, fn($p) => $p['type'] === 'voucher');

        View::render('promotions/index', [
            'title' => 'Khuyến mãi',
            'promotions' => $promotions,
            'activePromotions' => $activePromotions,
            'flashSales' => $flashSales,
            'vouchers' => $vouchers
        ]);
    }

    public function detail(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        
        // Sample promotion detail
        $promotion = [
            'id' => $id,
            'title' => 'Flash Sale - Giảm giá đến 70%',
            'description' => 'Chương trình flash sale lớn nhất trong năm với ưu đãi lên đến 70% cho các sách bestseller. Đừng bỏ lỡ cơ hội sở hữu những cuốn sách hay với giá không thể tốt hơn!',
            'full_description' => '<p>Chương trình flash sale là cơ hội vàng để sở hữu những cuốn sách bestseller với mức giá không tưởng. Với ưu đãi lên đến 70%, bạn có thể mua được nhiều sách hơn với chi phí thấp hơn.</p>
            
            <h3>Điều kiện áp dụng:</h3>
            <ul>
                <li>Áp dụng cho các sách có tag "Flash Sale"</li>
                <li>Không áp dụng cùng các chương trình khuyến mãi khác</li>
                <li>Giới hạn số lượng mỗi đầu sách</li>
                <li>Áp dụng từ 01/12/2024 đến 31/12/2024</li>
            </ul>
            
            <h3>Cách thức tham gia:</h3>
            <ol>
                <li>Chọn sách có tag Flash Sale</li>
                <li>Thêm vào giỏ hàng</li>
                <li>Giảm giá tự động áp dụng</li>
                <li>Hoàn tất thanh toán</li>
            </ol>',
            'discount' => 70,
            'image' => 'promotion1.jpg',
            'gallery' => ['promotion1.jpg', 'promotion1-2.jpg', 'promotion1-3.jpg'],
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-31',
            'type' => 'flash_sale',
            'status' => 'active',
            'terms' => [
                'Chương trình có thể kết thúc sớm khi hết sản phẩm',
                'Không hoàn tiền sau khi đã mua hàng',
                'Mỗi khách hàng được mua tối đa 5 sản phẩm',
                'Nhà sách giữ quyền thay đổi chương trình'
            ]
        ];

        // Related promotions
        $relatedPromotions = [
            ['id' => 2, 'title' => 'Mua 2 tặng 1 - Sách văn học', 'discount' => 33, 'image' => 'promotion2.jpg'],
            ['id' => 5, 'title' => 'Combo học tập - Tiết kiệm 30%', 'discount' => 30, 'image' => 'promotion5.jpg'],
            ['id' => 3, 'title' => 'Freeship cho đơn từ 200k', 'discount' => 0, 'image' => 'promotion3.jpg']
        ];

        View::render('promotions/detail', [
            'title' => $promotion['title'],
            'promotion' => $promotion,
            'relatedPromotions' => $relatedPromotions
        ]);
    }
}
