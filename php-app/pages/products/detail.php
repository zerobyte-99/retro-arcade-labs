<?php
// Product detail page with reflected XSS
require_once __DIR__ . '/../../includes/common.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// VULNERABLE: No SQL injection protection but ID is intval so relatively safe
// But we have XSS vulnerability here
$result = query("SELECT * FROM products WHERE id = $product_id");
$product = fetch_assoc($result);

if (!$product) {
    die("Product not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- LAB: VULN-XSS-002 - Reflected XSS via query parameter -->
    <title><?= e($product['name']) ?> - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .product-detail { background: #1a1a2e; border: 2px solid #ff00ff; padding: 40px; border-radius: 10px; }
        h1 { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
        .price { color: #ffff00; font-size: 2em; font-weight: bold; margin: 20px 0; }
        .btn { display: inline-block; background: #ff00ff; color: #0a0a0f; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #00fff5; }
        .nav { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE</div>
        <div><a href="/pages/index.php">Home</a><a href="/pages/products/list.php">Products</a><a href="/pages/cart.php">Cart</a></div>
    </nav>
    <div class="container">
        <div class="product-detail">
            <!-- VULNERABLE: XSS via id parameter if not properly escaped -->
            <p style="color:#666;">Product ID: <?= $product_id ?></p>
            <h1><?= e($product['name']) ?></h1>
            <p><?= e($product['description']) ?></p>
            <div class="price">$<?= number_format($product['price'], 2) ?></div>
            <p>Stock: <?= $product['stock'] ?> available</p>
            <a href="/pages/cart.php?action=add&id=<?= $product['id'] ?>" class="btn">🛒 ADD TO CART</a>
        </div>
        
        <div style="margin-top:30px;background:#1a1a2e;padding:20px;border-radius:10px;">
            <h3>💬 Comments & Reviews</h3>
            <form method="POST" action="/api/comments/">
                <textarea name="body" placeholder="Write a review..." style="width:100%;height:80px;background:#0a0a0f;border:1px solid #00fff5;color:#00fff5;padding:10px;"></textarea>
                <button type="submit" style="margin-top:10px;padding:10px 20px;background:#ff00ff;border:none;color:#0a0a0f;cursor:pointer;">Submit Review</button>
            </form>
            <!-- LAB: VULN-XSS-004 - Stored XSS in comments display -->
            <?php
            $comments = query("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.product_id = $product_id");
            while ($c = fetch_assoc($comments)): ?>
                <div style="background:#0a0a0f;padding:15px;margin-top:10px;border-left:3px solid #ff00ff;">
                    <strong style="color:#ffff00;"><?= e($c['username']) ?></strong>
                    <!-- VULNERABLE: Body not properly escaped for stored XSS -->
                    <p><?= $c['body'] ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
