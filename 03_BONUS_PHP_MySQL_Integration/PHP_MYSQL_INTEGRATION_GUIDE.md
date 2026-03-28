# 🔗 MySQL dengan PHP: Integration Guide
## Bonus Material untuk MySQL Masterclass

---

## 📌 OVERVIEW

File ini berisi panduan praktis untuk mengintegrasikan MySQL dengan PHP, melengkapi MySQL Masterclass dengan aspek aplikasi real-world.

---

## 1️⃣ SETUP DEVELOPMENT ENVIRONMENT

### Requirement
```
MySQL Server  - 5.7+
PHP           - 7.4+ (recommended 8.0+)
Web Server    - Apache atau Nginx
IDE           - VS Code, PhpStorm, atau Sublime
```

### Installation (macOS)
```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install MySQL
brew install mysql
brew services start mysql

# Install PHP
brew install php

# Start services
brew services start php
```

### Create Project Structure
```
project/
├── public/
│   ├── index.php
│   └── css/
├── src/
│   ├── Database.php
│   ├── Models/
│   └── Controllers/
├── config/
│   └── database.php
└── .env (hide sensitive data)
```

---

## 2️⃣ DATABASE CONNECTION

### Simple MySQLi Connection
```php
<?php
// File: config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password'); // Use environment variable in production!
define('DB_NAME', 'techstore');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

echo "Connected successfully";
?>
```

### PDO Connection (Recommended)
```php
<?php
// File: config/database.php
// PDO is more secure and flexible

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=UTF8MB4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully using PDO";
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage();
    exit;
}
?>
```

### Class-Based Database Helper
```php
<?php
// File: src/Database.php

class Database {
    private $host = 'localhost';
    private $db_name = 'techstore';
    private $user = 'root';
    private $password = '';
    private $pdo;
    
    public function connect() {
        $dsn = 'mysql:host=' . $this->host . 
               ';dbname=' . $this->db_name . 
               ';charset=utf8mb4';
        
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

// Usage:
$database = new Database();
$pdo = $database->connect();
?>
```

---

## 3️⃣ BASIC OPERATIONS (CRUD)

### CREATE (INSERT)
```php
<?php
// INSERT single record
$stmt = $pdo->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
$stmt->execute(['John Doe', 'john@example.com', '081234567890']);

// Get inserted ID
$lastId = $pdo->lastInsertId();
echo "Record inserted with ID: " . $lastId;

// INSERT multiple records
$data = [
    ['Jane', 'jane@example.com', '081234567891'],
    ['Bob', 'bob@example.com', '081234567892'],
];

$stmt = $pdo->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
foreach ($data as $row) {
    $stmt->execute($row);
}
echo "Multiple records inserted";
?>
```

### READ (SELECT)
```php
<?php
// Get single record
$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([1]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
echo $customer['name'];

// Get all records
$stmt = $pdo->prepare("SELECT * FROM customers WHERE status = ? ORDER BY created_at DESC");
$stmt->execute(['active']);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($customers as $customer) {
    echo $customer['name'] . '<br>';
}

// Count records
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM customers WHERE status = ?");
$stmt->execute(['active']);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total active customers: " . $result['total'];
?>
```

### UPDATE
```php
<?php
// Update single field
$stmt = $pdo->prepare("UPDATE customers SET email = ? WHERE customer_id = ?");
$stmt->execute(['newemail@example.com', 1]);

// Update multiple fields
$stmt = $pdo->prepare("UPDATE customers SET email = ?, phone = ? WHERE customer_id = ?");
$stmt->execute(['newemail@example.com', '081234567899', 1]);
echo "Rows affected: " . $stmt->rowCount();

// Update with calculation
$stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
$stmt->execute([5, 10]); // Reduce stock by 5
?>
```

### DELETE
```php
<?php
// Delete single record
$stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
$stmt->execute([1]);
echo "Rows deleted: " . $stmt->rowCount();

// Soft delete (update status instead)
$stmt = $pdo->prepare("UPDATE customers SET status = 'inactive' WHERE customer_id = ?");
$stmt->execute([1]);
?>
```

---

## 4️⃣ ADVANCED QUERIES

### Complex Queries with JOINs
```php
<?php
// Get customer with their orders
$sql = "SELECT c.customer_id, c.name, c.email, 
               COUNT(o.order_id) as total_orders, 
               SUM(o.total_amount) as total_spent
        FROM customers c
        LEFT JOIN orders o ON c.customer_id = o.customer_id
        WHERE c.status = ?
        GROUP BY c.customer_id
        HAVING total_orders > ?
        ORDER BY total_spent DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['active', 2]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    echo $row['name'] . ' - Total Orders: ' . $row['total_orders'] . '<br>';
}
?>
```

### Pagination
```php
<?php
// Get page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total count
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE status = ?");
$stmt->execute(['active']);
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total / $per_page);

// Get paginated data
$stmt = $pdo->prepare("SELECT * FROM products 
                       WHERE status = ? 
                       ORDER BY created_at DESC 
                       LIMIT ? OFFSET ?");
$stmt->execute(['active', $per_page, $offset]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display pagination
echo "Page $page of $total_pages<br>";
foreach ($products as $product) {
    echo $product['name'] . '<br>';
}

// Next/Previous links
if ($page > 1) {
    echo '<a href="?page=' . ($page - 1) . '">Previous</a>';
}
if ($page < $total_pages) {
    echo '<a href="?page=' . ($page + 1) . '">Next</a>';
}
?>
```

### Search & Filter
```php
<?php
// Build search query dynamically
$conditions = [];
$params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $conditions[] = "(name LIKE ? OR description LIKE ?)";
    $search = '%' . $_GET['search'] . '%';
    $params[] = $search;
    $params[] = $search;
}

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $conditions[] = "category_id = ?";
    $params[] = $_GET['category'];
}

if (isset($_GET['price_from']) && !empty($_GET['price_from'])) {
    $conditions[] = "price >= ?";
    $params[] = $_GET['price_from'];
}

if (isset($_GET['price_to']) && !empty($_GET['price_to'])) {
    $conditions[] = "price <= ?";
    $params[] = $_GET['price_to'];
}

$sql = "SELECT * FROM products WHERE status = 'active'";
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY created_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
array_unshift($params, 'active'); // Add status parameter at beginning
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo $product['name'] . ' - $' . $product['price'] . '<br>';
}
?>
```

---

## 5️⃣ TRANSACTIONS

### Basic Transaction
```php
<?php
// Transaction example: Process order
try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Step 1: Create order
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)");
    $stmt->execute([1, 500000]);
    $order_id = $pdo->lastInsertId();
    
    // Step 2: Add order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$order_id, 5, 2, 250000]);
    
    // Step 3: Update inventory
    $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
    $stmt->execute([2, 5]);
    
    // Commit transaction
    $pdo->commit();
    echo "Order processed successfully";
    
} catch (Exception $e) {
    // Rollback if any error
    $pdo->rollback();
    echo "Error: " . $e->getMessage();
}
?>
```

---

## 6️⃣ SECURITY BEST PRACTICES

### ✅ Prepared Statements (SQL Injection Prevention)
```php
<?php
// ❌ WRONG - Vulnerable to SQL injection
$email = $_POST['email'];
$sql = "SELECT * FROM users WHERE email = '$email'";

// ✅ CORRECT - Use prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$_POST['email']]);
$user = $stmt->fetch();
?>
```

### ✅ Input Validation
```php
<?php
// Validate email
$email = $_POST['email'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit;
}

// Validate and sanitize string
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$name = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);

// Validate number
$age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
if ($age === false || $age < 0 || $age > 120) {
    echo "Invalid age";
    exit;
}
?>
```

### ✅ Password Hashing
```php
<?php
// Hash password for storage
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$_POST['email'], $password]);

// Verify password
$stored_hash = $user['password']; // From database
if (password_verify($_POST['password'], $stored_hash)) {
    echo "Password is correct";
} else {
    echo "Wrong password";
}
?>
```

### ✅ Environment Variables
```php
<?php
// Load from .env file (use composer: composer require vlucas/phpdotenv)
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access variables
$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];

// .env file content:
// DB_HOST=localhost
// DB_USER=app_user
// DB_PASS=SecurePassword123
// DB_NAME=techstore
?>
```

---

## 7️⃣ MODEL-VIEW-CONTROLLER (MVC) PATTERN

### Simple Model Class
```php
<?php
// File: src/Models/Customer.php

class Customer {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Get all customers
    public function getAll($status = null) {
        $sql = "SELECT * FROM customers WHERE 1 = 1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Create new
    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO customers (name, email, phone, address, city) 
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['city']
        ]);
    }
    
    // Update
    public function update($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE customers SET name = ?, email = ?, phone = ? WHERE customer_id = ?"
        );
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $id
        ]);
    }
    
    // Delete
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
        return $stmt->execute([$id]);
    }
}
?>
```

### Controller Usage
```php
<?php
// File: public/customers.php

require '../src/Database.php';
require '../src/Models/Customer.php';

// Initialize
$database = new Database();
$pdo = $database->connect();
$customer_model = new Customer($pdo);

// Route handling
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $customers = $customer_model->getAll('active');
        break;
    
    case 'view':
        $customer = $customer_model->getById($_GET['id']);
        break;
    
    case 'create':
        if ($_POST) {
            $customer_model->create($_POST);
            header('Location: customers.php?action=list');
            die();
        }
        break;
    
    case 'update':
        if ($_POST) {
            $customer_model->update($_GET['id'], $_POST);
            header('Location: customers.php?action=view&id=' . $_GET['id']);
            die();
        }
        $customer = $customer_model->getById($_GET['id']);
        break;
    
    case 'delete':
        $customer_model->delete($_GET['id']);
        header('Location: customers.php?action=list');
        die();
}
?>
```

---

## 8️⃣ PERFORMANCE OPTIMIZATION

### Use Indexes in Queries
```php
<?php
// Make sure columns used in WHERE are indexed
$stmt = $pdo->prepare(
    "SELECT id, name, email FROM customers 
     WHERE status = ? AND created_at > ? 
     ORDER BY created_at DESC 
     LIMIT 20"
);
$stmt->execute(['active', date('Y-m-d', strtotime('-30 days'))]);

// Check query performance
$statement = $pdo->query("EXPLAIN SELECT ...");
$plan = $statement->fetchAll(PDO::FETCH_ASSOC);
// Look for 'type' = 'ref' (good) not 'ALL' (bad)
?>
```

### Caching
```php
<?php
// Simple file cache
$cache_file = '/tmp/products_cache.json';
$cache_time = 3600; // 1 hour

if (file_exists($cache_file) && filemtime($cache_file) > time() - $cache_time) {
    // Use cached data
    $products = json_decode(file_get_contents($cache_file), true);
} else {
    // Fetch from database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE status = 'active'");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Save to cache
    file_put_contents($cache_file, json_encode($products));
}

echo json_encode($products);
?>
```

### Connection Pooling
```php
<?php
// Reuse connection throughout request
static $pdo = null;

if (!$pdo) {
    $dsn = "mysql:host=localhost;dbname=techstore;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_PERSISTENT => true,  // Persistent connection
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}

return $pdo;
?>
```

---

## 9️⃣ DEBUGGING & LOGGING

### Enable Error Reporting
```php
<?php
// During development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// In production - log errors instead
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
?>
```

### Log Database Queries
```php
<?php
class Database {
    private $log_file = '/tmp/query.log';
    
    public function logQuery($query, $params) {
        $log = date('Y-m-d H:i:s') . " | " . $query . " | " . json_encode($params) . "\n";
        file_put_contents($this->log_file, $log, FILE_APPEND);
    }
    
    public function prepareAndLog($query, $params) {
        $this->logQuery($query, $params);
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}

// Track slow queries
$start = microtime(true);
$stmt = $pdo->prepare("SELECT * FROM large_table WHERE ...");
$stmt->execute($params);
$time = microtime(true) - $start;

if ($time > 0.5) { // More than 500ms
    error_log("SLOW QUERY ($time): " . $query);
}
?>
```

---

## 🔟 COMMON PATTERNS & SNIPPETS

### API Response Format
```php
<?php
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'data' => null,
    'errors' => []
];

try {
    // Your code here
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$_GET['id']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        throw new Exception("Customer not found");
    }
    
    $response['success'] = true;
    $response['message'] = "Customer retrieved";
    $response['data'] = $customer;
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
?>
```

### Form Processing
```php
<?php
$errors = [];
$data = [];

// Validate
if (empty($_POST['name'])) {
    $errors['name'] = 'Name is required';
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email is required';
}

if (empty($errors)) {
    // Process form
    try {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email) VALUES (?, ?)");
        $stmt->execute([$_POST['name'], $_POST['email']]);
        
        $data['success'] = true;
        $data['message'] = "Customer added successfully";
        $data['id'] = $pdo->lastInsertId();
        
    } catch (Exception $e) {
        $data['success'] = false;
        $data['message'] = "Error: " . $e->getMessage();
    }
} else {
    $data['success'] = false;
    $data['errors'] = $errors;
}

// Return JSON or redirect
if (isset($_GET['ajax'])) {
    echo json_encode($data);
} else {
    $_SESSION['message'] = $data['message'];
    header('Location: form.php');
}
?>
```

---

## 📚 FRAMEWORK ALTERNATIVES

Untuk aplikasi production, pertimbangkan menggunakan framework:

### Laravel (Recommended)
```php
// Using Eloquent ORM
$customers = Customer::where('status', 'active')->get();
```

### Symfony
```php
// Using Doctrine ORM
$repository = $doctrine->getRepository(Customer::class);
$customers = $repository->findBy(['status' => 'active']);
```

### CodeIgniter
```php
$this->db->where('status', 'active');
$customers = $this->db->get('customers')->result_array();
```

---

## 🎯 EXERCISE: BUILD A SIMPLE CRUD APP

**Task:** Create a customer management application

1. **List Customers** - Show all active customers with pagination
2. **View Details** - Show single customer details
3. **Add Customer** - Form to add new customer
4. **Edit Customer** - Update customer information
5. **Delete Customer** - Remove customer from database

**Requirements:**
- Use prepared statements
- Proper error handling
- Form validation
- Session management
- Simple HTML/CSS UI

**Time:** ~4-6 hours

**Deliverables:**
1. Database schema (use provided schema)
2. Model class for Customer
3. Controllers/Pages
4. HTML views
5. CSS styling

---

## 📞 RESOURCES

- **PHP Official Docs:** https://www.php.net/manual/
- **PDO Documentation:** https://www.php.net/manual/en/book.pdo.php
- **MySQL PHP Tutorial:** https://www.w3schools.com/php/php_mysql_intro.asp
- **Laravel Documentation:** https://laravel.com/docs
- **OWASP Top 10:** https://owasp.org/www-project-top-ten/

---

**Keep learning & code responsibly! 🚀**

*Last Updated: March 29, 2026*
