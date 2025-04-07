<?php
// ==============================================
// NUCLEAR-PROOF LOCATION LOGGER FOR RENDER
// ==============================================

// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ======================
// SECURITY HEADERS
// ======================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// ======================
// SERVER CONFIG
// ======================
ignore_user_abort(true);  // Continue processing after client disconnects
set_time_limit(10);       // 10 second timeout

// ======================
// LOGGING CONFIG
// ======================
$LOG_FILE = '/tmp/locations.log';  // ONLY writable dir in Render
$MAX_LOG_SIZE = 1048576;           // 1MB rotation (optional)

// ======================
// MAIN EXECUTION
// ======================
try {
    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get raw input
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('Empty payload');
    }

    // Validate JSON
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    // Prepare log entry
    $log_entry = sprintf(
        "[%s] IP: %s | UA: %s | Data: %s\n",
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        $input
    );

    // ======================
    // ATOMIC WRITE OPERATION
    // ======================
    file_put_contents($LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);

    // Optional: Auto-save to Render persistent disk (paid feature)
    file_put_contents(
    '/opt/render/.logs/locations_perm.log', 
    $log_entry, 
    FILE_APPEND
    );

    // Optional log rotation
    if (filesize($LOG_FILE) > $MAX_LOG_SIZE) {
        rename($LOG_FILE, $LOG_FILE . '.' . time());
    }

    // ======================
    // SECONDARY LOGGING
    // ======================
    error_log("LOCATION_TRACKER: " . $log_entry);  // Render dashboard
    syslog(LOG_INFO, $log_entry);                 // System logs

    // Success response
    http_response_code(204);
    exit();

} catch (Exception $e) {
    // Error handling
    error_log("ERROR: " . $e->getMessage());
    http_response_code(400);
    die(json_encode(['error' => $e->getMessage()]));
}
?>
