<?php
// Bypass all security restrictions
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
ignore_user_abort(true);
set_time_limit(0);

// Military-grade logging
$raw_input = file_get_contents('php://input');
$client_ip = $_SERVER['REMOTE_ADDR'];
$log_entry = sprintf(
    "[%s] IP: %s | Data: %s\n",
    date('Y-m-d H:i:s'),
    $client_ip,
    $raw_input ?: 'NO_DATA'
);

// Triple redundancy
file_put_contents('/tmp/tracking.log', $log_entry, FILE_APPEND);
error_log($log_entry);  // Render dashboard logs
syslog(LOG_INFO, $log_entry);

// Send empty response
http_response_code(204);
exit();
?>
