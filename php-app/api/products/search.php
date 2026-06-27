<?php
// LAB: VULN-SQLI-002 - Product Search SQL Injection

header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';

// VULNERABLE: SQL injection in search
// LAB: VULN-SQLI-002
// Payload: ' UNION SELECT 1,2,3,4,5,6,7,8--
$sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";

$result = query($sql);

$products = [];
if ($result) {
    while ($row = fetch_assoc($result)) {
        $products[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'query' => $search,
    'count' => count($products),
    'products' => $products
]);
?>
