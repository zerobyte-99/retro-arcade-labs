<?php
// LAB: VULN-UPLOAD-002 - Weak File Type Validation
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/common.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_FILES['avatar'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['avatar'];
$target_dir = __DIR__ . '/../../uploads/';

// VULNERABLE: Only checks extension, not actual file content
// LAB: VULN-UPLOAD-002
// Can upload: shell.php, shell.jpg.php, etc.
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowed_extensions)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// VULNERABLE: Path traversal possible
// LAB: VULN-UPLOAD-001
$filename = basename($file['name']);
$target_path = $target_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $target_path)) {
    echo json_encode(['success' => true, 'message' => 'File uploaded', 'path' => '/uploads/' . $filename]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Upload failed']);
}
?>
