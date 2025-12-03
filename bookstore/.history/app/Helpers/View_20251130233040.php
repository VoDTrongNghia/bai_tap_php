<?php
declare(strict_types=1);

namespace App\Helpers;

class View
{
    public static function render(string $template, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        
        // Load config nếu chưa được load
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }

        // Xác định đường dẫn đến file view
        $template = str_replace('.', '/', $template);
        $viewFile = VIEWS_PATH . $template . '.php';
        
        // Kiểm tra xem file view có tồn tại không
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Không tìm thấy view: $template");
        }

        // Bắt đầu đệm đầu ra
        ob_start();
        
        try {
            // Nhúng file view
            include $viewFile;
            $content = ob_get_clean();
            
            // Hiển thị nội dung
            echo $content;
            
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
}