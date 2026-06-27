-- Retro Arcade Labs Seed Data
USE retro_arcade;

-- Create tables first
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role ENUM('guest','player','premium','moderator','admin') DEFAULT 'player',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(500),
    stock INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percent','fixed') DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL,
    min_order DECIMAL(10,2) DEFAULT 0,
    max_uses INT DEFAULT 1,
    used_count INT DEFAULT 0,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    status ENUM('open','pending','resolved') DEFAULT 'open',
    priority ENUM('low','medium','high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS ticket_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    body TEXT NOT NULL,
    rating INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed users
INSERT INTO users (username, password, email, role) VALUES 
('guest@example.local', 'Password123!', 'guest@example.local', 'guest'),
('player1@example.local', 'Password123!', 'player1@example.local', 'player'),
('player2@example.local', 'Password123!', 'player2@example.local', 'player'),
('premium@example.local', 'Password123!', 'premium@example.local', 'premium'),
('moderator@example.local', 'Password123!', 'moderator@example.local', 'moderator'),
('admin@example.local', 'AdminPassword123!', 'admin@example.local', 'admin');

-- Seed categories
INSERT INTO categories (name, description) VALUES 
('Arcade Games', 'Classic retro arcade games'),
('Racing Games', 'High speed racing games'),
('Fighting Games', 'Beat em up and fighting games'),
('Token Packs', 'Virtual arcade tokens'),
('Subscriptions', 'Premium memberships'),
('Merchandise', 'Arcade-themed physical items');

-- Seed products
INSERT INTO products (name, description, price, category_id, image_url, stock) VALUES 
('Neon Racer 3000', 'High-speed cyberpunk racing game', 49.99, 2, '/images/neon-racer.jpg', 100),
('Pixel Blaster', 'Classic shoot-em-up pixel game', 24.99, 1, '/images/pixel-blaster.jpg', 100),
('Token Pack x100', '100 arcade tokens', 9.99, 4, '/images/tokens.jpg', 1000),
('Premium Monthly', 'VIP perks + 500 tokens/month', 14.99, 5, '/images/premium.jpg', 100),
('Retro Joystick Pro', 'Professional controller', 79.99, 6, '/images/joystick.jpg', 50),
('Cyber Boxing', 'Fight in neon arenas', 39.99, 3, '/images/cyber-boxing.jpg', 100),
('Galactic Defender', 'Space shooter', 19.99, 1, '/images/galactic.jpg', 100),
('Token Pack x500', 'Best value tokens', 39.99, 4, '/images/tokens500.jpg', 500),
('VIP Annual Pass', 'Year of premium', 149.99, 5, '/images/vip.jpg', 50),
('Neon Hoodie', 'Glow hoodie', 59.99, 6, '/images/hoodie.jpg', 75);

-- Seed coupons
INSERT INTO coupons (code, discount_type, discount_value, min_order, max_uses) VALUES 
('WELCOME10', 'percent', 10, 0, 100),
('ARCADE20', 'percent', 20, 50, 50),
('SINGLEUSE', 'fixed', 5, 20, 1),
('VIP50', 'percent', 50, 100, 10);
