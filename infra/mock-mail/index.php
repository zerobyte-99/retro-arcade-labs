<?php
// Simple mock mail UI
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mock Mail - Retro Arcade Labs</title>
    <style>
        body { background: #1a1a2e; color: #00fff5; font-family: monospace; padding: 20px; }
        h1 { color: #ff00ff; }
        .email { background: #0a0a0f; border: 1px solid #00fff5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .email strong { color: #ffff00; }
    </style>
</head>
<body>
    <h1>📧 Mock Mail Catcher</h1>
    <p>All sent emails appear here (for SSRF lab testing)</p>
    <div style="background:#0a0a0f;padding:20px;border-radius:10px;">
        <p style="color:#666;">No emails captured yet.</p>
        <p>Use the image fetcher or webhook tester to send requests to:</p>
        <code>http://mock-mail:11025/</code>
    </div>
</body>
</html>
