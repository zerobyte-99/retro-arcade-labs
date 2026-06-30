<?php
// LAB: VULN-DESERIAL-002 - Insecure Deserialization
// Accepts serialized cart data via POST and deserializes it
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// VULNERABLE: Deserializing user-supplied data
// LAB: VULN-DESERIAL-002
// This could be exploited with a malicious serialized object

$cart_data = isset($_POST['cart']) ? $_POST['cart'] : '';

// VULNERABLE: No input validation before unserialize
if (!empty($cart_data)) {
    // Try to unserialize the cart data
    // This is vulnerable to PHP object injection
    $cart = @unserialize($cart_data);
    
    echo json_encode([
        'success' => true,
        'cart_unserialized' => is_array($cart) ? count($cart) . ' items' : 'Invalid cart',
        'cart_data' => $cart
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No cart data provided']);
}
?>
