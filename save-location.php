<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    error_log("Location captured: " . print_r($data, true));
    
    // Add timestamp
    $data['timestamp'] = date('Y-m-d H:i:s');
    
    // Save to file (create if doesn't exist)
    file_put_contents('locations.log', json_encode($data)."\n", FILE_APPEND);
    
    echo json_encode(['status' => 'success']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>
