<?php
// LAB: VULN-XSS-005 - Stored XSS in support tickets
require_once __DIR__ . '/../../includes/common.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';
    
    // VULNERABLE: Stored XSS in ticket body
    // LAB: VULN-XSS-005
    // Payload: <script>alert('XSS')</script>
    query("INSERT INTO tickets (user_id, subject, body) VALUES (" . $_SESSION['user_id'] . ", '$subject', '$body')");
    
    redirect('/pages/user/dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Support Ticket - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #ffff00; }
        input, textarea { width: 100%; padding: 10px; background: #0a0a0f; border: 1px solid #00fff5; color: #00fff5; box-sizing: border-box; }
        textarea { height: 150px; }
        button { padding: 15px 30px; background: #ff00ff; border: none; color: #0a0a0f; cursor: pointer; font-size: 1em; }
        button:hover { background: #00fff5; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE</div>
        <div><a href="/pages/user/dashboard.php">Dashboard</a><a href="/pages/logout.php">Logout</a></div>
    </nav>
    <div class="container">
        <h1>🎫 CREATE SUPPORT TICKET</h1>
        
        <!-- VULNERABLE: No XSS sanitization on input or output -->
        <form method="POST">
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="Enter subject">
            </div>
            <div class="form-group">
                <label>Description</label>
                <!-- VULNERABLE: No sanitization - XSS stored and reflected -->
                <textarea name="body" required placeholder="Describe your issue..."></textarea>
            </div>
            <button type="submit">Submit Ticket</button>
        </form>
        
        <p style="margin-top:20px;color:#666;">💡 Try: <script>alert('XSS')</script> in the description field</p>
    </div>
</body>
</html>
