<?php
// LAB: VULN-BIZ-001 - Reusable Coupon (Race Condition)
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$code = isset($_POST['code']) ? $_POST['code'] : '';

// VULNERABLE: Race condition - no locking, used_count checked before increment
$sql = "SELECT * FROM coupons WHERE code = '$code'";
$result = query($sql);

if ($result && num_rows($result) > 0) {
    $coupon = fetch_assoc($result);

    // VULNERABLE: No atomic check-and-update
    if ($coupon['used_count'] >= $coupon['max_uses']) {
        echo json_encode(['success' => false, 'message' => 'Coupon expired']);
    } else {
        // Increment without proper locking
        query("UPDATE coupons SET used_count = used_count + 1 WHERE id = " . $coupon['id']);
        echo json_encode([
            'success' => true,
            'discount' => $coupon['discount_value'],
            'discount_type' => $coupon['discount_type']
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid coupon']);
}
?>
