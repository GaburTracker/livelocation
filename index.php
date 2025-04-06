<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Log to Render's native logging system
    error_log("Location captured: " . print_r($data, true));
    
    // Optional: Save to database instead of file
    http_response_code(200);
}
?>