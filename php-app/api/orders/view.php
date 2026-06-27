<?php
// LAB: VULN-IDOR-002 - Order View IDOR
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

require_login();

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// VULNERABLE: No ownership check - can view any order
// LAB: VULN-IDOR-002
$sql = "SELECT * FROM orders WHERE id = $order_id";
$result = query($sql);

if ($result && num_rows($result) > 0) {
    $order = fetch_assoc($result);
    
    // Get order items
    $items_result = query("SELECT oi.*, p.name, p.price 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = $order_id");
    
    $items = [];
    while ($item = fetch_assoc($items_result)) {
        $items[] = $item;
    }
    
    $order['items'] = $items;
    
    echo json_encode([
        'success' => true,
        'order' => $order
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Order not found'
    ]);
}
?>
