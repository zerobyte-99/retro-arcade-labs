<?php
// LAB: VULN-SQLI-006 - SQL Injection in Cart Item Lookup
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '0';

// VULNERABLE: SQL injection in product_id parameter
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = query($sql);

if ($result && num_rows($result) > 0) {
    $product = fetch_assoc($result);
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
}
?>
