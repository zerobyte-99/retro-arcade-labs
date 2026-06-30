<?php
// LAB: VULN-UPLOAD-002 - Weak File Type Validation
// LAB: VULN-XXE-001 - XXE in Avatar Upload
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
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'xml', 'svg'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowed_extensions)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// LAB: VULN-XXE-001 - XXE Vulnerability
// If XML or SVG is uploaded, parse it without disabling external entities
if ($extension === 'xml' || $extension === 'svg') {
    $xml_content = file_get_contents($file['tmp_name']);
    // VULNERABLE: No libxml_disable_entity_loader (deprecated), no DTD filtering
    // This allows external entity injection
    $xml = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NOENT);
    echo json_encode([
        'success' => true, 
        'message' => 'File parsed',
        'parsed_content' => $xml ? $xml->asXML() : 'Failed to parse'
    ]);
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
