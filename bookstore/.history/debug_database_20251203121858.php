<?php
// Load config first
require_once __DIR__ . '/config.php';

// Debug database connection and data
header('Content-Type: text/plain');

echo "=== DATABASE DEBUG ===\n\n";

// Database config
$host = DB_HOST;
$dbname = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$charset = DB_CHARSET;

echo "Database Config:\n";
echo "Host: $host\n";
echo "Database: $dbname\n";
echo "User: $user\n";
echo "Charset: $charset\n\n";

try {
    // Test connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: SUCCESS\n\n";
    
    // Check if database exists
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Current database: " . $result['current_db'] . "\n\n";
    
    // List all tables
    echo "=== TABLES ===\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "❌ No tables found in database\n";
    } else {
        echo "Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }
    echo "\n";
    
    // Check specific tables
    $expected_tables = ['sach', 'books', 'nguoi_dung', 'danh_muc', 'don_hang', 'voucher'];
    
    foreach ($expected_tables as $table) {
        echo "=== TABLE: $table ===\n";
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Records: " . $result['count'] . "\n";
            
            if ($result['count'] > 0) {
                $stmt = $pdo->query("SELECT * FROM `$table` LIMIT 3");
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "Sample data:\n";
                print_r($records);
            }
        } catch (Exception $e) {
            echo "❌ Table '$table' not found or error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection FAILED: " . $e->getMessage() . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Check if MySQL/XAMPP is running\n";
    echo "2. Verify database name 'ban_sach' exists\n";
    echo "3. Check database credentials in config.php\n";
    echo "4. Create database: CREATE DATABASE ban_sach;\n";
}

echo "\n=== PHP INFO ===\n";
echo "PDO MySQL support: " . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo "PDO support: " . (extension_loaded('pdo') ? 'YES' : 'NO') . "\n";
?>
