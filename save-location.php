<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add these headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    error_log("Location captured: " . print_r($data, true));
    
    // Force log creation
    $log = __DIR__ . '/locations.log';
    file_put_contents($log, print_r($data, true) . "\n", FILE_APPEND);
    chmod($log, 0666);
    
    echo json_encode(['status' => 'success']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
if (!file_exists($log)) touch($log);
chmod($log, 0666);
?>
