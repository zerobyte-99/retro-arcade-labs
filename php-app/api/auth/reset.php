<?php
// LAB: VULN-AUTH-003 - Password Reset Token Exposure
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Generate reset token (vulnerable - predictable and exposed in response)
    $token = bin2hex(random_bytes(16));
    $token_hash = hash('sha256', $token);

    // Store token in logs (vulnerable - token in plaintext)
    error_log("PASSWORD RESET: $email -> token: $token");

    echo json_encode([
        'success' => true,
        'message' => 'Reset link generated',
        // VULNERABLE: Token exposed in response
        'reset_token' => $token,
        'reset_link' => "/pages/reset.php?token=$token"
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>
