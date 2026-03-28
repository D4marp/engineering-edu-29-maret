# STUDY CASE: E-Commerce Platform Database Management
## Basic MySQL Masterclass: Unlock the Power of Data

---

## 📋 OVERVIEW

Anda ditugaskan merancang dan mengelola database untuk platform e-commerce **"TechStore Indonesia"**. Platform ini melayani jutaan transaksi per bulan dengan ribuan produk dan puluhan ribu pelanggan aktif.

### Tantangan Bisnis:
- 🛍️ 50,000+ produk dengan berbagai kategori
- 👥 100,000+ pelanggan aktif per bulan
- 💳 10,000+ transaksi per hari
- 📊 Laporan real-time yang kompleks
- ⚡ Response time harus < 2 detik

---

## 🎯 TUJUAN PEMBELAJARAN

Melalui study case ini, Anda akan belajar:
1. **Fundamental SQL** - DDL, DML, DCL operations
2. **Advanced Queries** - JOINs, Subqueries, CTEs
3. **Database Optimization** - Indexing & Query Performance
4. **Real-World Integration** - Data aggregation & reporting
5. **Best Practices** - Data normalization & security

---

## 📐 PART 1: DATABASE DESIGN

### 1.1 Entity Relationship Diagram (ERD)

```
CUSTOMERS
├── customer_id (PK)
├── name
├── email
├── phone
├── address
├── created_at
└── status

PRODUCTS
├── product_id (PK)
├── name
├── category_id (FK)
├── price
├── stock
├── description
└── created_at

CATEGORIES
├── category_id (PK)
├── name
└── description

ORDERS
├── order_id (PK)
├── customer_id (FK)
├── order_date
├── total_amount
├── status
└── payment_method

ORDER_ITEMS
├── order_item_id (PK)
├── order_id (FK)
├── product_id (FK)
├── quantity
└── unit_price

REVIEWS
├── review_id (PK)
├── product_id (FK)
├── customer_id (FK)
├── rating (1-5)
├── comment
└── created_at

INVENTORY_LOG
├── log_id (PK)
├── product_id (FK)
├── quantity_change
├── transaction_type (in/out)
└── timestamp
```

### 1.2 Normalized Database Schema

```sql
-- STEP 1: Create Database
CREATE DATABASE techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techstore;

-- STEP 2: Create Tables

-- CATEGORIES TABLE
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CUSTOMERS TABLE
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
    last_login TIMESTAMP,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_registered_date (registered_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PRODUCTS TABLE
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
    dimensions VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_price (price),
    INDEX idx_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ORDERS TABLE
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

-- ORDER_ITEMS TABLE
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

-- REVIEWS TABLE
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

-- INVENTORY_LOG TABLE
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
```

---

## 🔧 PART 2: FUNDAMENTAL SQL OPERATIONS

### 2.1 INSERT Data (DML)

```sql
-- Insert Categories
INSERT INTO categories (name, description) VALUES
('Laptop & Desktop', 'Personal computers and laptops'),
('Mobile Devices', 'Smartphones and tablets'),
('Accessories', 'Computer and mobile accessories'),
('Networking', 'Network equipment and devices'),
('Storage', 'HDD, SSD, and storage solutions');

-- Insert Sample Products
INSERT INTO products (category_id, name, description, price, cost, stock, sku, status) VALUES
(1, 'MacBook Pro 14"', 'Powerful laptop for professionals', 25000000, 18000000, 15, 'MBP-14-2024', 'active'),
(1, 'Dell XPS 13', 'Ultrabook with stunning display', 16000000, 12000000, 25, 'DELL-XPS-13', 'active'),
(2, 'iPhone 15 Pro', 'Latest Apple smartphone', 18000000, 14000000, 50, 'IP15-PRO', 'active'),
(2, 'Samsung Galaxy S24', 'Flagship Android phone', 14000000, 10000000, 75, 'SGS24-BLK', 'active'),
(3, 'USB-C Cable 2m', 'Durable charging cable', 150000, 50000, 500, 'USB-C-2M', 'active'),
(3, 'Wireless Mouse Logitech', 'Ergonomic wireless mouse', 350000, 150000, 200, 'LOG-MOUSE-WL', 'active'),
(4, 'TP-Link WiFi 6 Router', 'High-speed WiFi 6 router', 2500000, 1500000, 30, 'TPLINK-AX6', 'active'),
(5, 'Samsung SSD 1TB', 'Fast NVMe SSD storage', 1200000, 700000, 100, 'SAM-SSD-1TB', 'active');

-- Insert Sample Customers
INSERT INTO customers (name, email, phone, address, city, postal_code) VALUES
('Budi Santoso', 'budi@example.com', '081234567890', 'Jl. Merdeka No. 123', 'Jakarta', '12345'),
('Siti Rahman', 'siti@example.com', '081234567891', 'Jl. Sudirman No. 456', 'Bandung', '40123'),
('Ahmad Wijaya', 'ahmad@example.com', '081234567892', 'Jl. Gatot Subroto No. 789', 'Surabaya', '60123'),
('Rini Kusuma', 'rini@example.com', '081234567893', 'Jl. Diponegoro No. 321', 'Yogyakarta', '55123'),
('Hendra Prasetya', 'hendra@example.com', '081234567894', 'Jl. Ahmad Yani No. 654', 'Medan', '20123');

-- Insert Sample Orders
INSERT INTO orders (customer_id, total_amount, tax_amount, shipping_cost, payment_method, status) VALUES
(1, 25150000, 375000, 50000, 'bank_transfer', 'delivered'),
(2, 16200000, 300000, 75000, 'credit_card', 'shipped'),
(3, 18450000, 350000, 50000, 'e_wallet', 'delivered'),
(1, 1575000, 75000, 25000, 'credit_card', 'confirmed'),
(4, 41200000, 700000, 100000, 'bank_transfer', 'pending');

-- Insert Order Items (dengan relasi ke orders dan products)
INSERT INTO order_items (order_id, product_id, quantity, unit_price, discount_percent) VALUES
(1, 1, 1, 25000000, 0),
(2, 2, 1, 16000000, 0),
(3, 3, 1, 18000000, 0),
(4, 6, 1, 350000, 0),
(4, 5, 2, 150000, 0),
(5, 1, 1, 25000000, 5),
(5, 3, 1, 18000000, 0);

-- Insert Reviews
INSERT INTO reviews (product_id, customer_id, rating, comment) VALUES
(1, 1, 5, 'Produk sangat bagus, tidak ada yang perlu dikomplain'),
(1, 2, 4, 'Bagus tapi agak mahal'),
(2, 3, 5, 'Kualitas terbaik, pengiriman cepat'),
(3, 1, 4, 'Smartphone yang andal'),
(3, 4, 5, 'Sempurna! Sesuai ekspektasi'),
(6, 5, 5, 'Mouse sangat nyaman digunakan'),
(7, 2, 4, 'Router bekerja dengan baik');
```

### 2.2 SELECT & BASIC QUERIES

```sql
-- Query 1: Lihat semua produk aktif dengan kategorinya
SELECT 
    p.product_id,
    p.name AS product_name,
    c.name AS category_name,
    p.price,
    p.stock
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
WHERE p.status = 'active'
ORDER BY p.price DESC;

-- Query 2: Lihat semua order customer tertentu
SELECT 
    o.order_id,
    o.order_date,
    o.total_amount,
    o.status,
    COUNT(oi.order_item_id) AS total_items
FROM orders o
LEFT JOIN order_items oi ON o.order_id = oi.order_id
WHERE o.customer_id = 1
GROUP BY o.order_id
ORDER BY o.order_date DESC;

-- Query 3: Lihat rating produk dengan ulasan
SELECT 
    p.product_id,
    p.name,
    ROUND(AVG(r.rating), 2) AS avg_rating,
    COUNT(r.review_id) AS total_reviews
FROM products p
LEFT JOIN reviews r ON p.product_id = r.product_id
GROUP BY p.product_id
HAVING COUNT(r.review_id) > 0
ORDER BY avg_rating DESC;

-- Query 4: Stok terendah
SELECT 
    product_id,
    name,
    stock,
    price
FROM products
WHERE status = 'active'
ORDER BY stock ASC
LIMIT 10;
```

---

## 🎓 PART 3: ADVANCED SQL - JOINS & SUBQUERIES

### 3.1 Advanced JOINs

```sql
-- Query 1: Pelanggan dengan total pembelian mereka
SELECT 
    c.customer_id,
    c.name,
    c.email,
    COUNT(DISTINCT o.order_id) AS total_orders,
    SUM(o.total_amount) AS total_spent,
    MAX(o.order_date) AS last_purchase_date
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id
ORDER BY total_spent DESC;

-- Query 2: Produk paling laris dengan detail penjualan
SELECT 
    p.product_id,
    p.name,
    c.name AS category,
    COUNT(oi.order_item_id) AS total_sold,
    SUM(oi.quantity) AS qty_sold,
    ROUND(SUM(oi.quantity * oi.unit_price), 2) AS total_revenue,
    ROUND(AVG(r.rating), 2) AS avg_rating
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN orders o ON oi.order_id = o.order_id AND o.status != 'cancelled'
LEFT JOIN reviews r ON p.product_id = r.product_id
GROUP BY p.product_id
ORDER BY total_revenue DESC;

-- Query 3: Detail order dengan customer dan product info
SELECT 
    o.order_id,
    c.name AS customer_name,
    c.email,
    p.name AS product_name,
    oi.quantity,
    oi.unit_price,
    (oi.quantity * oi.unit_price) AS subtotal,
    o.order_date,
    o.status
FROM orders o
INNER JOIN customers c ON o.customer_id = c.customer_id
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id
ORDER BY o.order_date DESC;
```

### 3.2 Subqueries

```sql
-- Query 1: Pelanggan yang total pembelanjaannya di atas rata-rata
SELECT 
    c.customer_id,
    c.name,
    (SELECT SUM(total_amount) FROM orders WHERE customer_id = c.customer_id) AS total_spent
FROM customers c
WHERE (SELECT SUM(total_amount) FROM orders WHERE customer_id = c.customer_id) > 
      (SELECT AVG(total_spent) FROM (
          SELECT SUM(total_amount) AS total_spent 
          FROM orders 
          GROUP BY customer_id
      ) AS avg_table)
ORDER BY total_spent DESC;

-- Query 2: Produk dengan rating di atas rata-rata kategori
SELECT 
    p.product_id,
    p.name,
    c.name AS category,
    ROUND(AVG(r.rating), 2) AS avg_rating
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN reviews r ON p.product_id = r.product_id
GROUP BY p.product_id
HAVING ROUND(AVG(r.rating), 2) > (
    SELECT AVG(rating) FROM reviews 
    WHERE product_id IN (
        SELECT product_id FROM products WHERE category_id = c.category_id
    )
)
ORDER BY avg_rating DESC;

-- Query 3: Produk yang belum pernah dibeli
SELECT 
    product_id,
    name,
    price,
    stock
FROM products
WHERE product_id NOT IN (
    SELECT DISTINCT product_id FROM order_items
)
AND status = 'active'
ORDER BY price DESC;
```

---

## ⚡ PART 4: QUERY OPTIMIZATION & INDEXING

### 4.1 Performance Analysis

```sql
-- Enable query profiling
SET profiling = 1;

-- Query tanpa index (SLOW)
SELECT * FROM orders 
WHERE customer_id = 1 AND order_date > '2024-01-01';

-- Check performance
SHOW PROFILES;
SHOW PROFILE FOR QUERY 1;

-- Verify index exists
SHOW INDEXES FROM orders;

-- Query dengan index (FAST)
-- Index sudah ada: INDEX idx_customer (customer_id), INDEX idx_order_date (order_date)
SELECT * FROM orders 
WHERE customer_id = 1 AND order_date > '2024-01-01';
```

### 4.2 Index Strategy

```sql
-- Current Indexes dalam database:
-- 1. PRIMARY KEY (automatic index)
-- 2. UNIQUE constraints (automatic index)
-- 3. Foreign Keys (automatic index)
-- 4. Composite Indexes untuk queries umum:

-- Cek query yang sering dijalankan
EXPLAIN SELECT c.name, COUNT(o.order_id) as total_orders
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
WHERE c.status = 'active'
GROUP BY c.customer_id;

-- Create composite index jika diperlukan
CREATE INDEX idx_customer_status_order ON orders(customer_id, status, order_date);

-- Monitor index size
SELECT 
    object_schema,
    object_name,
    index_name,
    count_read,
    count_write,
    count_delete
FROM performance_schema.table_io_waits_summary_by_index_usage
WHERE object_schema != 'mysql'
ORDER BY count_read DESC;
```

### 4.3 Query Optimization Tips

```sql
-- ❌ TIDAK OPTIMAL - Query yang lambat
SELECT * FROM orders o
WHERE YEAR(o.order_date) = 2024 AND MONTH(o.order_date) = 3;

-- ✅ OPTIMAL - Menggunakan index
SELECT * FROM orders o
WHERE o.order_date >= '2024-03-01' AND o.order_date < '2024-04-01';

-- ❌ TIDAK OPTIMAL - SELECT * (ambil semua kolom)
SELECT * FROM products WHERE category_id = 1;

-- ✅ OPTIMAL - Hanya ambil kolom yang dibutuhkan
SELECT product_id, name, price FROM products WHERE category_id = 1;

-- ❌ TIDAK OPTIMAL - Subquery di SELECT clause
SELECT 
    c.customer_id,
    (SELECT COUNT(*) FROM orders WHERE customer_id = c.customer_id) AS order_count
FROM customers c;

-- ✅ OPTIMAL - Menggunakan JOIN dan GROUP BY
SELECT 
    c.customer_id,
    COUNT(o.order_id) AS order_count
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id;
```

---

## 📊 PART 5: REAL-WORLD BUSINESS ANALYTICS

### 5.1 Sales Dashboard Queries

```sql
-- Query 1: Revenue Summary
SELECT 
    DATE(order_date) AS order_date,
    COUNT(order_id) AS total_orders,
    COUNT(DISTINCT customer_id) AS unique_customers,
    ROUND(SUM(total_amount), 2) AS total_revenue,
    ROUND(AVG(total_amount), 2) AS avg_order_value
FROM orders
WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(order_date)
ORDER BY order_date DESC;

-- Query 2: Top 10 Selling Products
SELECT 
    p.product_id,
    p.name,
    c.name AS category,
    SUM(oi.quantity) AS qty_sold,
    ROUND(SUM(oi.quantity * oi.unit_price), 2) AS total_revenue,
    ROUND(AVG(r.rating), 2) AS avg_rating
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
INNER JOIN order_items oi ON p.product_id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.order_id
LEFT JOIN reviews r ON p.product_id = r.product_id
WHERE o.status IN ('delivered', 'shipped')
GROUP BY p.product_id
ORDER BY total_revenue DESC
LIMIT 10;

-- Query 3: Customer Segmentation (RFM Analysis)
SELECT 
    c.customer_id,
    c.name,
    COUNT(DISTINCT o.order_id) AS frequency,
    DATEDIFF(CURDATE(), MAX(o.order_date)) AS days_since_last_purchase,
    ROUND(SUM(o.total_amount), 2) AS monetary_value,
    CASE 
        WHEN COUNT(DISTINCT o.order_id) >= 5 AND 
             SUM(o.total_amount) >= 20000000 THEN 'VIP'
        WHEN COUNT(DISTINCT o.order_id) >= 3 THEN 'Regular'
        ELSE 'New'
    END AS customer_segment
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id
ORDER BY monetary_value DESC;

-- Query 4: Payment Method Analysis
SELECT 
    payment_method,
    COUNT(order_id) AS total_transactions,
    ROUND(AVG(total_amount), 2) AS avg_transaction,
    ROUND(SUM(total_amount), 2) AS total_revenue,
    ROUND(COUNT(order_id) * 100.0 / (SELECT COUNT(*) FROM orders), 2) AS percentage
FROM orders
WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
GROUP BY payment_method
ORDER BY total_revenue DESC;

-- Query 5: Inventory Status Report
SELECT 
    c.name AS category,
    COUNT(p.product_id) AS total_products,
    SUM(p.stock) AS total_stock,
    ROUND(AVG(p.price), 2) AS avg_price,
    SUM(p.stock * p.price) AS inventory_value,
    ROUND((SUM(CASE WHEN p.stock <= 10 THEN 1 ELSE 0 END) * 100.0) / COUNT(p.product_id), 2) AS low_stock_percentage
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
WHERE p.status = 'active'
GROUP BY c.category_id
ORDER BY inventory_value DESC;
```

---

## 📈 PART 6: ADVANCED CONCEPTS

### 6.1 Window Functions (MySQL 8.0+)

```sql
-- Row Numbering
SELECT 
    product_id,
    name,
    price,
    ROW_NUMBER() OVER (ORDER BY price DESC) AS price_rank,
    RANK() OVER (PARTITION BY category_id ORDER BY price DESC) AS category_rank
FROM products
WHERE status = 'active';

-- Running Total
SELECT 
    order_date,
    total_amount,
    SUM(total_amount) OVER (
        ORDER BY order_date 
        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
    ) AS running_total
FROM orders
ORDER BY order_date;

-- Lead and Lag
SELECT 
    order_date,
    total_amount,
    LAG(total_amount) OVER (ORDER BY order_date) AS prev_order,
    total_amount - LAG(total_amount) OVER (ORDER BY order_date) AS difference
FROM orders
ORDER BY order_date;
```

### 6.2 Common Table Expressions (CTE)

```sql
-- CTE untuk hierarchical data
WITH customer_orders AS (
    SELECT 
        c.customer_id,
        c.name,
        COUNT(o.order_id) AS order_count,
        SUM(o.total_amount) AS total_spent
    FROM customers c
    LEFT JOIN orders o ON c.customer_id = o.customer_id
    GROUP BY c.customer_id
)
SELECT 
    co.customer_id,
    co.name,
    co.order_count,
    ROUND(co.total_spent, 2) AS total_spent,
    ROUND(co.total_spent / NULLIF((SELECT AVG(total_spent) FROM customer_orders), 0), 2) AS vs_avg_ratio
FROM customer_orders co
ORDER BY total_spent DESC;

-- Multiple CTEs
WITH monthly_sales AS (
    SELECT 
        DATE_TRUNC(order_date, MONTH) AS sales_month,
        SUM(total_amount) AS monthly_revenue
    FROM orders
    GROUP BY DATE_TRUNC(order_date, MONTH)
),
sales_with_growth AS (
    SELECT 
        sales_month,
        monthly_revenue,
        LAG(monthly_revenue) OVER (ORDER BY sales_month) AS prev_month_revenue,
        ROUND(((monthly_revenue - LAG(monthly_revenue) OVER (ORDER BY sales_month)) / 
               LAG(monthly_revenue) OVER (ORDER BY sales_month) * 100), 2) AS growth_percentage
    FROM monthly_sales
)
SELECT * FROM sales_with_growth
ORDER BY sales_month DESC;
```

---

## 🛡️ PART 7: DATA INTEGRITY & SECURITY

### 7.1 Transactions

```sql
-- Example: Process order (atomic operation)
START TRANSACTION;

-- Step 1: Create order
INSERT INTO orders (customer_id, total_amount, status) 
VALUES (1, 25150000, 'pending');
SET @order_id = LAST_INSERT_ID();

-- Step 2: Add order items
INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
VALUES (@order_id, 1, 1, 25000000);

-- Step 3: Update inventory
UPDATE products SET stock = stock - 1 
WHERE product_id = 1;

-- Step 4: Log inventory change
INSERT INTO inventory_log (product_id, quantity_change, transaction_type, reference_id)
VALUES (1, -1, 'purchase', @order_id);

-- Commit or Rollback
COMMIT; -- Jika semua berhasil
-- ROLLBACK; -- Jika ada error
```

### 7.2 User Permissions

```sql
-- Create application user (bukan root)
CREATE USER 'techstore_app'@'localhost' IDENTIFIED BY 'SecurePassword123!';

-- Grant permissions untuk SELECT, INSERT, UPDATE
GRANT SELECT, INSERT, UPDATE ON techstore.* TO 'techstore_app'@'localhost';

-- Grant specific permissions for reporting
CREATE USER 'techstore_report'@'localhost' IDENTIFIED BY 'Report123!';
GRANT SELECT ON techstore.* TO 'techstore_report'@'localhost';

-- Revoke permissions
REVOKE INSERT, UPDATE ON techstore.* FROM 'techstore_app'@'localhost';

-- Check permissions
SHOW GRANTS FOR 'techstore_app'@'localhost';
```

### 7.3 Data Validation & Constraints

```sql
-- CHECK constraint contoh
ALTER TABLE reviews 
ADD CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5);

-- UNIQUE constraint untuk email
ALTER TABLE customers
ADD CONSTRAINT unique_email UNIQUE (email);

-- Foreign Key dengan cascade
ALTER TABLE order_items
ADD CONSTRAINT fk_order FOREIGN KEY (order_id) 
REFERENCES orders(order_id) ON DELETE CASCADE;

-- DEFAULT values
ALTER TABLE products
MODIFY COLUMN status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active';
```

---

## 🎯 PART 8: PRACTICAL EXERCISES

### Exercise 1: Buat Laporan Penjualan Bulanan
```sql
-- Tugas: Buat query yang menampilkan:
-- - Bulan
-- - Jumlah order
-- - Total revenue
-- - Rata-rata order value
-- - Growth vs bulan sebelumnya

-- SOLUSI akan disertakan dalam file terpisah
```

### Exercise 2: Optimasi Query Lambat
```sql
-- Tugas: Identifikasi dan optimasi query berikut:
SELECT o.*, c.*, p.* FROM orders o 
JOIN customers c ON o.customer_id = c.customer_id
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE YEAR(o.order_date) = 2024;

-- SOLUSI: Gunakan EXPLAIN dan tambahkan index
```

### Exercise 3: Segmentasi Customer
```sql
-- Tugas: Segment customer berdasarkan:
-- - RFM Score (Recency, Frequency, Monetary)
-- - Kategori produk yang dibeli
-- - Average rating yang mereka beri
```

---

## 📚 KEY LEARNINGS SUMMARY

| Topik | Konsep Utama | Best Practice |
|-------|-------------|---|
| **Data Modeling** | Normalization (1NF, 2NF, 3NF) | Hindari redundansi data |
| **JOINs** | INNER, LEFT, RIGHT, FULL | Pilih JOIN tipe yang tepat |
| **Subqueries** | Nested SELECT | Pertimbangkan JOIN sebagai alternatif |
| **Indexing** | B-tree, Hash | Index kolom yang sering di-filter |
| **Optimization** | EXPLAIN, Query Plan | Monitor dan optimize regularly |
| **Transactions** | ACID properties | Gunakan untuk operasi kritis |
| **Security** | User roles, Permissions | Least privilege principle |

---

## 🚀 NEXT STEPS

1. **Practice Writing Queries** - Kerjakan semua exercise
2. **Analyze Real Data** - Gunakan EXPLAIN untuk setiap query
3. **Implement Indexes** - Test impact pada performa
4. **Build Application** - Integrasikan ke aplikasi PHP
5. **Monitor Performance** - Set up query monitoring dan alerting

---

**Happy Learning! Let's Unlock the Power of Data! 🚀**
