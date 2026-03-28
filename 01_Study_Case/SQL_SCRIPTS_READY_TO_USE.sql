-- ============================================
-- BASIC MYSQL MASTERCLASS
-- SQL Scripts Ready to Use
-- ============================================
-- Database: TechStore Indonesia
-- Purpose: E-Commerce Platform
-- Created: 2026-03-29
-- ============================================

-- ============ SECTION 1: DATABASE SETUP ============

-- Create Database
DROP DATABASE IF EXISTS techstore;
CREATE DATABASE techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techstore;

-- ============ SECTION 2: CREATE TABLES ============

-- Table: Categories
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Customers
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(10),
    country VARCHAR(100) DEFAULT 'Indonesia',
    registered_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_registered_date (registered_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Products
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(15,2) NOT NULL,
    cost DECIMAL(15,2),
    stock INT DEFAULT 0,
    sku VARCHAR(50) UNIQUE NOT NULL,
    weight DECIMAL(8,3),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_price (price),
    INDEX idx_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Orders
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(15,2) NOT NULL,
    tax_amount DECIMAL(15,2) DEFAULT 0,
    shipping_cost DECIMAL(15,2) DEFAULT 0,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('bank_transfer', 'credit_card', 'e_wallet', 'cod') DEFAULT 'bank_transfer',
    shipping_address TEXT,
    notes TEXT,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    INDEX idx_customer (customer_id),
    INDEX idx_order_date (order_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Order Items
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Reviews
CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    helpful_count INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    INDEX idx_product (product_id),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: Inventory Log
CREATE TABLE inventory_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    quantity_change INT NOT NULL,
    transaction_type ENUM('purchase', 'return', 'stock_in', 'damage', 'adjustment') DEFAULT 'purchase',
    reference_id INT,
    note TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    INDEX idx_product (product_id),
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============ SECTION 3: INSERT DATA ============

-- Insert Categories
INSERT INTO categories (name, description) VALUES
('Laptop & Desktop', 'Personal computers and laptops'),
('Mobile Devices', 'Smartphones and tablets'),
('Accessories', 'Computer and mobile accessories'),
('Networking', 'Network equipment and devices'),
('Storage', 'HDD, SSD, and storage solutions'),
('Monitors', 'Display devices for computers'),
('Keyboards & Mice', 'Input devices for computers'),
('Power & Cables', 'Power supplies and cables');

-- Insert Products
INSERT INTO products (category_id, name, description, price, cost, stock, sku, status) VALUES
(1, 'MacBook Pro 14"', 'Powerful laptop for professionals', 25000000, 18000000, 15, 'MBP-14-2024', 'active'),
(1, 'Dell XPS 13', 'Ultrabook with stunning display', 16000000, 12000000, 25, 'DELL-XPS-13', 'active'),
(1, 'Lenovo ThinkPad X1', 'Business laptop with great keyboard', 18000000, 13000000, 20, 'LNV-TP-X1', 'active'),
(2, 'iPhone 15 Pro', 'Latest Apple smartphone', 18000000, 14000000, 50, 'IP15-PRO', 'active'),
(2, 'Samsung Galaxy S24', 'Flagship Android phone', 14000000, 10000000, 75, 'SGS24-BLK', 'active'),
(2, 'Google Pixel 8', 'Google smartphone with AI features', 12000000, 9000000, 40, 'GP8-BLK', 'active'),
(3, 'USB-C Cable 2m', 'Durable charging cable', 150000, 50000, 500, 'USB-C-2M', 'active'),
(3, 'Wireless Mouse Logitech', 'Ergonomic wireless mouse', 350000, 150000, 200, 'LOG-MOUSE-WL', 'active'),
(3, 'USB Hub 7-Port', 'Expandable USB hub', 450000, 200000, 150, 'HUB-7PORT', 'active'),
(4, 'TP-Link WiFi 6 Router', 'High-speed WiFi 6 router', 2500000, 1500000, 30, 'TPLINK-AX6', 'active'),
(5, 'Samsung SSD 1TB', 'Fast NVMe SSD storage', 1200000, 700000, 100, 'SAM-SSD-1TB', 'active'),
(5, 'WD External HDD 2TB', 'Portable external storage', 800000, 400000, 80, 'WD-EXT-2TB', 'active'),
(6, 'Dell UltraSharp 27"', '4K monitor for professionals', 8000000, 5000000, 25, 'DELL-27K', 'active'),
(7, 'Mechanical Keyboard RGB', 'Gaming keyboard with RGB lights', 1200000, 600000, 60, 'KBD-RGB-MEC', 'active'),
(8, 'Corsair PSU 850W', 'Power supply 850 watts', 2000000, 1200000, 40, 'CORSAIR-850W', 'active');

-- Insert Customers
INSERT INTO customers (name, email, phone, address, city, postal_code) VALUES
('Budi Santoso', 'budi@example.com', '081234567890', 'Jl. Merdeka No. 123', 'Jakarta', '12345'),
('Siti Rahman', 'siti@example.com', '081234567891', 'Jl. Sudirman No. 456', 'Bandung', '40123'),
('Ahmad Wijaya', 'ahmad@example.com', '081234567892', 'Jl. Gatot Subroto No. 789', 'Surabaya', '60123'),
('Rini Kusuma', 'rini@example.com', '081234567893', 'Jl. Diponegoro No. 321', 'Yogyakarta', '55123'),
('Hendra Prasetya', 'hendra@example.com', '081234567894', 'Jl. Ahmad Yani No. 654', 'Medan', '20123'),
('Dwi Hartanto', 'dwi@example.com', '081234567895', 'Jl. Gatot Subroto No. 111', 'Jakarta', '12321'),
('Nisa Ramadani', 'nisa@example.com', '081234567896', 'Jl. Dipati Ukur No. 222', 'Bandung', '40234'),
('Eka Prasetyo', 'eka@example.com', '081234567897', 'Jl. Pemuda No. 333', 'Surabaya', '60234'),
('Ratna Sari', 'ratna@example.com', '081234567898', 'Jl. Malioboro No. 444', 'Yogyakarta', '55234'),
('Bambang Suryanto', 'bambang@example.com', '081234567899', 'Jl. Merdeka No. 555', 'Medan', '20234');

-- Insert Orders
INSERT INTO orders (customer_id, total_amount, tax_amount, shipping_cost, discount_amount, payment_method, status) VALUES
(1, 25150000, 375000, 50000, 0, 'bank_transfer', 'delivered'),
(2, 16200000, 300000, 75000, 0, 'credit_card', 'shipped'),
(3, 18450000, 350000, 50000, 0, 'e_wallet', 'delivered'),
(1, 1575000, 75000, 25000, 100000, 'credit_card', 'confirmed'),
(4, 41200000, 700000, 100000, 0, 'bank_transfer', 'pending'),
(5, 3200000, 50000, 0, 200000, 'e_wallet', 'delivered'),
(2, 8000000, 200000, 50000, 0, 'cod', 'shipped'),
(3, 2400000, 100000, 0, 0, 'bank_transfer', 'delivered'),
(6, 27000000, 450000, 75000, 0, 'credit_card', 'delivered'),
(7, 1500000, 75000, 25000, 0, 'e_wallet', 'confirmed'),
(8, 35000000, 600000, 100000, 500000, 'bank_transfer', 'shipped'),
(9, 1800000, 100000, 0, 200000, 'cod', 'delivered'),
(10, 5200000, 150000, 50000, 0, 'credit_card', 'pending'),
(4, 950000, 50000, 0, 0, 'e_wallet', 'delivered'),
(5, 16100000, 300000, 75000, 0, 'bank_transfer', 'shipped');

-- Insert Order Items
INSERT INTO order_items (order_id, product_id, quantity, unit_price, discount_percent) VALUES
(1, 1, 1, 25000000, 0),
(2, 2, 1, 16000000, 0),
(3, 3, 1, 18000000, 0),
(4, 8, 1, 350000, 0),
(4, 7, 2, 150000, 0),
(5, 1, 1, 25000000, 5),
(5, 4, 1, 18000000, 0),
(6, 10, 1, 2500000, 0),
(6, 12, 1, 800000, 0),
(7, 13, 1, 8000000, 0),
(8, 11, 1, 1200000, 0),
(9, 1, 1, 25000000, 0),
(9, 4, 1, 2000000, 0),
(10, 9, 1, 450000, 0),
(10, 14, 1, 1200000, 0),
(11, 2, 1, 16000000, 0),
(11, 5, 1, 14000000, 0),
(11, 15, 1, 2000000, 0),
(12, 7, 2, 150000, 0),
(13, 11, 2, 1200000, 0),
(13, 12, 1, 800000, 0),
(14, 3, 1, 350000, 0),
(15, 2, 1, 16000000, 0),
(15, 13, 1, 8000000, 0),
(15, 14, 1, 1200000, 0),
(15, 9, 2, 450000, 0);

-- Insert Reviews
INSERT INTO reviews (product_id, customer_id, rating, comment) VALUES
(1, 1, 5, 'Produk sangat bagus, tidak ada yang perlu dikomplain'),
(1, 2, 4, 'Bagus tapi agak mahal'),
(1, 6, 5, 'Terbaik di kelasnya!'),
(2, 3, 5, 'Kualitas terbaik, pengiriman cepat'),
(2, 8, 4, 'Laptop ringan dan cepat'),
(3, 2, 5, 'Keyboard nyaman, layar sempurna'),
(4, 1, 4, 'Smartphone yang andal'),
(4, 4, 5, 'Sempurna! Sesuai ekspektasi'),
(4, 7, 5, 'Kamera bagus'),
(5, 3, 4, 'Bagus tapi baterai cepat habis'),
(5, 9, 5, 'Harga sesuai kualitas'),
(6, 5, 4, 'Phone bagus untuk harga segini'),
(6, 10, 5, 'Cepat dan responsif'),
(8, 1, 5, 'Mouse sangat nyaman digunakan'),
(8, 6, 4, 'Bagus, tapi wheel perlu perbaikan'),
(10, 2, 5, 'WiFi6 sangat cepat!'),
(10, 7, 5, 'Stabil dan coverage luas'),
(11, 3, 5, 'SSD super cepat'),
(11, 8, 4, 'Cepat loading'),
(12, 4, 4, 'Storage cukup, harga terjangkau'),
(12, 9, 5, 'Portable dan reliable'),
(13, 5, 5, 'Monitor IPS warna akurat'),
(13, 10, 5, 'Sangat jernih dan terang'),
(14, 1, 5, 'Keyboard mechanical terbaik'),
(14, 6, 4, 'Enak diketik walaupun noisy'),
(15, 2, 5, 'PSU stabil dan powerfull');

-- Insert Inventory Logs
INSERT INTO inventory_log (product_id, quantity_change, transaction_type, reference_id, note) VALUES
(1, -1, 'purchase', 1, 'Order #1'),
(2, -1, 'purchase', 2, 'Order #2'),
(3, -1, 'purchase', 3, 'Order #3'),
(8, -1, 'purchase', 4, 'Order #4'),
(7, -2, 'purchase', 4, 'Order #4'),
(1, -1, 'purchase', 5, 'Order #5'),
(4, -1, 'purchase', 5, 'Order #5'),
(10, -1, 'purchase', 6, 'Order #6'),
(12, -1, 'purchase', 6, 'Order #6'),
(15, +10, 'stock_in', NULL, 'Stock replenishment'),
(1, +5, 'stock_in', NULL, 'Stock replenishment'),
(4, +20, 'stock_in', NULL, 'Stock replenishment');

-- Show table data
SELECT 'Database setup completed successfully!' AS status;
SELECT COUNT(*) as total_categories FROM categories;
SELECT COUNT(*) as total_products FROM products;
SELECT COUNT(*) as total_customers FROM customers;
SELECT COUNT(*) as total_orders FROM orders;
SELECT COUNT(*) as total_reviews FROM reviews;
