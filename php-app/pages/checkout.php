<?php
// Checkout page - LAB: VULN-BIZ-003 (client-side price manipulation)
require_once __DIR__ . '/../includes/common.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
    
    // VULNERABLE: Price from client not validated server-side
    // LAB: VULN-BIZ-003
    // Attack: Modify total_price in form or via curl
    
    $result = query("INSERT INTO orders (user_id, total, status) VALUES (" . $_SESSION['user_id'] . ", $total_price, 'completed')");
    if ($result) {
        $order_id = get_last_id();
        // Move cart items to order
        $cart_items = query("SELECT * FROM cart WHERE user_id = " . $_SESSION['user_id']);
        while ($item = fetch_assoc($cart_items)) {
            query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, " . $item['product_id'] . ", " . $item['quantity'] . ", " . $item['price'] . ")");
        }
        // Clear cart
        query("DELETE FROM cart WHERE user_id = " . $_SESSION['user_id']);
        $success = "Order #$order_id placed successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; }
        .card { background: #1a1a2e; border: 2px solid #ff00ff; padding: 30px; border-radius: 10px; }
        .total { font-size: 2em; color: #ffff00; margin: 20px 0; }
        button { padding: 15px 30px; background: #ff00ff; border: none; color: #0a0a0f; cursor: pointer; font-size: 1em; }
        button:hover { background: #00fff5; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE</div>
        <div><a href="/pages/cart.php">Back to Cart</a></div>
    </nav>
    <div class="container">
        <h1>💳 CHECKOUT</h1>
        <?php if (isset($success)): ?>
            <div class="card" style="background:#003300;border-color:#00ff00;">
                <h2 style="color:#00ff00;">✓ <?= e($success) ?></h2>
            </div>
        <?php else: ?>
            <div class="card">
                <form method="POST">
                    <?php
                    $cart = query("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = " . $_SESSION['user_id']);
                    $total = 0;
                    while ($item = fetch_assoc($cart)) {
                        $total += $item['price'] * $item['quantity'];
                        echo "<p>" . e($item['name']) . " x" . $item['quantity'] . " - $" . number_format($item['price'] * $item['quantity'], 2) . "</p>";
                    }
                    ?>
                    <div class="total">Total: $<?= number_format($total, 2) ?></div>
                    
                    <!-- VULNERABLE: Price sent by client, not validated -->
                    <input type="hidden" name="total_price" value="<?= $total ?>">
                    <button type="submit">COMPLETE ORDER</button>
                    
                    <p style="margin-top:20px;color:#666;font-size:0.8em;">💡 Try: Modify total_price in form to $0.01</p>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
