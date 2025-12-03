<?php
declare(strict_types=1);

namespace App\Helpers;

class CartHelper
{
    /**
     * Get cart total quantity
     */
    public static function getCartQuantity(): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $qty) {
            $total += (int)$qty;
        }
        return $total;
    }

    /**
     * Get cart total price
     */
    public static function getCartTotal(): float
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            return 0.0;
        }
        
        // We need BookRepository to calculate total
        // This is a simplified version - actual calculation should be in CartController
        return 0.0;
    }
}

