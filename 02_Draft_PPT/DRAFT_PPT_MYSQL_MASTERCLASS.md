# 📊 BASIC MYSQL MASTERCLASS
## Unlock the Power of Data

### Draft Presentasi PowerPoint (Format Markdown)
*Fundamental to Advanced Database Management, Query Optimization, & Real-World Integration with MySQL*

---

## 🎬 SLIDE 1: TITLE SLIDE

### Basic MySQL Masterclass
### Unlock the Power of Data

**Fundamental to Advanced Database Management,**
**Query Optimization, & Real-World Integration with MySQL**

---
**Presenter:** Damar Galih Aji Pradana
**Date:** 29 Maret 2026
**Target Audience:** Data Analysts & Developers

---

## 📑 SLIDE 2: AGENDA

### Agenda Pembelajaran

1. ✅ **Fundamental SQL** - Dasar-dasar database
2. ✅ **Advanced SQL** - JOINs, Subqueries, CTEs
3. ✅ **Database Design** - Schema optimization & normalization
4. ✅ **Performance Tuning** - Indexing & query optimization
5. ✅ **Real-World Integration** - Analytics & business cases
6. ✅ **Best Practices** - Security & data integrity
7. ✅ **Hands-on Practice** - Live coding & exercises

**Duration:** 2 Hours (including breaks)
**Format:** Interactive Workshop + Live Coding

---

## 📚 SLIDE 3: WHY MYSQL?

### Mengapa MySQL untuk Masterclass Ini?

#### 🌍 Global Usage
- **89%** dari semua database di web menggunakan MySQL
- Digunakan oleh: Facebook, Twitter, Netflix, Uber, Airbnb

#### 💪 Advantages
- ✅ Open Source & Free
- ✅ Reliable & Stable
- ✅ Fast & Scalable
- ✅ Easy to Learn
- ✅ Great Community Support

#### 📊 Industry Demand
- **Top skill** untuk Data Analysts
- **Essential** untuk Full-stack Developers
- Growing demand in **Big Data Analytics**

#### 🔧 Practical Skills
- Immediately applicable to real projects
- Used in LAMP/MEAN stacks
- Foundation for advanced databases (PostgreSQL, MongoDB)

---

## 🎯 SLIDE 4: FUNDAMENTAL CONCEPTS

### Konsep Dasar Database

#### 📊 Database vs Spreadsheet
| Aspect | Spreadsheet | Database |
|--------|------------|----------|
| **Data Volume** | 1-100K rows | Millions+ rows |
| **Relationships** | Limited | Complex |
| **Performance** | Slow (large data) | Fast & Scalable |
| **Concurrency** | Single user | Multiple users |
| **Integrity** | Manual | Automatic |

#### 🏗️ Database Structure
```
Database
  └── Tables (Entity)
       └── Rows (Records)
            └── Columns (Attributes/Fields)
```

#### 🔑 Key Terms
- **Table:** Koleksi data terstruktur (seperti Excel sheet)
- **Primary Key:** Identifier unik untuk setiap row
- **Foreign Key:** Link ke tabel lain (relasi)
- **Index:** Akselerasi pencarian data
- **Query:** Perintah untuk mengambil/memodifikasi data

#### 📐 ACID Properties
- **Atomicity** - All or nothing (semua atau tidak sama sekali)
- **Consistency** - Data tetap konsisten
- **Isolation** - Transaksi independen
- **Durability** - Data persisten

---

## 📋 SLIDE 5: DATABASE DESIGN

### Merancang Database yang Baik

#### 🎨 Design Process
1. **Understand Requirements** - Pahami kebutuhan bisnis
2. **Create ERD** - Entity Relationship Diagram
3. **Normalize** - Hindari redundansi/anomali
4. **Create Schema** - Define tables & relationships
5. **Test & Optimize** - Verify & improve performance

#### 🔄 Normalization Levels
```
Unnormalized
    ↓
1NF (First Normal Form) - No repeating groups
    ↓
2NF (Second Normal Form) - All attributes depend on Primary Key
    ↓
3NF (Third Normal Form) - No transitive dependencies
    ↓
BCNF (Boyce-Codd) - Stricter than 3NF
```

#### ❌ Anomalies to Avoid
- **Insert Anomaly** - Can't insert without complete data
- **Update Anomaly** - Must update multiple rows
- **Delete Anomaly** - Lose important data when deleting

#### ✅ Real Database Example (E-Commerce)
```
Tables:
- Customers (ID, Name, Email, Address)
- Products (ID, Name, Category, Price)
- Orders (ID, Customer_ID, Order_Date)
- Order_Items (ID, Order_ID, Product_ID, Quantity)
- Reviews (ID, Product_ID, Rating, Comment)
```

---

## 💾 SLIDE 6: FUNDAMENTAL SQL

### DDL, DML, DML Commands

#### 1️⃣ DDL (Data Definition Language)
**Purpose:** Define structure

```sql
-- CREATE TABLE
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE
);

-- ALTER TABLE
ALTER TABLE customers ADD COLUMN phone VARCHAR(20);

-- DROP TABLE
DROP TABLE customers;
```

#### 2️⃣ DML (Data Manipulation Language)
**Purpose:** Manipulate data

```sql
-- INSERT
INSERT INTO customers (name, email) 
VALUES ('Budi', 'budi@example.com');

-- UPDATE
UPDATE customers SET email = 'budi.new@example.com' 
WHERE customer_id = 1;

-- DELETE
DELETE FROM customers WHERE customer_id = 1;
```

#### 3️⃣ DQL (Data Query Language)
**Purpose:** Retrieve data

```sql
-- SELECT
SELECT customer_id, name, email
FROM customers
WHERE status = 'active'
ORDER BY name;
```

#### 4️⃣ DCL (Data Control Language)
**Purpose:** Control access

```sql
-- GRANT
GRANT SELECT ON database.* TO 'user'@'localhost';

-- REVOKE
REVOKE INSERT ON database.* FROM 'user'@'localhost';
```

---

## 🔗 SLIDE 7: JOINS EXPLAINED

### Menggabungkan Data dari Multiple Tables

#### 👥 The Four Main JOINs

##### 1. INNER JOIN
```
Customers ∩ Orders
(Only matching records)

SELECT c.name, o.order_id
FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id;
```
**Kapan gunakan:** Hanya data yang ada di kedua tabel

##### 2. LEFT JOIN
```
Customers + Matching Orders
(All from left, matching from right)

SELECT c.name, o.order_id
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id;
```
**Kapan gunakan:** Semua customer, termasuk yang belum order

##### 3. RIGHT JOIN
```
Orders + Matching Customers
(All from right, matching from left)

SELECT c.name, o.order_id
FROM customers c
RIGHT JOIN orders o ON c.customer_id = o.customer_id;
```
**Kapan gunakan:** Semua order dengan info customer jika ada

##### 4. FULL OUTER JOIN
```
Customers ∪ Orders
(All records from both tables)

SELECT c.name, o.order_id
FROM customers c
FULL OUTER JOIN orders o ON c.customer_id = o.customer_id;
```
**Note:** MySQL tidak support FULL OUTER directly, gunakan UNION

#### 📊 JOIN Decision Tree
```
START
  ├─ Need ALL records from left? ─YES─> LEFT JOIN
  ├─ Need ALL records from right? ─YES─> RIGHT JOIN
  ├─ Need records from BOTH? ─YES─> INNER JOIN
  └─ Need ALL records anyway? ─YES─> FULL OUTER (UNION)
```

---

## 🔍 SLIDE 8: SUBQUERIES & CTEs

### Query dalam Query

#### 🎯 Subqueries (Dependent)

**Definition:** Query yang nested dalam query lain

```sql
-- Subquery dalam WHERE clause
SELECT product_id, name, price
FROM products
WHERE price > (
    SELECT AVG(price) FROM products
);

-- Subquery dalam FROM clause
SELECT category, avg_price
FROM (
    SELECT category_id, AVG(price) AS avg_price
    FROM products
    GROUP BY category_id
) AS category_stats
WHERE avg_price > 1000000;
```

#### 🚀 Common Table Expressions (CTEs)

**Definition:** Temporary result set yang dapat direferensikan multiple times

```sql
-- Single CTE
WITH top_customers AS (
    SELECT customer_id, name, 
           SUM(total_amount) AS lifetime_value
    FROM customers c
    JOIN orders o ON c.customer_id = o.customer_id
    GROUP BY customer_id
    HAVING SUM(total_amount) > 10000000
)
SELECT * FROM top_customers
ORDER BY lifetime_value DESC;

-- Multiple CTEs
WITH monthly_sales AS (
    SELECT DATE_TRUNC(order_date, MONTH) AS month,
           SUM(total_amount) AS revenue
    FROM orders
    GROUP BY month
),
growth_calc AS (
    SELECT month, revenue,
           LAG(revenue) OVER (ORDER BY month) AS prev_revenue
    FROM monthly_sales
)
SELECT month, revenue,
       ROUND((revenue - prev_revenue) / prev_revenue * 100, 2) AS growth_pct
FROM growth_calc;
```

#### ✅ Subquery vs CTE: Kapan Gunakan?
| Aspek | Subquery | CTE |
|-------|----------|-----|
| **Readability** | Kurang | Lebih jelas |
| **Reusability** | Sekali | Multiple times |
| **Complexity** | Simple | Complex |
| **Performance** | Sama | Sama |

---

## ⚡ SLIDE 9: INDEXING & PERFORMANCE

### Mempercepat Queries

#### 🏃 Importance of Indexing

**Analogi:** Seperti index di buku
- Tanpa index: Cari 1 per 1 halaman (SLOW)
- Dengan index: Langsung ke halaman yang dicari (FAST)

```
Without Index: O(n) - Scan every row
With Index:    O(log n) - Binary search
```

#### 🔧 Types of Indexes
```
1. PRIMARY KEY INDEX
   - Unique identifier
   - Automatically created
   
2. UNIQUE INDEX
   - Ensures unique values
   - Used for UNIQUE constraints
   
3. Regular INDEX
   - Speed up WHERE, JOIN, ORDER BY
   - Most commonly used
   
4. COMPOSITE INDEX
   - Multiple columns
   - Order matters!
   - Example: (customer_id, order_date)
   
5. FULL-TEXT INDEX
   - For text search
   - MATCH/AGAINST queries
```

#### 📝 Index Strategy

```sql
-- Good candidates untuk index:
-- 1. Primary Key (auto)
-- 2. Foreign Keys (untuk JOIN)
-- 3. Columns dalam WHERE clause
-- 4. Columns dalam ORDER BY
-- 5. Columns dalam GROUP BY
-- 6. Columns dalam JOIN ON

-- DO:
CREATE INDEX idx_customer_status ON customers(status);
CREATE INDEX idx_order_date ON orders(order_date);
CREATE INDEX idx_order_customer_date ON orders(customer_id, order_date);

-- DON'T:
CREATE INDEX idx_all_columns ON table(*);
CREATE INDEX idx_rarely_used ON table(rarely_used_col);
```

#### 📊 Performance Monitoring

```sql
-- Check query execution plan
EXPLAIN SELECT * FROM customers 
WHERE status = 'active' 
ORDER BY created_at DESC;

-- Look for:
-- ❌ Type: ALL (full table scan - BAD)
-- ✅ Type: ref (index scan - GOOD)
-- ✅ Type: range (range scan - GOOD)

-- Analyze slow queries
SHOW FULL PROCESSLIST; -- See running queries
SELECT * FROM mysql.slow_log; -- Slow query log
```

---

## 📈 SLIDE 10: REAL-WORLD ANALYTICS

### Business Intelligence dengan SQL

#### 📊 Common Analytics Queries

**1. Revenue Dashboard**
```sql
SELECT 
    DATE(order_date) AS date,
    COUNT(order_id) AS orders,
    SUM(total_amount) AS revenue,
    ROUND(AVG(total_amount), 2) AS avg_order
FROM orders
GROUP BY DATE(order_date);
```

**2. Customer Segmentation (RFM)**
```sql
SELECT 
    customer_id,
    COUNT(order_id) AS frequency,
    DATEDIFF(CURDATE(), MAX(order_date)) AS recency,
    SUM(total_amount) AS monetary,
    CASE 
        WHEN frequency >= 5 AND monetary >= 10M THEN 'VIP'
        WHEN frequency >= 3 THEN 'Regular'
        ELSE 'New'
    END AS segment
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
GROUP BY customer_id;
```

**3. Product Performance**
```sql
SELECT 
    p.product_id, p.name,
    COUNT(oi.order_item_id) AS qty_sold,
    SUM(oi.quantity * oi.unit_price) AS revenue,
    ROUND(AVG(r.rating), 2) AS rating
FROM products p
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN reviews r ON p.product_id = r.product_id
GROUP BY p.product_id
ORDER BY revenue DESC;
```

#### 🎯 Key Metrics

| Metric | Formula | Significance |
|--------|---------|--------------|
| **Revenue** | SUM(total_amount) | Total income |
| **Conversion** | Orders / Visitors | Sale effectiveness |
| **AOV** | Revenue / Orders | Average spend |
| **CAC** | Marketing Cost / Customers | Cost per customer |
| **LTV** | Total Spent / Customer | Customer value |
| **Churn** | Lost Customers / Total | Retention rate |

---

## 🛡️ SLIDE 11: SECURITY & BEST PRACTICES

### Protecting Your Data

#### 🔐 Security Best Practices

```sql
-- 1. NEVER use root for application
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';

-- 2. Grant LEAST PRIVILEGES
GRANT SELECT, INSERT, UPDATE ON mydb.* TO 'app_user'@'localhost';

-- 3. Never store passwords in code
-- Use environment variables or config files

-- 4. SQL Injection Prevention
-- WRONG: "SELECT * FROM users WHERE id = " + userId;
-- RIGHT: Use prepared statements (in code)
-- mysql_prepare() atau ORM

-- 5. Regular Backups
-- mysqldump -u root -p mydb > backup.sql

-- 6. Monitor slow queries
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
```

#### ✅ Transaction Safety

```sql
-- Use transactions untuk multi-step operations
BEGIN;
  INSERT INTO orders (...) VALUES (...);
  UPDATE products SET stock = stock - 1 WHERE id = X;
  INSERT INTO inventory_log (...);
COMMIT; -- atau ROLLBACK jika error
```

#### 📋 Data Integrity

```sql
-- Constraints untuk menjamin data quality
CREATE TABLE orders (
    order_id INT PRIMARY KEY,
    customer_id INT NOT NULL REFERENCES customers(id),
    amount DECIMAL(10,2) CHECK (amount > 0),
    status ENUM('pending','confirmed','shipped','delivered'),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🎯 SLIDE 12: QUERY OPTIMIZATION TIPS

### Optimization Techniques

#### ❌ SEBELUM (Slow)
```sql
-- 1. Menggunakan function di WHERE
SELECT * FROM orders 
WHERE YEAR(order_date) = 2024;

-- 2. SELECT * (ambil semua kolom)
SELECT * FROM products WHERE category_id = 1;

-- 3. Subquery di SELECT
SELECT c.id, 
       (SELECT COUNT(*) FROM orders WHERE customer_id = c.id)
FROM customers c;

-- 4. JOIN tanpa ON clause
SELECT * FROM orders, customers 
WHERE orders.customer_id = customers.id;

-- 5. ORDER BY tanpa index
SELECT * FROM products ORDER BY description;
```

#### ✅ SESUDAH (Fast)
```sql
-- 1. Range query
SELECT * FROM orders 
WHERE order_date >= '2024-01-01' AND order_date < '2025-01-01';

-- 2. Specific columns
SELECT product_id, name, price FROM products 
WHERE category_id = 1;

-- 3. JOIN with GROUP BY
SELECT c.id, COUNT(o.id) 
FROM customers c
LEFT JOIN orders o ON c.id = o.customer_id
GROUP BY c.id;

-- 4. Proper JOIN syntax
SELECT * FROM orders o
INNER JOIN customers c ON o.customer_id = c.id;

-- 5. Indexed ORDER BY
SELECT * FROM products 
ORDER BY created_at DESC; -- atau status (jika indexed)
```

#### 📊 Optimization Checklist
- ✅ Use indexes on WHERE columns
- ✅ Avoid functions in WHERE clause
- ✅ Use JOINs instead of subqueries
- ✅ Select only needed columns
- ✅ Filter early (WHERE before GROUP BY)
- ✅ Monitor with EXPLAIN
- ✅ Keep statistics updated

---

## 🎓 SLIDE 13: PRACTICAL EXERCISE

### Hands-On Coding Session

#### Exercise 1: Simple Queries
```sql
-- 1. Get all active customers
-- 2. Show products below average price
-- 3. Count orders per customer
```

#### Exercise 2: Advanced Queries
```sql
-- 1. Find top 5 customers by spending
-- 2. Get product ratings with review count
-- 3. Revenue by category for last 30 days
```

#### Exercise 3: Optimization Challenge
```sql
-- Original slow query (given)
-- Task: Optimize using indexes and query rewriting
-- Goal: < 100ms execution time (from 5s)
```

#### Exercise 4: Real-World Scenario
```sql
-- Scenario: Build customer lifetime value analysis
-- Show:
--   - Customer name
--   - Total purchases
--   - Average order value
--   - Last purchase date
--   - Customer segment (VIP/Regular/New)
```

---

## 📚 SLIDE 14: RESOURCES & NEXT STEPS

### Lanjutkan Pembelajaran

#### 📖 Learning Resources
- **MySQL Official Docs:** https://dev.mysql.com/doc/
- **LeetCode SQL:** https://leetcode.com/problemset/database/
- **HackerRank SQL:** https://www.hackerrank.com/domains/sql
- **SQLZoo:** https://sqlzoo.net/
- **Mode Analytics SQL Tutorial:** https://mode.com/sql-tutorial/

#### 🛠️ Tools to Master
- **MySQL Workbench** - GUI dashboard
- **phpMyAdmin** - Web interface
- **DBeaver** - Universal database tool
- **MySQL Command Line** - Classic tool

#### 🚀 Advanced Topics
1. **Stored Procedures & Triggers**
   - Automate database actions
   - Business logic in database
   
2. **Views & Materialized Views**
   - Simplify complex queries
   - Pre-compute aggregations
   
3. **Replication & Sharding**
   - Scale horizontally
   - High availability
   
4. **NoSQL Alternatives**
   - MongoDB (document-based)
   - Redis (in-memory)
   - PostgreSQL (more advanced SQL)

#### 📊 Integration with Applications
- **PHP:** MySQLi, PDO
- **Python:** PyMySQL, SQLAlchemy
- **Node.js:** mysql2, Sequelize
- **Java:** JDBC, Hibernate
- **C#:** .NET Entity Framework

---

## 🎯 SLIDE 15: Q&A & WRAP-UP

### Ringkasan Pembelajaran

#### 🏆 Key Takeaways

1. **Fundamental SQL** adalah skill essential
   - DDL, DML, DQL, DCL commands
   - Basic CRUD operations

2. **Advanced Queries** membuka kemungkinan tak terbatas
   - JOINs untuk relasi data
   - Subqueries & CTEs untuk clarity
   - Window functions untuk analytics

3. **Database Design** adalah pondasi sukses
   - Normalization mencegah anomali
   - Proper relationships memastikan integritas

4. **Performance Matters**
   - Indexes mempercepat queries drastis
   - EXPLAIN untuk diagnosis
   - Optimization adalah continuous process

5. **Security dan Best Practices**
   - Least privilege principle
   - Transactions untuk consistency
   - Regular monitoring & maintenance

#### 🎓 Kompetensi yang Dicapai
✅ Merancang database relasional
✅ Menulis query dari simple hingga complex
✅ Mengoptimalkan query performance
✅ Menerapkan security best practices
✅ Menganalisis data untuk business insights

#### ❓ Q&A Session
**Tanya jawab terbuka untuk semua peserta**

#### 📋 Feedback & Sertifikat
- Kerjakan semua exercises
- Submit project Anda
- Dapatkan sertifikat

---

## 🎬 SLIDE 16: CLOSING

### Let's Unlock the Power of Data! 

#### 💡 Remember...
> "Data is the new oil, but SQL is the refinery that turns data into insights."

#### 🚀 Your Next Steps
1. ✅ Review study case materials
2. ✅ Practice with provided exercises
3. ✅ Work on your own database project
4. ✅ Join community & ask questions
5. ✅ Master advanced topics

#### 📞 Contact & Resources
- **Email:** engineeringedu29@gmail.com
- **YouTube:** [Channel Link]
- **Discord:** [Community Link]
- **GitHub:** [Repository Link]

---

## 📊 ADDITIONAL VISUAL AIDS

### Diagram 1: Data Flow
```
Application
    ↓
SQL Query
    ↓
MySQL Engine
    ├── Parser (Validasi syntax)
    ├── Optimizer (Pilih plan terbaik)
    ├── Compiler (Generate plan)
    └── Query Engine (Execute)
    ↓
Storage Engine (InnoDB)
    ├── Buffered Pool
    ├── Log Files
    └── Data Files
    ↓
Result Set
    ↓
Application
```

### Diagram 2: Index Structure
```
WITHOUT INDEX:
Row 1 → Row 2 → Row 3 → Row 4 → Row 5 → ... (Linear search)

WITH INDEX (B-Tree):
        [Key: 50]
        /         \
    [30]           [70]
    /   \         /    \
  [10] [40]    [60]   [80]
  
→ Binary search (faster!)
```

### Diagram 3: Query Lifecycle
```
START
  ↓
1. PARSE
   └─ Check syntax
  ↓
2. VALIDATE
   └─ Check table/column existence
  ↓
3. OPTIMIZE
   └─ Choose best execution plan
  ↓
4. COMPILE
   └─ Generate query code
  ↓
5. EXECUTE
   └─ Run the query
  ↓
6. FETCH
   └─ Return results
  ↓
END
```

---

**Total Slides:** 16 slides + additional visual aids
**Suggested Time:** 6 hours (with breaks)
**Format:** Interactive presentation + live coding demonstrations

**Happy Learning! 🚀 Let's become MySQL Masters! 💪**
