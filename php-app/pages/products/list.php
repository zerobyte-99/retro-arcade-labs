<?php
// Product catalog page
require_once __DIR__ . '/../../includes/common.php';

// VULNERABLE: SQL injection in category filter
// LAB: VULN-SQLI-005
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

if ($search) {
    // VULNERABLE: SQL injection in search
    // LAB: VULN-SQLI-002
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
    $page_title = "Search: " . e($search);
} elseif ($category) {
    // VULNERABLE: SQL injection in category
    $sql = "SELECT * FROM products WHERE category_id = $category";
    $page_title = "Category Products";
} else {
    $sql = "SELECT * FROM products";
    $page_title = "All Products";
}

$result = query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?> - Retro Arcade Labs</title>
    <link rel="stylesheet" href="/css/retro.css">
    <style>
        body { background: #0a0a0f; color: #00fff5; font-family: 'Courier New', monospace; margin: 0; padding: 0; }
        .header { background: #1a1a2e; padding: 20px; border-bottom: 2px solid #ff00ff; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .search-box { margin: 20px 0; }
        .search-box input { padding: 10px; width: 300px; background: #0a0a0f; border: 1px solid #00fff5; color: #00fff5; }
        .search-box button { padding: 10px 20px; background: #ff00ff; border: none; color: #0a0a0f; cursor: pointer; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product { background: #1a1a2e; border: 2px solid #00fff5; padding: 20px; border-radius: 10px; }
        .product h3 { color: #ff00ff; margin-top: 0; }
        .price { color: #ffff00; font-size: 1.3em; font-weight: bold; }
        .btn { display: inline-block; background: #ff00ff; color: #0a0a0f; padding: 10px 20px; text-decoration: none; margin-top: 10px; border-radius: 5px; }
        .nav { background: #1a1a2e; padding: 20px; display: flex; justify-content: space-between; }
        .nav a { color: #00fff5; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav class="nav">
        <div style="font-weight:bold;color:#ff00ff;">🎮 RETRO ARCADE</div>
        <div><a href="/pages/index.php">Home</a><a href="/pages/products/list.php">Products</a><a href="/pages/cart.php">Cart</a></div>
    </nav>
    <div class="container">
        <h1><?= $page_title ?></h1>
        <form class="search-box" method="GET">
            <input type="text" name="search" placeholder="Search products..." value="<?= isset($_GET['search']) ? e($_GET['search']) : '' ?>">
            <button type="submit">🔍 SEARCH</button>
        </form>
        <div class="product-grid">
            <?php while ($p = fetch_assoc($result)): ?>
                <div class="product">
                    <h3><?= e($p['name']) ?></h3>
                    <p><?= e($p['description']) ?></p>
                    <div class="price">$<?= number_format($p['price'], 2) ?></div>
                    <a href="/pages/products/detail.php?id=<?= $p['id'] ?>" class="btn">View</a>
                    <a href="/pages/cart.php?action=add&id=<?= $p['id'] ?>" class="btn">Add to Cart</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
