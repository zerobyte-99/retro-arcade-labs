<?php
// LAB: VULN-CSRF-001, VULN-MASS-001 - Profile update without CSRF and with mass assignment
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { $input = $_POST; }

// VULNERABLE: No CSRF validation
// LAB: VULN-CSRF-001

// VULNERABLE: Mass assignment - can modify any field including role
// LAB: VULN-MASS-001
$user_id = $_SESSION['user_id'];

// Build update query from all input fields
$updates = [];
foreach ($input as $key => $value) {
    if ($key === 'user_id') continue; // Don't update ID
    $updates[] = "$key = '" . escape($value) . "'";
}

if (!empty($updates)) {
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $user_id";
    if (query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Profile updated']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No fields to update']);
}
?>
