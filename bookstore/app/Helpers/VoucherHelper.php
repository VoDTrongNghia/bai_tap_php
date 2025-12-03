<?php

if (!function_exists('getVoucherStatusLabel')) {
    function getVoucherStatusLabel(string $status): string {
        $labels = [
            'active' => 'Đang hoạt động',
            'pending' => 'Chưa bắt đầu',
            'expired' => 'Đã hết hạn',
            'used_up' => 'Đã sử dụng hết',
            'inactive' => 'Ngừng hoạt động'
        ];
        
        return $labels[$status] ?? 'Không xác định';
    }
}
