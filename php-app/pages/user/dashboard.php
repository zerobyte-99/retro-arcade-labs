<?php
// User dashboard - LAB: VULN-CSRF-001 (no CSRF protection)
require_once __DIR__ . '/../../includes/common.php';
require_login();

$user = get_current_user_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; }
        .dashboard { max-width: 1000px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .card { background: #1a1a2e; border: 2px solid #00fff5; padding: 30px; margin-bottom: 20px; border-radius: 10px; }
        .card h2 { color: #ffff00; margin-top: 0; }
        .btn { display: inline-block; background: #ff00ff; color: #0a0a0f; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .btn:hover { background: #00fff5; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; display:flex;justify-content:space-between; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
        .role-badge { background: #ff00ff; color: #0a0a0f; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE</div>
        <div>
            <a href="/pages/index.php">Home</a>
            <a href="/pages/products/list.php">Products</a>
            <a href="/pages/cart.php">Cart</a>
            <a href="/pages/user/dashboard.php">Dashboard</a>
            <?php if (can_access_admin()): ?>
                <a href="/pages/admin/" style="color:#ffff00;">Admin</a>
            <?php endif; ?>
            <a href="/pages/logout.php">Logout</a>
        </div>
    </nav>
    <div class="dashboard">
        <h1>🎮 WELCOME BACK, <?= e($user['username']) ?>!</h1>
        
        <div class="card">
            <h2>👤 Profile</h2>
            <p><strong>Username:</strong> <?= e($user['username']) ?></p>
            <p><strong>Email:</strong> <?= e($user['email']) ?></p>
            <p><strong>Role:</strong> <span class="role-badge"><?= strtoupper(e($user['role'])) ?></span></p>
            
            <!-- LAB: VULN-CSRF-001 - No CSRF token on profile update -->
            <form method="POST" action="/api/users/profile.php" style="margin-top:20px;">
                <div style="margin-bottom:15px;">
                    <label style="display:block;margin-bottom:5px;color:#ffff00;">Email</label>
                    <input type="email" name="email" value="<?= e($user['email']) ?>" style="width:100%;max-width:300px;padding:10px;background:#0a0a0f;border:1px solid #00fff5;color:#00fff5;">
                </div>
                <!-- VULNERABLE: No CSRF protection -->
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
        
        <div class="card">
            <h2>📦 Recent Orders</h2>
            <?php
            $orders = query("SELECT * FROM orders WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY created_at DESC LIMIT 5");
            if ($orders && num_rows($orders) > 0):
                while ($order = fetch_assoc($orders)):
            ?>
                <div style="background:#0a0a0f;padding:15px;margin-bottom:10px;border-left:3px solid #ff00ff;">
                    <strong>Order #<?= $order['id'] ?></strong> - $<?= number_format($order['total'], 2) ?> - 
                    <span style="color:<?= $order['status']=='completed'?'#00ff00':'#ffff00' ?>"><?= strtoupper($order['status']) ?></span>
                    <span style="color:#666;"> - <?= date('M j, Y', strtotime($order['created_at'])) ?></span>
                </div>
            <?php endwhile; else: ?>
                <p>No orders yet. <a href="/pages/products/list.php" style="color:#00fff5;">Start shopping!</a></p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>🎫 Support Tickets</h2>
            <p><a href="/pages/support/create.php" class="btn">Create New Ticket</a></p>
            <?php
            $tickets = query("SELECT * FROM tickets WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY created_at DESC LIMIT 5");
            if ($tickets && num_rows($tickets) > 0):
                while ($ticket = fetch_assoc($tickets)):
            ?>
                <div style="background:#0a0a0f;padding:15px;margin-bottom:10px;border-left:3px solid #00fff5;">
                    <strong><?= e($ticket['subject']) ?></strong>
                    <span style="color:<?= $ticket['status']=='open'?'#00ff00':'#ffff00' ?>"> - <?= strtoupper($ticket['status']) ?></span>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</body>
</html>
