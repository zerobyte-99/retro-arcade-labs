<?php
// LAB: VULN-XSS-004 - Stored XSS in Comments
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { $input = $_POST; }

$product_id = isset($input['product_id']) ? intval($input['product_id']) : 0;
$body = isset($input['body']) ? $input['body'] : '';

// VULNERABLE: Stored XSS - no sanitization
// LAB: VULN-XSS-004
// Payload: <script>alert('XSS')</script>
if ($product_id && $body) {
    $result = query("INSERT INTO comments (product_id, user_id, body) VALUES ($product_id, " . $_SESSION['user_id'] . ", '$body')");
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Comment posted']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to post comment']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>
