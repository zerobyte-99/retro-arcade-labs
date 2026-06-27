<?php
// Retro Arcade Labs - Landing Page
require_once __DIR__ . '/../includes/common.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Arcade Labs - Welcome</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body {
            background: #0a0a0f;
            color: #00fff5;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
        }
        .hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #0a0a0f 100%);
            padding: 100px 20px;
            text-align: center;
            border-bottom: 3px solid #ff00ff;
        }
        .hero h1 {
            font-size: 3em;
            text-shadow: 0 0 10px #00fff5, 0 0 20px #00fff5;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2em;
            color: #ff00ff;
        }
        .cta {
            display: inline-block;
            background: #ff00ff;
            color: #0a0a0f;
            padding: 15px 40px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 30px;
            border-radius: 5px;
        }
        .products {
            padding: 50px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .products h2 {
            text-align: center;
            color: #ffff00;
            text-shadow: 0 0 5px #ffff00;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .product-card {
            background: #1a1a2e;
            border: 2px solid #00fff5;
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px #00fff5;
        }
        .product-card h3 {
            color: #00fff5;
            margin-top: 0;
        }
        .product-card .price {
            color: #ffff00;
            font-size: 1.5em;
            font-weight: bold;
        }
        .nav {
            background: #1a1a2e;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff00ff;
        }
        .nav a {
            color: #00fff5;
            text-decoration: none;
            margin-left: 20px;
        }
        .nav a:hover {
            color: #ff00ff;
        }
        .scanlines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 0, 0, 0.1),
                rgba(0, 0, 0, 0.1) 1px,
                transparent 1px,
                transparent 2px
            );
            pointer-events: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="scanlines"></div>
    <nav class="nav">
        <div style="font-size: 1.5em; font-weight: bold; color: #ff00ff;">🎮 RETRO ARCADE LABS</div>
        <div>
            <a href="/pages/products/">Games</a>
            <a href="/pages/cart.php">Cart</a>
            <a href="/pages/login.php">Login</a>
            <a href="/pages/register.php">Register</a>
        </div>
    </nav>
    
    <div class="hero">
        <h1>🎮 INSERT COIN TO CONTINUE 🎮</h1>
        <p>Welcome to the ultimate cyberpunk marketplace for retro gaming enthusiasts</p>
        <a href="/pages/products/" class="cta">BROWSE GAMES</a>
    </div>
    
    <div class="products">
        <h2>🔥 FEATURED PRODUCTS 🔥</h2>
        <div class="product-grid">
            <?php
            $result = query("SELECT * FROM products LIMIT 8");
            if ($result) {
                while ($product = fetch_assoc($result)) {
                    echo '<div class="product-card">';
                    echo '<h3>' . e($product['name']) . '</h3>';
                    echo '<p>' . e($product['description']) . '</p>';
                    echo '<div class="price">$' . number_format($product['price'], 2) . '</div>';
                    echo '<a href="/pages/products/detail.php?id=' . $product['id'] . '" class="cta" style="margin-top:10px;padding:10px 20px;display:inline-block;">View Details</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <footer style="background:#1a1a2e;padding:20px;text-align:center;border-top:2px solid #ff00ff;">
        <p style="color:#00fff5;">© 2026 Retro Arcade Labs - All Rights Reserved</p>
        <p style="color:#ff00ff;">INTENTIONALLY VULNERABLE - FOR EDUCATIONAL USE ONLY</p>
    </footer>
</body>
</html>
