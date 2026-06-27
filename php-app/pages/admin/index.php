<?php
require_once __DIR__ . '/../../includes/common.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; }
        .dashboard { max-width: 1200px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: #1a1a2e; border: 2px solid #00fff5; padding: 30px; border-radius: 10px; text-align: center; }
        .card h2 { color: #ffff00; margin-top: 0; }
        .card a { display: block; margin-top: 20px; padding: 15px; background: #ff00ff; color: #0a0a0f; text-decoration: none; border-radius: 5px; }
        .card a:hover { background: #00fff5; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; display:flex;justify-content:space-between; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
        .stat { font-size: 3em; color: #ffff00; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE - ADMIN PANEL</div>
        <div><a href="/pages/user/dashboard.php">User Dashboard</a><a href="/pages/logout.php">Logout</a></div>
    </nav>
    <div class="dashboard">
        <h1>⚙️ ADMIN DASHBOARD</h1>
        <div class="grid">
            <div class="card">
                <h2>👥 Users</h2>
                <div class="stat"><?php $r = query("SELECT COUNT(*) as c FROM users"); echo fetch_assoc($r)['c']; ?></div>
                <a href="/pages/admin/users.php">Manage Users</a>
            </div>
            <div class="card">
                <h2>📦 Orders</h2>
                <div class="stat"><?php $r = query("SELECT COUNT(*) as c FROM orders"); echo fetch_assoc($r)['c']; ?></div>
                <a href="/pages/admin/orders.php">Manage Orders</a>
            </div>
            <div class="card">
                <h2>🎮 Products</h2>
                <div class="stat"><?php $r = query("SELECT COUNT(*) as c FROM products"); echo fetch_assoc($r)['c']; ?></div>
                <a href="/pages/admin/products.php">Manage Products</a>
            </div>
            <div class="card">
                <h2>🎫 Tickets</h2>
                <div class="stat"><?php $r = query("SELECT COUNT(*) as c FROM tickets WHERE status='open'"); echo fetch_assoc($r)['c']; ?></div>
                <a href="/pages/admin/tickets.php">Manage Tickets</a>
            </div>
            <div class="card">
                <h2>🔧 Diagnostics</h2>
                <p>Run system commands</p>
                <a href="/pages/admin/diagnostics.php">Run Diagnostics</a>
            </div>
            <div class="card">
                <h2>🔍 Cache Inspector</h2>
                <p>View Redis cache</p>
                <a href="/pages/admin/cache.php">Inspect Cache</a>
            </div>
        </div>
    </div>
</body>
</html>
