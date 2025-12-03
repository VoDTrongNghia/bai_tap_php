<?php
declare(strict_types=1);

namespace App\Helpers;

class View
{
    public static function render(string $template, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        
        // Load config if not already loaded
        if (!defined('BASE_PATH')) {
            require_once __DIR__ . '/../../config.php';
        }
        
        // Ensure baseUrl has trailing slash for consistency
        $baseUrl = rtrim(BASE_PATH, '/') . '/';
        $appName = APP_NAME;

        $baseDir = __DIR__ . '/../../views/';
        $viewFile = $baseDir . $template . '.php';
        
        // Check if this is an admin view or auth view (login/register don't need header/footer)
        $isAdmin = strpos($template, 'admin/') === 0;
        $isAuth = strpos($template, 'auth/') === 0;
        
        if ($isAdmin) {
            $headerFile = $baseDir . 'layout/admin_header.php';
            $footerFile = $baseDir . 'layout/admin_footer.php';
        } elseif ($isAuth) {
            // Auth pages (login, register) have their own full HTML structure
            $headerFile = null;
            $footerFile = null;
        } else {
            $headerFile = $baseDir . 'layout/header.php';
            $footerFile = $baseDir . 'layout/footer.php';
        }

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found: ' . htmlspecialchars($template);
            return;
        }

        if ($headerFile && file_exists($headerFile)) {
            include $headerFile;
        }
        include $viewFile;
        if ($footerFile && file_exists($footerFile)) {
            include $footerFile;
        }
    }
}


