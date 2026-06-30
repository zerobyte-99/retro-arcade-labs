<?php
// LAB: VULN-LFI-001 - Local File Inclusion via lang parameter
require_once __DIR__ . '/../includes/common.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

// VULNERABLE: Local File Inclusion without path sanitization
// LAB: VULN-LFI-001
// Payloads:
// - ?lang=../../../etc/passwd (read passwd)  
// - ?lang=../config/database (read config)
// - ?lang=php://filter/convert.base64-encode/resource=index (read source)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Translations - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
</head>
<body>
    <div style="background:#1a1a2e;padding:40px;max-width:800px;margin:50px auto;border:2px solid #ff00ff;border-radius:10px;">
        <h1 style="color:#ff00ff;text-shadow:0 0 10px #ff00ff;">🌍 Language Settings</h1>
        
        <form method="GET" style="margin:20px 0;">
            <label style="color:#ffff00;">Language:</label>
            <input type="text" name="lang" value="<?php echo htmlspecialchars($lang); ?>" style="background:#0a0a0f;color:#00fff5;border:1px solid #00fff5;padding:10px;width:200px;">
            <button type="submit" style="background:#ff00ff;color:#0a0a0f;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;">Load</button>
        </form>
        
        <div style="background:#0a0a0f;padding:20px;border:2px solid #00fff5;border-radius:5px;margin:20px 0;">
            <h2 style="color:#00fff5;">Content Preview:</h2>
            <?php
            // VULNERABLE: Reading file based on user input without sanitization
            // Uses path from document root for flexibility
            $base_path = '/var/www/html/';
            $content = @file_get_contents($base_path . $lang);
            if ($content !== false) {
                echo '<pre style="color:#00ff00;">' . htmlspecialchars($content) . '</pre>';
            } else {
                echo '<p style="color:#ff3333;">⚠️ Could not load: ' . htmlspecialchars($base_path.$lang) . '</p>';
                echo '<p>💡 Try: ?lang=etc/passwd or ?lang=config/database.php</p>';
            }
            ?>
        </div>
        
        <a href="/pages/index.php" style="color:#ffff00;">← Back</a>
    </div>
</body>
</html>
