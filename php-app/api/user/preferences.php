<?php
// LAB: VULN-DESERIAL-001 - PHP Object Injection via cookie
// Deserializes user preferences from cookie without validation
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// VULNERABLE: Unserializing user input from cookie
// LAB: VULN-DESERIAL-001
// Attack: Set cookie "user_prefs" to serialized payload like:
// O:15:"MaliciousClass":0:{} to trigger __wakeup or __destruct

$prefs = isset($_COOKIE['user_prefs']) ? $_COOKIE['user_prefs'] : 'a:0:{}';

// VULNERABLE: No validation before unserialize
$data = unserialize($prefs);

echo json_encode([
    'success' => true,
    'preferences' => $data,
    'raw' => $prefs
]);
?>
