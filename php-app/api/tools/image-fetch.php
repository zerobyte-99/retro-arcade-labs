<?php
// LAB: VULN-SSRF-001 - Image Fetcher SSRF
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

$url = isset($_GET['url']) ? $_GET['url'] : '';

// VULNERABLE: No URL validation - allows SSRF to internal services
// LAB: VULN-SSRF-001
// Targets: internal-metadata:11081, mock-mail:11025, etc.
// Payloads:
// - http://internal-metadata:11081/api/secrets
// - http://mock-mail:11025/

if (empty($url)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'URL required']);
    exit;
}

// VULNERABLE: No URL validation - can access internal services
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);

$content = @file_get_contents($url, false, $context);

if ($content !== false) {
    echo json_encode([
        'success' => true,
        'url' => $url,
        'content_length' => strlen($content),
        'content_preview' => substr($content, 0, 500)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch URL'
    ]);
}
?>
