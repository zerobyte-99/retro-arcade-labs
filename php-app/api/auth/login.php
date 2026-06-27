<?php
// LAB: VULN-SQLI-001 - SQL Injection Login Bypass
// This is the primary SQL injection vulnerability for authentication bypass

header('Content-Type: application/json');

// Include common functions
require_once __DIR__ . '/../../includes/common.php';

// Handle JSON or form data
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$username = isset($input['username']) ? $input['username'] : '';
$password = isset($input['password']) ? $input['password'] : '';

// VULNERABLE: Direct string interpolation in SQL query
// LAB: VULN-SQLI-001
// This allows SQL injection bypass with payloads like:
// - admin' --
// - ' OR '1'='1
// - ' OR 1=1 --
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

$result = query($sql);

if ($result && num_rows($result) > 0) {
    $user = fetch_assoc($result);
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid credentials'
    ]);
}
?>
