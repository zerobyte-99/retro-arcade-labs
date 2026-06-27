<?php
// Health check endpoint
header('Content-Type: application/json');

$status = [
    'service' => 'Retro Arcade Labs API',
    'status' => 'running',
    'timestamp' => date('Y-m-d H:i:s')
];

// Check database
try {
    require_once __DIR__ . '/../config/database.php';
    $status['database'] = 'connected';
} catch (Exception $e) {
    $status['database'] = 'disconnected';
}

echo json_encode($status);
?>
