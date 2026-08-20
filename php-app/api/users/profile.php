<?php
// LAB: VULN-CSRF-001, VULN-MASS-001 - Profile update without CSRF and with mass assignment
// LAB: VULN-IDOR-001 - Cross-account profile read (no ownership check)
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // VULNERABLE: IDOR - returns any user's profile by id, no check that the
    // requested id belongs to the logged-in session.
    // LAB: VULN-IDOR-001
    if (isset($_GET['id'])) {
        $target_id = intval($_GET['id']);
    } elseif (isset($_GET['user_id'])) {
        $target_id = intval($_GET['user_id']);
    } else {
        $target_id = $_SESSION['user_id'];
    }

    $sql = "SELECT * FROM users WHERE id = $target_id";
    $result = query($sql);

    if ($result && num_rows($result) > 0) {
        $user = fetch_assoc($result);
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
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
