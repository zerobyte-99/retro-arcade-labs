<?php
// LAB: VULN-RCE-002 - Command Injection in Diagnostics
require_once __DIR__ . '/../../includes/common.php';
require_role('admin');

$output = '';
$error = '';

if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    
    // VULNERABLE: Command injection via cmd parameter
    // LAB: VULN-RCE-002
    // Payloads: 
    // - ?cmd=whoami
    // - ?cmd=cat /etc/passwd
    // - ?cmd=ls -la /var/www/html
    
    $output = shell_exec($cmd . " 2>&1");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diagnostics - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; }
        .container { max-width: 900px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .form-group { margin-bottom: 20px; }
        input { width: 100%; padding: 10px; background: #0a0a0f; border: 1px solid #ff00ff; color: #00fff5; box-sizing: border-box; font-family: 'Courier New', monospace; }
        button { padding: 10px 20px; background: #ff00ff; border: none; color: #0a0a0f; cursor: pointer; }
        button:hover { background: #00fff5; }
        pre { background: #0a0a0f; border: 2px solid #ff00ff; padding: 20px; overflow-x: auto; color: #00ff00; }
        .warning { background: #331111; border: 2px solid #ff3333; padding: 15px; margin-bottom: 20px; color: #ff3333; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE - ADMIN</div>
        <div><a href="/pages/admin/">Dashboard</a><a href="/pages/user/dashboard.php">User Dashboard</a></div>
    </nav>
    <div class="container">
        <h1>🔧 SYSTEM DIAGNOSTICS</h1>
        
        <div class="warning">⚠️ ADMIN ONLY - COMMAND INJECTION VULNERABLE</div>
        
        <form method="GET">
            <div class="form-group">
                <label>Enter diagnostic command:</label>
                <input type="text" name="cmd" value="<?= isset($_GET['cmd']) ? e($_GET['cmd']) : 'whoami' ?>" placeholder="e.g., whoami, ls, cat /etc/passwd">
            </div>
            <button type="submit">EXECUTE</button>
        </form>
        
        <?php if ($output): ?>
            <h2 style="color:#ffff00;">Output:</h2>
            <pre><?= e($output) ?></pre>
        <?php endif; ?>
        
        <p style="margin-top:20px;color:#666;">💡 Try: ?cmd=cat /etc/hostname or ?cmd=ls -la /var/www/html</p>
    </div>
</body>
</html>
