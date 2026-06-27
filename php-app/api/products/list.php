<?php
// Product listing API
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// VULNERABLE: SQL injection in category filter
// LAB: VULN-SQLI-005
if ($category) {
    $sql = "SELECT * FROM products WHERE category_id = $category LIMIT $offset, $per_page";
} else {
    $sql = "SELECT * FROM products LIMIT $offset, $per_page";
}

$result = query($sql);

$products = [];
if ($result) {
    while ($row = fetch_assoc($result)) {
        $products[] = $row;
    }
}

// Get total count
$count_result = query("SELECT COUNT(*) as total FROM products" . ($category ? " WHERE category_id = $category" : ""));
$total = $count_result ? fetch_assoc($count_result)['total'] : 0;

echo json_encode([
    'success' => true,
    'page' => $page,
    'per_page' => $per_page,
    'total' => $total,
    'products' => $products
]);
?>
