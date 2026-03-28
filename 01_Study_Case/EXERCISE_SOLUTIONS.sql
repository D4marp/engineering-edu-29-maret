-- ============================================
-- BASIC MYSQL MASTERCLASS
-- EXERCISE SOLUTIONS & ANSWERS
-- ============================================
-- All exercises with complete solutions
-- Database: techstore
-- ============================================

-- ========== EXERCISE 1: SIMPLE QUERIES ==========

-- EXERCISE 1.1: Get all active customers ordered by name
-- LEVEL: Beginner
-- CONCEPTS: SELECT, WHERE, ORDER BY

-- Question: Find all customers with 'active' status, sorted alphabetically
SELECT 
    customer_id,
    name,
    email,
    phone,
    registered_date,
    status
FROM customers
WHERE status = 'active'
ORDER BY name ASC;

-- Expected output: 10 rows (all active customers)

---

-- EXERCISE 1.2: Show products below average price
-- LEVEL: Beginner-Intermediate
-- CONCEPTS: SELECT, WHERE, Subquery

-- Question: Which products are cheaper than the average price?
SELECT 
    product_id,
    name,
    category_id,
    price,
    stock
FROM products
WHERE price < (SELECT AVG(price) FROM products)
AND status = 'active'
ORDER BY price DESC;

-- Alternative using HAVING (with GROUP BY):
SELECT 
    product_id,
    name,
    price
FROM products
WHERE status = 'active'
AND price < (
    SELECT AVG(price) FROM products WHERE status = 'active'
)
ORDER BY price DESC;

---

-- EXERCISE 1.3: Count orders per customer
-- LEVEL: Beginner
-- CONCEPTS: COUNT, GROUP BY, JOIN

-- Question: How many orders did each customer make?
SELECT 
    c.customer_id,
    c.name,
    c.email,
    COUNT(o.order_id) AS total_orders
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id
ORDER BY total_orders DESC;

-- Expected output: 10 customers with their order counts

---

-- ========== EXERCISE 2: ADVANCED QUERIES ==========

-- EXERCISE 2.1: Find top 5 customers by total spending
-- LEVEL: Intermediate
-- CONCEPTS: SUM, GROUP BY, ORDER BY, LIMIT, JOIN

-- Question: Who are the top 5 customers based on total spending?
SELECT 
    c.customer_id,
    c.name,
    c.email,
    c.city,
    COUNT(DISTINCT o.order_id) AS number_of_orders,
    ROUND(SUM(o.total_amount), 2) AS total_spending,
    ROUND(AVG(o.total_amount), 2) AS avg_order_value
FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name, c.email, c.city
ORDER BY total_spending DESC
LIMIT 5;

-- Expected output: Top 5 customers with spending details

---

-- EXERCISE 2.2: Get product ratings with review count
-- LEVEL: Intermediate
-- CONCEPTS: AVG, COUNT, GROUP BY, HAVING, LEFT JOIN

-- Question: What are the average ratings for products with at least 2 reviews?
SELECT 
    p.product_id,
    p.name,
    c.name AS category,
    p.price,
    p.stock,
    COUNT(r.review_id) AS total_reviews,
    ROUND(AVG(r.rating), 2) AS average_rating,
    MAX(r.created_at) AS latest_review_date
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN reviews r ON p.product_id = r.product_id
GROUP BY p.product_id, p.name, c.name, p.price, p.stock
HAVING COUNT(r.review_id) >= 2
ORDER BY average_rating DESC, total_reviews DESC;

-- Expected output: Products with 2+ reviews sorted by rating

---

-- EXERCISE 2.3: Revenue by category for last 30 days
-- LEVEL: Intermediate-Advanced
-- CONCEPTS: DATE functions, SUM, GROUP BY, INNER JOIN, WHERE with DATE

-- Question: Which product categories generated the most revenue in the last 30 days?
SELECT 
    c.category_id,
    c.name AS category_name,
    COUNT(DISTINCT o.order_id) AS number_of_orders,
    COUNT(DISTINCT oi.product_id) AS unique_products_sold,
    SUM(oi.quantity) AS total_quantity_sold,
    ROUND(SUM(oi.quantity * oi.unit_price), 2) AS total_revenue,
    ROUND(AVG(o.total_amount), 2) AS avg_order_value
FROM categories c
INNER JOIN products p ON c.category_id = p.category_id
INNER JOIN order_items oi ON p.product_id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
AND o.status != 'cancelled'
GROUP BY c.category_id, c.name
ORDER BY total_revenue DESC;

-- Expected output: Revenue sorted by category for last 30 days

---

-- ========== EXERCISE 3: OPTIMIZATION CHALLENGE ==========

-- EXERCISE 3.1: Original SLOW Query
-- ❌ BEFORE (Slow - without proper indexing)

-- This is the slow version - don't use this!
SELECT * FROM orders o
JOIN customers c ON o.customer_id = c.customer_id
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE YEAR(o.order_date) = 2024
AND MONTH(o.order_date) = 3
AND o.status IN ('delivered', 'shipped')
ORDER BY o.order_date DESC;

-- Performance issue: YEAR() and MONTH() prevent index usage!

---

-- ✅ AFTER (Optimized Query)
-- OPTIMIZED VERSION

SELECT 
    o.order_id,
    o.order_date,
    o.total_amount,
    o.status,
    c.customer_id,
    c.name AS customer_name,
    c.email,
    p.product_id,
    p.name AS product_name,
    p.price,
    oi.quantity,
    (oi.quantity * oi.unit_price) AS line_total
FROM orders o
INNER JOIN customers c ON o.customer_id = c.customer_id
INNER JOIN order_items oi ON o.order_id = oi.order_id
INNER JOIN products p ON oi.product_id = p.product_id
WHERE o.order_date >= '2024-03-01'
AND o.order_date < '2024-04-01'
AND o.status IN ('delivered', 'shipped')
ORDER BY o.order_date DESC;

-- Improvements:
-- 1. Use date range instead of YEAR/MONTH functions
-- 2. Select only needed columns (not SELECT *)
-- 3. Uses existing indexes on order_date and customer_id

-- To verify performance:
-- EXPLAIN SELECT ... (replace with above query)
-- Look for: Type = 'ref' or 'range' (GOOD) vs 'ALL' (BAD)

---

-- ========== EXERCISE 4: REAL-WORLD SCENARIOS ==========

-- EXERCISE 4.1: Customer Lifetime Value (CLV) Analysis
-- LEVEL: Advanced
-- CONCEPTS: Complex JOINs, Window Functions, CASE WHEN

-- Question: Analyze customers with CLV, segments, and rankings
WITH customer_spending AS (
    SELECT 
        c.customer_id,
        c.name,
        c.email,
        c.city,
        c.registered_date,
        COUNT(DISTINCT o.order_id) AS frequency,
        DATEDIFF(CURDATE(), MAX(o.order_date)) AS days_since_purchase,
        ROUND(SUM(o.total_amount), 2) AS lifetime_value,
        ROUND(AVG(o.total_amount), 2) AS avg_order_value
    FROM customers c
    LEFT JOIN orders o ON c.customer_id = o.customer_id
    WHERE o.status IN ('delivered', 'shipped')
    GROUP BY c.customer_id, c.name, c.email, c.city, c.registered_date
),
customer_segments AS (
    SELECT 
        *,
        CASE 
            WHEN frequency >= 5 AND lifetime_value >= 20000000 THEN 'VIP Gold'
            WHEN frequency >= 3 AND lifetime_value >= 10000000 THEN 'VIP Silver'
            WHEN frequency >= 2 THEN 'Regular'
            WHEN frequency >= 1 THEN 'One-time Buyer'
            ELSE 'Never Purchased'
        END AS segment,
        ROW_NUMBER() OVER (ORDER BY lifetime_value DESC) AS clv_rank
    FROM customer_spending
)
SELECT 
    customer_id,
    name,
    email,
    city,
    frequency,
    days_since_purchase,
    lifetime_value,
    avg_order_value,
    segment,
    clv_rank
FROM customer_segments
ORDER BY clv_rank;

-- Expected output: All customers with CLV analysis and segmentation

---

-- EXERCISE 4.2: Product Performance Report
-- LEVEL: Advanced
-- CONCEPTS: Multiple JOINs, Conditional aggregation, Window Functions

-- Question: Which products are performing well vs poorly?
SELECT 
    p.product_id,
    p.name AS product_name,
    c.name AS category,
    p.price,
    p.stock AS current_stock,
    COUNT(DISTINCT oi.order_item_id) AS times_purchased,
    SUM(oi.quantity) AS total_qty_sold,
    ROUND(SUM(oi.quantity * oi.unit_price), 2) AS total_revenue,
    ROUND((p.price - p.cost) * SUM(oi.quantity), 2) AS gross_profit,
    ROUND(COUNT(DISTINCT r.review_id)) AS review_count,
    ROUND(AVG(r.rating), 2) AS avg_rating,
    CASE 
        WHEN SUM(oi.quantity) >= 10 AND AVG(r.rating) >= 4.0 THEN 'Star Product'
        WHEN SUM(oi.quantity) >= 5 THEN 'Good Seller'
        WHEN SUM(oi.quantity) >= 1 THEN 'Okay'
        ELSE 'Not Selling'
    END AS performance_status,
    RANK() OVER (ORDER BY SUM(oi.quantity * oi.unit_price) DESC) AS revenue_rank
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN orders o ON oi.order_id = o.order_id AND o.status != 'cancelled'
LEFT JOIN reviews r ON p.product_id = r.product_id
WHERE p.status = 'active'
GROUP BY p.product_id, p.name, c.name, p.price, p.stock, p.cost
ORDER BY total_revenue DESC;

-- Expected output: Product performance ranking

---

-- EXERCISE 4.3: Sales Trend Analysis
-- LEVEL: Intermediate-Advanced
-- CONCEPTS: GROUP BY DATE, Window Functions, Mathematical calculations

-- Question: What is the daily and monthly sales trend?

-- Daily Sales Trend
SELECT 
    DATE(order_date) AS sales_date,
    DAYNAME(order_date) AS day_of_week,
    COUNT(order_id) AS orders_count,
    COUNT(DISTINCT customer_id) AS unique_customers,
    ROUND(SUM(total_amount), 2) AS daily_revenue,
    ROUND(AVG(total_amount), 2) AS avg_order_value,
    SUM(SUM(total_amount)) OVER (
        ORDER BY DATE(order_date) 
        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
    ) AS cumulative_revenue
FROM orders
WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
AND status != 'cancelled'
GROUP BY DATE(order_date)
ORDER BY sales_date DESC;

-- Monthly Sales Trend with growth rate
WITH monthly_data AS (
    SELECT 
        DATE_TRUNC(order_date, MONTH) AS month,
        COUNT(order_id) AS orders,
        ROUND(SUM(total_amount), 2) AS revenue
    FROM orders
    WHERE status != 'cancelled'
    GROUP BY DATE_TRUNC(order_date, MONTH)
)
SELECT 
    month,
    orders,
    revenue,
    LAG(revenue) OVER (ORDER BY month) AS prev_month_revenue,
    ROUND(((revenue - LAG(revenue) OVER (ORDER BY month)) / 
           LAG(revenue) OVER (ORDER BY month) * 100), 2) AS growth_percentage
FROM monthly_data
ORDER BY month DESC;

---

-- EXERCISE 4.4: Inventory Management Report
-- LEVEL: Intermediate
-- CONCEPTS: GROUP BY, CASE WHEN, Aggregation

-- Question: What is the current inventory status?
SELECT 
    c.name AS category,
    COUNT(p.product_id) AS total_products,
    SUM(p.stock) AS total_units,
    ROUND(AVG(p.stock), 2) AS avg_stock_per_product,
    MIN(p.stock) AS lowest_stock,
    MAX(p.stock) AS highest_stock,
    ROUND(SUM(p.stock * p.price), 2) AS inventory_value,
    SUM(CASE WHEN p.stock <= 10 THEN 1 ELSE 0 END) AS low_stock_items,
    ROUND((SUM(CASE WHEN p.stock <= 10 THEN 1 ELSE 0 END) * 100.0) / 
          COUNT(p.product_id), 2) AS low_stock_percentage,
    SUM(CASE WHEN p.stock = 0 THEN 1 ELSE 0 END) AS out_of_stock_items
FROM categories c
INNER JOIN products p ON c.category_id = p.category_id
WHERE p.status = 'active'
GROUP BY c.category_id, c.name
ORDER BY inventory_value DESC;

-- Most wanted products (high demand, low stock)
SELECT 
    p.product_id,
    p.name,
    c.name AS category,
    p.stock AS current_stock,
    COUNT(DISTINCT oi.order_item_id) AS times_ordered_past_30days,
    ROUND((SUM(oi.quantity) * 100.0) / (p.stock + SUM(oi.quantity)), 2) AS demand_percentage
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN orders o ON oi.order_id = o.order_id 
    AND o.order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
WHERE p.status = 'active'
GROUP BY p.product_id, p.name, c.name, p.stock
HAVING COUNT(DISTINCT oi.order_item_id) > 0
ORDER BY demand_percentage DESC;

---

-- EXERCISE 4.5: Payment Method Analysis
-- LEVEL: Intermediate
-- CONCEPTS: GROUP BY ENUM, Calculate percentages

-- Question: Which payment methods are most popular?
SELECT 
    payment_method,
    COUNT(order_id) AS total_transactions,
    ROUND(AVG(total_amount), 2) AS avg_transaction_amount,
    ROUND(SUM(total_amount), 2) AS total_revenue,
    ROUND((COUNT(order_id) * 100.0) / (SELECT COUNT(*) FROM orders), 2) AS transaction_percentage,
    ROUND((SUM(total_amount) * 100.0) / (SELECT SUM(total_amount) FROM orders), 2) AS revenue_percentage,
    COUNT(DISTINCT customer_id) AS unique_customers
FROM orders
WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
AND status IN ('delivered', 'shipped', 'confirmed')
GROUP BY payment_method
ORDER BY total_revenue DESC;

---

-- ========== BONUS: COMMON MISTAKES & FIXES ==========

-- MISTAKE 1: Missing NULL handling in calculations
-- ❌ WRONG: 
SELECT customer_id, name, age + 5 FROM customers;
-- Problem: If age is NULL, result is NULL

-- ✅ CORRECT:
SELECT customer_id, name, COALESCE(age, 0) + 5 FROM customers;

---

-- MISTAKE 2: Wrong JOIN type
-- ❌ WRONG (missing active customers without orders):
SELECT c.name, COUNT(o.order_id) FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id;

-- ✅ CORRECT (shows all customers):
SELECT c.name, COUNT(o.order_id) FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id;

---

-- MISTAKE 3: Slow query with function in WHERE
-- ❌ SLOW:
SELECT * FROM orders WHERE YEAR(order_date) = 2024;

-- ✅ FAST:
SELECT * FROM orders 
WHERE order_date >= '2024-01-01' AND order_date < '2025-01-01';

---

-- MISTAKE 4: GROUP BY without aggregation
-- ❌ WRONG (unreliable results):
SELECT customer_id, name, address FROM orders
GROUP BY customer_id;

-- ✅ CORRECT:
SELECT DISTINCT customer_id, name, address FROM customers;

---

-- ========== TIPS FOR SUCCESS ==========

/* TIPS:
1. Always use EXPLAIN to check query performance
2. Index columns that are frequently used in WHERE, JOIN, OrderBy
3. Use JOINs instead of subqueries when possible
4. Select only needed columns, avoid SELECT *
5. Use HAVING for filtering aggregated results
6. Use CTEs for complex queries to improve readability
7. Always handle NULL values properly
8. Test with realistic data volume
9. Monitor slow queries (> 2 seconds)
10. Use transactions for multi-step operations
*/

-- Execute queries one by one and examine the output
-- Compare performance with EXPLAIN
-- Try modifying queries to return different results
-- Practice is the key to mastery!
