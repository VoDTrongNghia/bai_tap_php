<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['logs'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Connect to database
$host = 'localhost';
$dbname = 'ban_sach';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Insert logs
    $stmt = $pdo->prepare("
        INSERT INTO frontend_logs (session_id, event_type, element_info, timestamp, url, user_agent, additional_data)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $success = true;
    foreach ($data['logs'] as $log) {
        try {
            $stmt->execute([
                $log['sessionId'] ?? '',
                $log['eventType'] ?? '',
                json_encode($log['elementInfo'] ?? []),
                date('Y-m-d H:i:s', $log['timestamp'] / 1000),
                $log['url'] ?? '',
                $log['userAgent'] ?? '',
                json_encode($log['additionalData'] ?? [])
            ]);
        } catch (Exception $e) {
            $success = false;
            error_log("Failed to insert log: " . $e->getMessage());
        }
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Logs saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Some logs failed to save']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>
