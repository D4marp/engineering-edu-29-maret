# 🚀 TechStore Laravel Project - Setup Guide

## Actual Laravel Project for MySQL Masterclass Study Case

Ini adalah **implementasi Laravel nyata dan berfungsi** (bukan hanya code snippets) yang langsung bisa dijalankan.

---

## 📋 REQUIREMENTS

```
PHP 8.1+
Composer
MySQL 8.0+
Node.js (optional, for asset building)
```

### Check Your Setup

```bash
php --version           # Should be 8.1+
composer --version      # Should be installed
mysql --version         # Should be 8.0+
```

---

## 🎯 QUICK START (5 minutes)

### 1. Navigate to Project Directory

```bash
cd /Users/HCMPublic/Documents/Damar/PHP/techstore-app
```

### 2. Install Dependencies

```bash
composer install
```

**⏱️ Takes ~2-3 minutes on first install**

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Create MySQL Database

```bash
mysql -u root -p

# In MySQL console:
CREATE DATABASE techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5. Run Migrations (Create Tables)

```bash
php artisan migrate
```

### 6. Seed Sample Data

```bash
php artisan db:seed
```

### 7. Start Development Server

```bash
php artisan serve
```

✅ **Application running at:** `http://127.0.0.1:8000`

---

## 🔍 VERIFY INSTALLATION

Open browser and visit:

1. **Dashboard:** http://127.0.0.1:8000/
2. **Products:** http://127.0.0.1:8000/products
3. **Customers:** http://127.0.0.1:8000/customers
4. **Orders:** http://127.0.0.1:8000/orders

Should see data from seeders!

---

## 📁 PROJECT STRUCTURE

```
techstore-app/
├── app/
│   ├── Models/           ← 7 Eloquent Models (Category, Product, Customer, Order, etc)
│   └── Http/
│       └── Controllers/  ← Business Logic Controllers
├── database/
│   ├── migrations/       ← Table Schema (7 tables)
│   └── seeders/          ← Sample Data
├── resources/
│   └── views/            ← Blade Templates (Bootstrap UI)
├── routes/
│   └── web.php          ← URL Routes
├── .env                  ← Configuration (MySQL connection)
└── composer.json         ← Dependencies
```

---

## 🗄️ DATABASE STRUCTURE

**7 Tables Created:**

1. **categories** - Product categories
2. **products** - Inventory items
3. **customers** - Customer information
4. **orders** - Order headers
5. **order_items** - Order details
6. **reviews** - Customer reviews
7. **inventory_logs** - Stock tracking

---

## 🎨 FEATURES IMPLEMENTED

### ✅ Complete CRUD Operations
- **Products** - Create, read, update, delete, filter by category/price/status
- **Customers** - Full customer management with segmentation
- **Orders** - Order creation with transaction handling & inventory updates
- **Categories** - Manage product categories
- **Reviews** - Customer product reviews

### ✅ Dashboard Analytics
- 📊 Total stats (revenue, orders, products, customers)
- 📈 Recent orders list
- ⭐ Top selling products
- 👥 Top customers by spending
- ⚠️ Low stock alerts

### ✅ Advanced Features
- 🔄 Inventory tracking with transaction logs
- 💰 Order total calculations (subtotal, tax, discount, shipping)
- ⭐ Product ratings & reviews
- 🏷️ Customer segmentation (Regular, Silver, Gold, Premium)
- 🔍 Advanced filtering and search
- 📄 Pagination on all lists

### ✅ Data Integrity
- Foreign key constraints
- Transaction handling for complex operations
- Stock automatically updated when order created
- Inventory logs maintained automatically

---

## 📝 USEFUL COMMANDS

### Generate Fresh Database
```bash
# Reset everything (deletes all data!)
php artisan migrate:fresh

# Re-seed with sample data
php artisan db:seed
```

### Create New Seeder
```bash
php artisan make:seeder ProductSeeder
```

### View Database
```bash
mysql -u root -p techstore
```

### Stop Server
```
Press Ctrl+C in terminal
```

---

## 🔗 ROUTES AVAILABLE

### Web Interface (Browser)

| Route | Purpose |
|-------|---------|
| `/` | Dashboard with analytics |
| `/products` | List all products |
| `/products/create` | Add new product |
| `/products/{id}` | View product details |
| `/products/{id}/edit` | Edit product |
| `/customers` | List customers |
| `/orders` | List orders |
| `/orders/create` | Create new order |
| `/orders/{id}` | Order details |
| `/categories` | Manage categories |
| `/reviews` | Customer reviews list |
| `/analytics` | Advanced analytics |

---

## 🚨 TROUBLESHOOTING

### Issue: "SQLSTATE[HY000] [1045] Access denied"
**Solution:** Update `.env` with correct MySQL credentials

```
DB_PASSWORD=your_mysql_password
```

### Issue: "Class not found"
**Solution:** Run composer install

```bash
composer install
```

### Issue: Database doesn't exist
**Solution:** Create database manually

```bash
mysql -u root -p
CREATE DATABASE techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Issue: Migrations fail
**Solution:** Check database connection, then:

```bash
php artisan migrate:fresh
php artisan db:seed
```

---

## 📚 FOLDER BREAKDOWN

### `/app/Models/` - Eloquent Models
- `Category.php` - Product categories with relationships
- `Product.php` - Products with pricing, inventory, reviews
- `Customer.php` - Customer data with calculated attributes
- `Order.php` - Orders with customer relationship
- `OrderItem.php` - Individual items in orders
- `Review.php` - Product reviews and ratings
- `InventoryLog.php` - Stock transaction history

### `/app/Http/Controllers/` - Business Logic
- `DashboardController` - Analytics and KPIs
- `ProductController` - CRUD for products
- `CustomerController` - CRUD for customers
- `OrderController` - Complex order processing with transactions
- `CategoryController` - Category management
- `ReviewController` - Review management

### `/resources/views/` - Blade Templates
- `layouts/app.blade.php` - Main layout with sidebar navigation
- `dashboard/index.blade.php` - Dashboard with stats
- `products/` - Product views (list, show, form)
- `customers/` - Customer views (list, show, form)
- `orders/` - Order views (list, show)

### `/database/migrations/` - Schema
- Create all 7 tables with proper indexes
- Foreign key constraints
- Validation rules (CHECK constraints)

### `/database/seeders/` - Sample Data
- Creates 4 categories
- Creates 6 sample products
- Creates 3 sample customers
- Creates 2 sample orders with items
- Creates sample reviews

---

## 🎓 LEARNING OUTCOMES

After running & exploring this project, you'll understand:

✅ Laravel MVC architecture (Models, Controllers, Views)  
✅ Eloquent ORM and relationships  
✅ Database migrations & seeders  
✅ Blade templating & form handling  
✅ Request validation  
✅ Transaction handling in Laravel  
✅ Pagination & filtering  
✅ RESTful routing  
✅ Bootstrap integration  
✅ Real-world business logic patterns  

---

## 🔐 SECURITY NOTES

This is a **development/learning project**. For production:

- ✅ Use environment variables for secrets
- ✅ Enable HTTPS
- ✅ Add authentication/authorization
- ✅ Implement rate limiting
- ✅ Add CSRF protection (already included)
- ✅ Validate all user inputs (already done)
- ✅ Use hashed passwords for admin access

---

## 📞 NEXT STEPS

1. ✅ Run the project using steps above
2. 📊 Explore the dashboard
3. 🛍️ Create test orders
4. 📈 Check analytics
5. 💻 Review code in `/app/Models` and `/app/Http/Controllers`
6. 🎨 Customize views in `/resources/views`

---

## 📖 FILE LOCATIONS

**Project Root:** `/Users/HCMPublic/Documents/Damar/PHP/techstore-app/`

**Key Files:**
- Database config: `.env` (line: DB_*)
- Routes: `routes/web.php`
- Models: `app/Models/*.php`
- Controllers: `app/Http/Controllers/*.php`
- Views: `resources/views/**/*.blade.php`
- Migrations: `database/migrations/*.php`
- Seeders: `database/seeders/*.php`

---

## 🎉 DEMO DATA

**Account Info (if using authentication):**
- Email: test@example.com
- Password: password

**Sample Products:**
- ASUS ROG Gaming Laptop
- Samsung 27" Monitor
- Mechanical RGB Keyboard

**Sample Customers:**
- Ahmad Suryanto (Jakarta)
- Siti Nurhaliza (Surabaya)

---

## ⏰ ESTIMATED SETUP TIME

- ⏱️ Install dependencies: **2-3 minutes**
- ⏱️ Create database: **1 minute**
- ⏱️ Run migrations & seeds: **30 seconds**
- ⏱️ Start server: **5 seconds**

**Total: ~5 minutes to full working application!**

---

**Happy coding! 🚀**

*TechStore Laravel Project - Built for MySQL Masterclass*

*Last Updated: March 29, 2026*
