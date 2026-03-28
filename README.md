# 📚 Engineering Education - 29 Maret 2026

**A comprehensive learning project covering PHP, MySQL, Laravel, and E-Commerce Development**

---

## 📖 Overview

This repository contains complete educational materials and a real-world e-commerce application built with **Laravel**, **MySQL**, and **PHP**. It includes study cases, presentations, and a fully functional TechStore application with modern UI/UX.

### 🎯 Project Goals
- Master fundamental and advanced MySQL concepts
- Build production-ready Laravel applications
- Implement professional UI/UX design patterns
- Learn database optimization and security best practices
- Integrate PHP with MySQL for real-world scenarios

---

## 📁 Repository Structure

```
engineering-edu-29-maret/
├── 📂 01_Study_Case/              # Complete e-commerce study case
│   ├── STUDY_CASE_E_COMMERCE_PLATFORM.md
│   ├── EXERCISE_SOLUTIONS.sql
│   └── SQL_SCRIPTS_READY_TO_USE.sql
│
├── 📂 02_Draft_PPT/               # Educational presentations
│   ├── DRAFT_PPT_MYSQL_MASTERCLASS.md
│   └── QUICK_REFERENCE_CHEAT_SHEET.md
│
├── 📂 03_BONUS_PHP_MySQL_Integration/  # Integration guide
│   └── PHP_MYSQL_INTEGRATION_GUIDE.md
│
├── 📂 techstore-app/              # Laravel e-commerce application
│   ├── app/                       # Application logic
│   ├── database/                  # Migrations & seeders
│   ├── resources/                 # Views & assets
│   ├── routes/                    # API & web routes
│   ├── config/                    # Configuration files
│   └── composer.json             # PHP dependencies
│
├── README.md                      # This file
├── FILE_STRUCTURE_VISUAL.md      # Visual project structure
└── FILE_SUMMARY.md               # Detailed file descriptions

```

---

## 🚀 Quick Start

### Prerequisites
- **PHP** 8.4+ 
- **Composer** (latest)
- **MySQL** 8.0+
- **Node.js** 18+ (for frontend build)
- **Git**

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/D4marp/engineering-edu-29-maret.git
cd engineering-edu-29-maret

# 2. Navigate to TechStore app
cd techstore-app

# 3. Install Composer dependencies
composer install

# 4. Install Node dependencies
npm install

# 5. Copy environment file
cp .env.example .env

# 6. Generate application key
php artisan key:generate

# 7. Create database in MySQL
mysql -u root -p
> CREATE DATABASE techstore;
> EXIT;

# 8. Run migrations
php artisan migrate

# 9. Seed sample data
php artisan db:seed

# 10. Build frontend assets
npm run build

# 11. Start development server
php artisan serve
```

Server runs at: **http://127.0.0.1:8000**

---

## 📊 TechStore Application

### What is TechStore?
A **full-featured e-commerce management system** built with Laravel 13, featuring:

- 📦 **Product Management** - Categories, inventory tracking, stock alerts
- 👥 **Customer Management** - Customer profiles, purchase history, segmentation
- 📋 **Order Management** - Order processing, status tracking, order items
- ⭐ **Review System** - Customer reviews and ratings
- 📊 **Analytics Dashboard** - RFM analysis, revenue tracking, payment methods
- 💰 **Inventory Logging** - Track stock movements and changes
- 🎨 **Modern UI** - Tailwind CSS, responsive design, professional styling

### Technology Stack
```
Frontend:
- Blade Templates (Laravel)
- Tailwind CSS 4
- CSS3 & JavaScript
- Font Awesome Icons

Backend:
- Laravel 13.2
- PHP 8.4.8
- MySQL Database
- Eloquent ORM

DevOps:
- Composer (Package Manager)
- NPM (Asset Pipeline)
- Vite (Frontend Build)
- ArtiSan CLI
```

### Key Features

#### Dashboard
- Real-time statistics (Products, Customers, Orders, Revenue)
- Recent orders table with status tracking
- Low stock alerts
- Top products & customers

#### Analytics
- Revenue by category with percentages
- Customer distribution by city
- Payment method analysis
- RFM (Recency, Frequency, Monetary) analysis

#### Inventory Management
- Add/Edit/Delete products
- Category organization
- Stock tracking
- Low stock warnings

#### Customer Management
- Customer profiles
- Order history
- Purchase analytics
- Customer segmentation

---

## 📚 Learning Materials

### 1. MySQL Masterclass (02_Draft_PPT)
**Format:** Interactive Presentation (16 slides)

**Topics Covered:**
- ✅ Fundamental SQL (DDL, DML, DCL, DQL)
- ✅ Database Design & Normalization
- ✅ Advanced Queries (JOINs, Subqueries, CTEs)
- ✅ Performance Tuning & Indexing
- ✅ Real-World Analytics
- ✅ Security & Best Practices
- ✅ Query Optimization Techniques

**Duration:** 2 hours (with practical exercises)

### 2. Study Case: E-Commerce Platform (01_Study_Case)
**Complete real-world scenario with:**
- Business requirements analysis
- Database schema design
- SQL exercises with solutions
- Complete seed scripts
- Performance optimization tips

### 3. Quick Reference Cheat Sheet (02_Draft_PPT)
Essential SQL commands and patterns for quick lookup during development.

### 4. PHP-MySQL Integration Guide (03_BONUS_PHP_MySQL_Integration)
Best practices for connecting PHP applications to MySQL databases with proper:
- Connection handling
- Error management
- Security (prepared statements, escaping)
- ORM usage (Eloquent)

---

## 🗄️ Database Schema

### ERD Overview
```
Customers
    ├── Orders
    │   └── OrderItems
    │       └── Products
    │           ├── Categories
    │           └── Reviews
    └── Reviews
        └── Products

InventoryLog
    └── Products
```

### Main Tables
| Table | Purpose | Key Fields |
|-------|---------|-----------|
| **customers** | Store customer info | id, name, email, phone, address, city |
| **products** | Product catalog | id, name, category_id, price, stock, description |
| **categories** | Product categories | id, name, description |
| **orders** | Order records | id, customer_id, total_amount, status, payment_method |
| **order_items** | Order details | id, order_id, product_id, quantity, unit_price |
| **reviews** | Customer reviews | id, product_id, customer_id, rating, comment |
| **inventory_logs** | Stock history | id, product_id, change_type, quantity, reason |

---

## 🎨 UI/UX Features

### Design System
- **Color Scheme:** Blue gradient (#1e40af → #0c4a6e)
- **Typography:** System fonts for optimal performance
- **Spacing:** Consistent 4px grid
- **Components:** Modern cards with shadows and hover effects

### Pages
- 📊 Dashboard (Overview & KPIs)
- 📈 Analytics (Revenue, Customers, RFM Analysis)
- 📦 Products (List, Create, Edit, Delete)
- 👥 Customers (Profiles, History, Segmentation)
- 📋 Orders (Management, Tracking, Details)
- ⭐ Reviews (Ratings, Comments, Management)
- 🏷️ Categories (Inventory Classification)

---

## 🔧 API Routes

### Product Routes
```
GET  /products              → List products
POST /products              → Create product
GET  /products/{id}         → Show product
PUT  /products/{id}         → Update product
DELETE /products/{id}       → Delete product
```

### Similar routes for: `/categories`, `/customers`, `/orders`, `/reviews`

---

## 📊 Sample Data

The project includes **DatabaseSeeder** that populates:
- 20+ sample products across multiple categories
- 10+ customers with diverse data
- 50+ sample orders with order items
- 30+ customer reviews with ratings
- Complete inventory logs

Run: `php artisan db:seed`

---

## 🔐 Security Features

✅ **SQL Injection Prevention** - Eloquent ORM with parameterized queries
✅ **Authentication** - User authentication middleware
✅ **Authorization** - Role-based access control ready
✅ **CSRF Protection** - Laravel CSRF tokens
✅ **Data Validation** - Form request validation
✅ **Password Hashing** - Bcrypt for secure storage

---

## 📈 Performance Optimization

### Implemented
- ✅ Database indexes on foreign keys
- ✅ Query optimization patterns
- ✅ Eager loading (with relationships)
- ✅ Caching ready (Redis compatible)
- ✅ Asset minification (CSS/JS)

### Best Practices Applied
```php
// ✅ Use select instead of get all
Product::select('id', 'name', 'price')->get();

// ✅ Eager load relationships
Order::with('customer', 'items')->get();

// ✅ Use pagination
Product::paginate(15);

// ✅ Chunk large datasets
Product::chunk(100, function ($products) { ... });
```

---

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/ProductTest.php

# With coverage
php artisan test --coverage
```

### Test Files
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests

---

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Set proper database credentials
- [ ] Configure email service
- [ ] Enable HTTPS/SSL
- [ ] Set up database backups
- [ ] Configure proper logging

---

## 📖 Learning Path

### Beginner
1. Start with MySQL Masterclass slides
2. Review quick reference cheat sheet
3. Study the database schema in TechStore
4. Explore the Laravel models and relationships

### Intermediate
1. Complete study case exercises
2. Run analytics queries manually
3. Modify dashboard queries
4. Optimize slow queries using EXPLAIN

### Advanced
1. Extend database schema for new features
2. Implement custom queries
3. Add new features to TechStore
4. Optimize database performance

---

## 🎓 Educational Outcomes

After completing this project, you will understand:

✅ How to design relational databases
✅ Write complex SQL queries (JOINs, subqueries, CTEs)
✅ Optimize database queries for performance
✅ Build Laravel models with relationships
✅ Create RESTful APIs
✅ Design professional user interfaces
✅ Implement business logic in applications
✅ Handle real-world e-commerce scenarios
✅ Apply security best practices
✅ Deploy and maintain applications

---

## 📞 Contact & Support

**Project Lead:** Damar Galih Aji Pradana

**Links:**
- 📧 Email: engineeringedu29@gmail.com
- 🐙 GitHub: [@D4marp](https://github.com/D4marp)
- 📱 Repository: [engineering-edu-29-maret](https://github.com/D4marp/engineering-edu-29-maret)

---

## 📄 License

This project is open-source educational material. Feel free to use, modify, and learn from it.

---

## 🙏 Acknowledgments

- Laravel community for excellent documentation
- MySQL documentation team
- Educational best practices from industry experts
- All contributors and learners

---

## 📋 Checklist for Getting Started

- [ ] Clone repository
- [ ] Install PHP & Composer
- [ ] Install MySQL & create database
- [ ] Run `composer install`
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate`
- [ ] Seed data: `php artisan db:seed`
- [ ] Install Node dependencies: `npm install`
- [ ] Build assets: `npm run build`
- [ ] Start server: `php artisan serve`
- [ ] Visit http://127.0.0.1:8000

---

**Happy Learning! 🚀 Let's master PHP, MySQL, and Laravel together!**

*Last Updated: March 29, 2026*
*Version: 1.0.0*
