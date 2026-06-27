<?php
// LAB: VULN-SSRF-002 - Webhook Tester SSRF
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

$input = json_decode(file_get_contents('php://input'), true);
$url = isset($input['url']) ? $input['url'] : '';
$method = isset($input['method']) ? $input['method'] : 'GET';
$headers = isset($input['headers']) ? $input['headers'] : [];

// VULNERABLE: No URL validation - SSRF to internal services
// LAB: VULN-SSRF-002
if (empty($url)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'URL required']);
    exit;
}

// VULNERABLE: Can access internal Docker services
$context = stream_context_create([
    'http' => [
        'method' => $method,
        'header' => implode("\r\n", array_map(function($k, $v) { return "$k: $v"; }, array_keys($headers), $headers)),
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$start = microtime(true);
$content = @file_get_contents($url, false, $context);
$duration = microtime(true) - $start;

if ($content !== false) {
    echo json_encode([
        'success' => true,
        'url' => $url,
        'method' => $method,
        'status' => 'completed',
        'duration' => round($duration, 3),
        'content_length' => strlen($content),
        'content_preview' => substr($content, 0, 500)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'url' => $url,
        'method' => $method,
        'status' => 'failed',
        'duration' => round($duration, 3)
    ]);
}
?>
