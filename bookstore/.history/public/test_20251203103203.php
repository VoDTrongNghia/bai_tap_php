<?php
echo "Test file is working!<br>";
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>";

// Test config
require_once '../config.php';
echo "BASE_URL: " . BASE_URL . "<br>";

// Test if mod_rewrite is working
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite enabled: " . (in_array('mod_rewrite', $modules) ? 'Yes' : 'No') . "<br>";
} else {
    echo "Cannot check mod_rewrite status<br>";
}
?>
