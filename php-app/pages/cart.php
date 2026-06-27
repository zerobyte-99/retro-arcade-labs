<?php
// Shopping cart page
require_once __DIR__ . '/../includes/common.php';
require_login();

$message = '';

// Handle cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($action === 'add' && $product_id) {
        // VULNERABLE: SQL injection in cart item lookup
        // LAB: VULN-SQLI-006
        $result = query("SELECT * FROM cart WHERE user_id = " . $_SESSION['user_id'] . " AND product_id = $product_id");
        if ($result && num_rows($result) > 0) {
            query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = " . $_SESSION['user_id'] . " AND product_id = $product_id");
        } else {
            query("INSERT INTO cart (user_id, product_id, quantity) VALUES (" . $_SESSION['user_id'] . ", $product_id, 1)");
        }
        $message = 'Item added to cart!';
    }
    
    if ($action === 'remove' && $product_id) {
        query("DELETE FROM cart WHERE user_id = " . $_SESSION['user_id'] . " AND product_id = $product_id");
        $message = 'Item removed from cart!';
    }
    
    if ($action === 'update' && isset($_POST['quantity'])) {
        // VULNERABLE: Negative quantity allowed
        // LAB: VULN-BIZ-002
        $quantity = intval($_POST['quantity']);
        $cart_id = intval($_POST['cart_id']);
        query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = " . $_SESSION['user_id']);
        $message = 'Cart updated!';
    }
}

// Get cart items
$cart_result = query("SELECT c.*, p.name, p.price, p.image_url 
                     FROM cart c 
                     JOIN products p ON c.product_id = p.id 
                     WHERE c.user_id = " . $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; }
        .container { max-width: 900px; margin: 50px auto; padding: 20px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #333; }
        th { color: #ffff00; }
        .price { color: #ffff00; font-weight: bold; }
        .total { font-size: 1.5em; color: #ff00ff; margin-top: 20px; }
        .btn { padding: 10px 20px; background: #ff00ff; color: #0a0a0f; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #00fff5; }
        .message { background: #1a1a2e; padding: 15px; border: 2px solid #00fff5; margin-bottom: 20px; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
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
        </div>
    </nav>
    <div class="container">
        <h1>🛒 YOUR CART</h1>
        <?php if ($message): ?>
            <div class="message"><?= e($message) ?></div>
        <?php endif; ?>
        
        <table>
            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>
            <?php
            $cart_total = 0;
            while ($item = fetch_assoc($cart_result)):
                $item_total = $item['price'] * $item['quantity'];
                $cart_total += $item_total;
            ?>
                <tr>
                    <td><?= e($item['name']) ?></td>
                    <td class="price">$<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <form method="POST" action="/pages/cart.php?action=update&id=<?= $item['product_id'] ?>" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <!-- VULNERABLE: No validation against negative quantities -->
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:60px;padding:5px;background:#0a0a0f;border:1px solid #00fff5;color:#00fff5;">
                            <button type="submit" class="btn" style="padding:5px 10px;">Update</button>
                        </form>
                    </td>
                    <td class="price">$<?= number_format($item_total, 2) ?></td>
                    <td><a href="/pages/cart.php?action=remove&id=<?= $item['product_id'] ?>" class="btn" style="background:#ff3333;">Remove</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <div class="total">Cart Total: $<?= number_format($cart_total, 2) ?></div>
        
        <div style="margin-top:30px;">
            <a href="/pages/checkout.php" class="btn" style="font-size:1.2em;">💳 CHECKOUT</a>
        </div>
    </div>
</body>
</html>
