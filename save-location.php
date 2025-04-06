<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $data['timestamp'] = date('Y-m-d H:i:s');
    
    // Use /tmp for guaranteed write access
    $logFile = '/tmp/locations.log';
    
    if (!file_exists($logFile)) {
        file_put_contents($logFile, "");
        chmod($logFile, 0666);
    }
    
    file_put_contents($logFile, print_r($data, true)."\n", FILE_APPEND);
    error_log("Logged: " . json_encode($data));
    
    echo json_encode(['status' => 'success']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>
