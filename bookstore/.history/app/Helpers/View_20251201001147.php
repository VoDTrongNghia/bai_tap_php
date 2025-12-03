<?php
declare(strict_types=1);

namespace App\Helpers;

class View
{
    public static function render(string $template, array $data = [])
    {
        // Extract data to variables
        $data['baseUrl'] = BASE_URL;
        extract($data);

        // Start output buffering
        ob_start();

        // Build the full path to the view file
        $viewFile = VIEWS_PATH . str_replace('.', '/', $template) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: " . $viewFile);
        }

        // Include the view file
        include $viewFile;

        // Get the buffered content
        $content = ob_get_clean();
        
        // Make baseUrl available to layout
        $baseUrl = BASE_URL;

        // Include the layout
        $layoutFile = VIEWS_PATH . 'layout/main.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            // If no layout, just output the content
            echo $content;
        }
    }

    // Helper function to include partials
    public static function partial(string $name, array $data = [])
    {
        extract($data);
        $file = VIEWS_PATH . 'partials/' . $name . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }
}