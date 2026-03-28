# MySQL Masterclass - Quick Reference Guide (Cheat Sheet)

## 📌 TABLE OF CONTENTS
1. Basic Commands
2. Data Types
3. Common Functions
4. Query Patterns
5. Performance Tips
6. Troubleshooting

---

## 1️⃣ BASIC COMMANDS

### Database Operations
```sql
-- Create database
CREATE DATABASE mydb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE mydb;

-- List all databases
SHOW DATABASES;

-- Delete database
DROP DATABASE mydb;

-- Show current database
SELECT DATABASE();
```

### Table Operations
```sql
-- Create table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Show tables
SHOW TABLES;

-- Show table structure
DESC users;
-- OR
SHOW COLUMNS FROM users;

-- Rename table
RENAME TABLE old_name TO new_name;

-- Delete table
DROP TABLE users;

-- Clear table (keep structure)
TRUNCATE TABLE users;
```

### Data Operations (CRUD)
```sql
-- CREATE (INSERT)
INSERT INTO users (name, email) VALUES ('John', 'john@example.com');

-- READ (SELECT)
SELECT * FROM users WHERE id = 1;

-- UPDATE
UPDATE users SET email = 'john.new@example.com' WHERE id = 1;

-- DELETE
DELETE FROM users WHERE id = 1;
```

---

## 2️⃣ DATA TYPES

### Numeric Types
```
TINYINT       -128 to 127 (or 0 to 255 unsigned)
SMALLINT      -32,768 to 32,767
INT           -2,147,483,648 to 2,147,483,647
BIGINT        -9,223,372,036,854,775,808 to ...
FLOAT         Single precision floating point
DOUBLE        Double precision floating point
DECIMAL(M,D)  Fixed-point (M=total digits, D=decimal places)
```

### String Types
```
CHAR(n)       Fixed-length string (0-255)
VARCHAR(n)    Variable-length string (0-65,535)
TEXT          Max 65,535 characters
LONGTEXT      Max 4,294,967,295 characters
ENUM('a','b') Single choice from list
SET('a','b')  Multiple choices from list
JSON          JSON formatted data
```

### Date/Time Types
```
DATE          'YYYY-MM-DD'
TIME          'HH:MM:SS'
DATETIME      'YYYY-MM-DD HH:MM:SS'
TIMESTAMP     'YYYY-MM-DD HH:MM:SS' (auto updates)
YEAR          'YYYY'
```

### Example with Constraints
```sql
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) CHECK (price > 0),
    category ENUM('electronics', 'books', 'clothing'),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 3️⃣ COMMON FUNCTIONS

### String Functions
```sql
-- Length
SELECT LENGTH('hello');                    -- 5

-- Case conversion
SELECT UPPER('hello');                     -- HELLO
SELECT LOWER('HELLO');                     -- hello

-- Substring
SELECT SUBSTRING('hello', 1, 3);           -- hel

-- Concatenate
SELECT CONCAT('Hello', ' ', 'World');      -- Hello World
SELECT 'Hello' || ' ' || 'World';          -- Hello World (alternative)

-- Replace
SELECT REPLACE('hello world', 'world', 'mysql');  -- hello mysql

-- Trim
SELECT TRIM('  hello  ');                  -- hello

-- Find position
SELECT INSTR('hello world', 'world');      -- 7
```

### Numeric Functions
```sql
-- Rounding
SELECT ROUND(3.14159, 2);                  -- 3.14
SELECT CEIL(3.14);                         -- 4
SELECT FLOOR(3.14);                        -- 3

-- Absolute value
SELECT ABS(-10);                           -- 10

-- Power & Square root
SELECT POWER(2, 3);                        -- 8
SELECT SQRT(16);                           -- 4

-- Random
SELECT RAND();                             -- Random float 0-1
SELECT FLOOR(RAND() * 100);                -- Random 0-99
```

### Date/Time Functions
```sql
-- Current date/time
SELECT CURDATE();                          -- Today's date
SELECT CURTIME();                          -- Current time
SELECT NOW();                              -- Current date & time
SELECT CURRENT_TIMESTAMP;                  -- Current timestamp

-- Date arithmetic
SELECT DATE_ADD('2024-01-01', INTERVAL 1 DAY);        -- 2024-01-02
SELECT DATE_SUB('2024-01-01', INTERVAL 1 MONTH);      -- 2023-12-01
SELECT DATE_ADD(NOW(), INTERVAL 7 DAY);               -- 7 days from now

-- Date extraction
SELECT YEAR('2024-03-29');                 -- 2024
SELECT MONTH('2024-03-29');                -- 3
SELECT DAY('2024-03-29');                  -- 29
SELECT DAYNAME('2024-03-29');              -- Friday
SELECT WEEK('2024-03-29');                 -- Week number

-- Date difference
SELECT DATEDIFF('2024-03-29', '2024-01-01');         -- Days difference
SELECT TIMESTAMPDIFF(HOUR, timestamp1, timestamp2);  -- Hours difference
```

### Aggregate Functions
```sql
-- Count
SELECT COUNT(*);                           -- Count all rows
SELECT COUNT(DISTINCT email);              -- Count unique values

-- Sum
SELECT SUM(salary);                        -- Sum of values

-- Average
SELECT AVG(price);                         -- Average price

-- Min/Max
SELECT MIN(created_at) AS earliest;
SELECT MAX(created_at) AS latest;

-- Group concat
SELECT GROUP_CONCAT(name, ', ') FROM users;  -- name1, name2, name3
```

### Conditional Functions
```sql
-- IF
SELECT IF(age >= 18, 'Adult', 'Minor');

-- CASE
SELECT CASE 
    WHEN status = 'active' THEN 'Online'
    WHEN status = 'inactive' THEN 'Offline'
    ELSE 'Unknown'
END AS user_status;

-- IFNULL / COALESCE
SELECT IFNULL(phone, 'No phone');
SELECT COALESCE(phone, email, 'No contact');

-- NULLIF
SELECT NULLIF(0, 0);                       -- NULL (useful for division by zero)
```

---

## 4️⃣ QUERY PATTERNS

### SELECT with CONDITIONS
```sql
-- Basic SELECT
SELECT column1, column2 FROM table;

-- WHERE conditions
SELECT * FROM users WHERE age > 18;
SELECT * FROM users WHERE status = 'active' AND city = 'Jakarta';
SELECT * FROM users WHERE status IN ('active', 'pending');
SELECT * FROM users WHERE name LIKE 'John%';           -- Starts with John
SELECT * FROM users WHERE email LIKE '%@gmail.com';    -- Ends with @gmail.com

-- BETWEEN
SELECT * FROM orders WHERE amount BETWEEN 100000 AND 500000;

-- NULL checking
SELECT * FROM users WHERE phone IS NULL;
SELECT * FROM users WHERE phone IS NOT NULL;
```

### JOINs
```sql
-- INNER JOIN (only matching)
SELECT c.name, o.order_id
FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id;

-- LEFT JOIN (all from left, matching from right)
SELECT c.name, o.order_id
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id;

-- RIGHT JOIN (all from right, matching from left)
SELECT c.name, o.order_id
FROM customers c
RIGHT JOIN orders o ON c.customer_id = o.customer_id;

-- Multiple JOINs
SELECT c.name, o.order_id, p.name
FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id;

-- Self JOIN
SELECT a.name AS employee, b.name AS manager
FROM employees a
LEFT JOIN employees b ON a.manager_id = b.employee_id;
```

### GROUP BY & HAVING
```sql
-- Group by single column
SELECT category, COUNT(*) AS product_count
FROM products
GROUP BY category;

-- Group by multiple columns
SELECT category, brand, COUNT(*) AS product_count
FROM products
GROUP BY category, brand;

-- With HAVING (filter groups)
SELECT category, AVG(price) AS avg_price
FROM products
GROUP BY category
HAVING AVG(price) > 1000000;

-- With ORDER BY
SELECT category, COUNT(*) AS qty
FROM products
GROUP BY category
ORDER BY qty DESC;
```

### Subqueries
```sql
-- Subquery in WHERE
SELECT * FROM products
WHERE price > (SELECT AVG(price) FROM products);

-- Subquery in FROM
SELECT * FROM (
    SELECT product_id, AVG(rating) as avg_rating FROM reviews
    GROUP BY product_id
) AS top_products
WHERE avg_rating > 4;

-- Subquery with EXISTS
SELECT * FROM customers c
WHERE EXISTS (
    SELECT 1 FROM orders WHERE customer_id = c.customer_id
);
```

### CTEs (Common Table Expressions)
```sql
-- Single CTE
WITH top_customers AS (
    SELECT customer_id, SUM(amount) as total
    FROM orders
    GROUP BY customer_id
    HAVING SUM(amount) > 10000000
)
SELECT * FROM top_customers;

-- Multiple CTEs
WITH monthly_sales AS (
    SELECT MONTH(order_date) as month, SUM(amount) as revenue
    FROM orders
    GROUP BY MONTH(order_date)
),
avg_sales AS (
    SELECT AVG(revenue) as avg_monthly FROM monthly_sales
)
SELECT * FROM monthly_sales, avg_sales;
```

### UNION
```sql
-- Combine results from multiple queries
SELECT name FROM customers
UNION
SELECT product_name FROM products;

-- UNION ALL (include duplicates)
SELECT name FROM customers
UNION ALL
SELECT product_name FROM products;
```

### ORDER BY & LIMIT
```sql
-- Ascending (default)
SELECT * FROM products ORDER BY price ASC;

-- Descending
SELECT * FROM products ORDER BY price DESC;

-- Multiple columns
SELECT * FROM orders 
ORDER BY customer_id DESC, order_date ASC;

-- Limit and offset
SELECT * FROM products LIMIT 10;           -- First 10
SELECT * FROM products LIMIT 10 OFFSET 20; -- Skip 20, take 10
SELECT * FROM products LIMIT 20, 10;       -- Alternative syntax
```

---

## 5️⃣ PERFORMANCE TIPS

### Indexing
```sql
-- Create index
CREATE INDEX idx_email ON users(email);

-- Composite index (order matters!)
CREATE INDEX idx_customer_date ON orders(customer_id, order_date);

-- Unique index
CREATE UNIQUE INDEX idx_sku ON products(sku);

-- Full-text index (for searching)
CREATE FULLTEXT INDEX idx_description ON products(description);

-- Show indexes
SHOW INDEXES FROM users;

-- Drop index
DROP INDEX idx_email ON users;
```

### Query Optimization
```sql
-- ❌ SLOW: Using function in WHERE
SELECT * FROM orders WHERE YEAR(order_date) = 2024;

-- ✅ FAST: Use range
SELECT * FROM orders 
WHERE order_date >= '2024-01-01' AND order_date < '2025-01-01';

-- ❌ SLOW: SELECT *
SELECT * FROM products WHERE category = 'Electronics';

-- ✅ FAST: Select needed columns
SELECT product_id, name, price FROM products WHERE category = 'Electronics';

-- ❌ SLOW: Subquery in SELECT
SELECT id, (SELECT COUNT(*) FROM orders WHERE customer_id = c.id) FROM customers c;

-- ✅ FAST: Use JOIN with GROUP BY
SELECT c.id, COUNT(o.id)
FROM customers c
LEFT JOIN orders o ON c.id = o.customer_id
GROUP BY c.id;
```

### Execution Plan Analysis
```sql
-- Check query execution plan
EXPLAIN SELECT * FROM orders WHERE customer_id = 5;

-- Extended information
EXPLAIN EXTENDED SELECT * FROM orders WHERE customer_id = 5;

-- Visual plan (MySQL 8.0+)
EXPLAIN FORMAT=TREE SELECT * FROM orders WHERE customer_id = 5;

-- Analyze what to look for:
-- Type: ALL (bad), ref (good), range (good)
-- rows: Estimated rows scanned (lower is better)
-- Using index: Good sign (data from index)
-- Using where: Filter applied (expected)
```

### Connection & Session
```sql
-- Check current user
SELECT USER();

-- Show server variables
SHOW VARIABLES LIKE 'max_connections';

-- Set session variable
SET SESSION sql_mode='STRICT_TRANS_TABLES';

-- Kill long-running query
KILL QUERY process_id;

-- Check current processes
SHOW PROCESSLIST;
```

---

## 6️⃣ TROUBLESHOOTING

### Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| `Syntax error` | Wrong SQL syntax | Check quotation marks, commas, semicolons |
| `Table doesn't exist` | Wrong table name or database | Use correct database: `USE dbname;` |
| `Column not found` | Wrong column name | Check column spelling and case |
| `Duplicate entry for key` | Violates UNIQUE constraint | Check for duplicate values |
| `Foreign key constraint fails` | Referenced row doesn't exist | Ensure parent record exists first |
| `#1064 - Syntax error` | Invalid SQL | Use EXPLAIN to check syntax |

### Debugging Queries
```sql
-- Test with LIMIT to avoid large dataset
SELECT * FROM large_table LIMIT 10;

-- Check row count
SELECT COUNT(*) FROM table_name;

-- Show query result count
SELECT * FROM users; -- Check results in status bar

-- Enable query logging
SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';
SELECT * FROM mysql.general_log;
SET GLOBAL general_log = 'OFF';

-- Slow query log
SET GLOBAL slowquery_log = 'ON';
SHOW PROCESSLIST; -- See active queries
```

### Data Integrity Checks
```sql
-- Check for NULL values
SELECT * FROM users WHERE email IS NULL;

-- Check for duplicates
SELECT email, COUNT(*) FROM users GROUP BY email HAVING COUNT(*) > 1;

-- Check foreign key relationships
SELECT * FROM orders WHERE customer_id NOT IN (SELECT customer_id FROM customers);

-- Verify unique constraints
SELECT column, COUNT(*) FROM table GROUP BY column HAVING COUNT(*) > 1;
```

---

## 🎯 QUICK FORMULA REFERENCE

### Common Calculations
```sql
-- Percentage of total
SELECT amount, 
       ROUND(amount * 100.0 / (SELECT SUM(amount) FROM table), 2) as percentage
FROM table;

-- Growth rate
SELECT current_month,
       previous_month,
       ROUND((current_month - previous_month) / previous_month * 100, 2) as growth_percent;

-- Age from birthdate
SELECT DATEDIFF(CURDATE(), birth_date) / 365.25 as age FROM users;

-- Rank by value
SELECT name, salary,
       RANK() OVER (ORDER BY salary DESC) as rank
FROM employees;

-- Moving average
SELECT date, sales,
       AVG(sales) OVER (ORDER BY date ROWS BETWEEN 6 PRECEDING AND CURRENT ROW) as moving_avg
FROM sales;
```

---

## 📊 HELPFUL QUERIES

### Database Maintenance
```sql
-- Check table size
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb
FROM information_schema.tables
WHERE table_schema = 'database_name'
ORDER BY size_mb DESC;

-- Optimize tables
OPTIMIZE TABLE table_name;

-- Check for errors
CHECK TABLE table_name;

-- Repair table
REPAIR TABLE table_name;

-- Analyze table statistics
ANALYZE TABLE table_name;
```

### User Management
```sql
-- Create user
CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';

-- Grant privileges
GRANT SELECT, INSERT, UPDATE ON database.* TO 'username'@'localhost';

-- Show privileges
SHOW GRANTS FOR 'username'@'localhost';

-- Remove privilege
REVOKE INSERT ON database.* FROM 'username'@'localhost';

-- Delete user
DROP USER 'username'@'localhost';

-- Change password
ALTER USER 'username'@'localhost' IDENTIFIED BY 'new_password';
```

---

## 🚀 KEYBOARD SHORTCUTS (MySQL CLI)

| Shortcut | Action |
|----------|--------|
| `Ctrl+C` | Cancel current query |
| `Ctrl+D` | Exit MySQL |
| `Ctrl+U` | Clear current line |
| `Up arrow` | Previous command |
| `Down arrow` | Next command |
| `Home` | Start of line |
| `End` | End of line |

---

## 📚 RESOURCES FOR DEEPER LEARNING

- **Official MySQL Docs:** https://dev.mysql.com/doc/
- **MySQL Tutorial:** https://www.mysqltutorial.org/
- **Stack Overflow MySQL Tag:** https://stackoverflow.com/questions/tagged/mysql
- **Database Design:** https://en.wikipedia.org/wiki/Database_normalization
- **Query Optimization:** https://dev.mysql.com/doc/refman/8.0/en/optimization.html

---

**Last Updated:** March 29, 2026  
**MySQL Version:** 5.7 - 8.0 compatible  
**Keep this reference handy!** 📌
