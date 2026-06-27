<?php
// LAB: VULN-SQLI-001 - SQL Injection Login Bypass
require_once __DIR__ . '/../includes/common.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // VULNERABLE: SQL injection in login
    // LAB: VULN-SQLI-001
    // Bypass: admin' -- or ' OR '1'='1
    if (login($username, $password)) {
        $return_url = isset($_GET['return']) ? $_GET['return'] : '/pages/index.php';
        redirect($return_url);
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; padding: 0; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 40px; background: #1a1a2e; border: 2px solid #ff00ff; border-radius: 10px; }
        h1 { color: #ff00ff; text-align: center; text-shadow: 0 0 10px #ff00ff; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #ffff00; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; background: #0a0a0f; border: 1px solid #00fff5; color: #00fff5; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 15px; background: #ff00ff; color: #0a0a0f; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1em; }
        button:hover { background: #00fff5; }
        .error { color: #ff3333; text-align: center; margin-bottom: 20px; }
        .hint { color: #666; font-size: 0.8em; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🎮 LOGIN 🎮</h1>
        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/pages/login.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit">INSERT COIN</button>
        </form>
        
        <p class="hint">💡 Try: admin' -- (SQL Injection Bypass Lab)</p>
        
        <p style="text-align:center;margin-top:20px;">
            <a href="/pages/register.php" style="color:#00fff5;">No account? Register here</a>
        </p>
    </div>
</body>
</html>
