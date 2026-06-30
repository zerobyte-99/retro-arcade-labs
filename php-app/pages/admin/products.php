<?php
// LAB: VULN-XXE-002 - XXE in Product Import
require_once __DIR__ . '/../../includes/common.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/pages/login.php?return=/pages/admin/products.php');
}

// Check if user is admin
if (!is_admin()) {
    echo '<h1>Access Denied</h1><p>Admin only page</p>';
    exit;
}

$message = '';
$import_result = '';

// Handle XML import
// LAB: VULN-XXE-002
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import'])) {
    $file = $_FILES['import'];
    
    // VULNERABLE: Parsing XML without disabling external entities
    // LIBXML_NOENT flag causes external entities to be expanded
    $xml_content = file_get_contents($file['tmp_name']);
    $xml = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NOENT);
    
    if ($xml) {
        $import_result = '<div class="alert alert-success">XML Parsed Successfully</div>';
        $import_result .= '<pre>' . htmlspecialchars($xml->asXML()) . '</pre>';
        
        // Try to extract product data
        if (isset($xml->product)) {
            foreach ($xml->product as $product) {
                $import_result .= '<p>Product: ' . htmlspecialchars((string)$product->name) . ' - $' . htmlspecialchars((string)$product->price) . '</p>';
            }
        }
    } else {
        $import_result = '<div class="alert alert-error">Failed to parse XML</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Management - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 5px; }
        .alert-success { background: #1a3a1a; border: 2px solid #00ff00; color: #00ff00; }
        .alert-error { background: #3a1a1a; border: 2px solid #ff0000; color: #ff0000; }
        pre { background: #1a1a2e; padding: 15px; overflow-x: auto; border-radius: 5px; }
        form { background: #1a1a2e; padding: 20px; border: 2px solid #00fff5; border-radius: 10px; margin: 20px 0; }
        input[type="file"] { background: #0a0a0f; color: #00fff5; border: 1px solid #00fff5; padding: 10px; width: 100%; margin: 10px 0; }
        button { background: #ff00ff; color: #0a0a0f; padding: 15px 30px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        button:hover { background: #00fff5; }
        .back-link { color: #ffff00; margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 Product Management</h1>
        <p>Admin Panel - Product Import</p>
        
        <?php if ($import_result): ?>
            <?php echo $import_result; ?>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <h2>📥 Import Products from XML</h2>
            <p>Upload an XML file to import products. Supports XXE format.</p>
            
            <input type="file" name="import" accept=".xml" required>
            <button type="submit">Import Products</button>
            
            <p style="color: #ff00ff; margin-top: 10px;">
                💡 XXE Lab: Try injecting external entities to read local files
            </p>
        </form>
        
        <pre style="color: #ffff00;">
Example XXE Payload:
&lt;?xml version="1.0"?&gt;
&lt;!DOCTYPE foo [&lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;]&gt;
&lt;products&gt;
    &lt;product&gt;
        &lt;name&gt;&amp;xxe;&lt;/name&gt;
        &lt;price&gt;0&lt;/price&gt;
    &lt;/product&gt;
&lt;/products&gt;
        </pre>
        
        <a href="/pages/admin/index.php" class="back-link">← Back to Admin Panel</a>
    </div>
</body>
</html>
