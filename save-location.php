<?php
// Bypass all restrictions
ignore_user_abort(true);
set_time_limit(0);

// Raw error logging
file_put_contents('/tmp/php_errors.log', print_r($_SERVER, true)."\n", FILE_APPEND);

// Capture any input
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?: [];

// Military-grade logging
$logEntry = sprintf(
    "[%s] %s\n",
    date('Y-m-d H:i:s'),
    print_r($data, true)
);

// Triple-write redundancy
file_put_contents('/tmp/locations.log', $logEntry, FILE_APPEND);
file_put_contents('/tmp/fallback.log', $logEntry, FILE_APPEND);
error_log($logEntry);

// Guaranteed response
header("HTTP/1.1 204 No Content");
exit();
?>
