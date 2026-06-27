<?php
require_once __DIR__ . '/../includes/common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    if ($username && $password && $email) {
        // VULNERABLE: SQL injection possible in registration
        $result = query("INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', 'player')");
        if ($result) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Username may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; padding: 0; }
        .container { max-width: 400px; margin: 100px auto; padding: 40px; background: #1a1a2e; border: 2px solid #ff00ff; border-radius: 10px; }
        h1 { color: #ff00ff; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #ffff00; }
        input { width: 100%; padding: 10px; background: #0a0a0f; border: 1px solid #00fff5; color: #00fff5; box-sizing: border-box; }
        button { width: 100%; padding: 15px; background: #ff00ff; border: none; color: #0a0a0f; cursor: pointer; font-size: 1em; }
        button:hover { background: #00fff5; }
        .error, .success { text-align: center; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .error { background: #330000; border: 1px solid #ff3333; color: #ff3333; }
        .success { background: #003300; border: 1px solid #00ff00; color: #00ff00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 REGISTER 🎮</h1>
        <?php if (isset($error)): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <?php if (isset($success)): ?><div class="success"><?= e($success) ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <button type="submit">CREATE ACCOUNT</button>
        </form>
        <p style="text-align:center;margin-top:20px;"><a href="/pages/login.php" style="color:#00fff5;">Already have an account? Login</a></p>
    </div>
</body>
</html>
