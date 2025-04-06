<?php
// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Atomic logging
$logFile = '/tmp/locations.log';
$input = file_get_contents('php://input');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($input)) {
    // Validate JSON
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        die(json_encode(['error' => 'Invalid JSON']));
    }

    // Guaranteed write
    file_put_contents(
        $logFile,
        date('[Y-m-d H:i:s] ') . print_r($data, true) . "\n",
        FILE_APPEND | LOCK_EX
    );

    // Immediate verification
    error_log("LOCATION LOGGED: " . $input);
    die(json_encode(['status' => 'success']));
}

http_response_code(405);
die(json_encode(['error' => 'Method not allowed']));
?>
