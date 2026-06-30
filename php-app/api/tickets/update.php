<?php
// LAB: VULN-IDOR-003 - Modify Another User's Ticket
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = isset($_POST['ticket_id']) ? $_POST['ticket_id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    // VULNERABLE: IDOR - no check that ticket belongs to current user
    $sql = "UPDATE tickets SET status = '$status', body = '$body' WHERE id = $ticket_id";
    query($sql);

    echo json_encode(['success' => true, 'message' => 'Ticket updated']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>
