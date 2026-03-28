# TechStore - E-Commerce Management System

**Complete Laravel Project Implementation of MySQL Masterclass Study Case**

This is a fully functional Laravel web application that directly implements the TechStore e-commerce system from the MySQL Masterclass study case.

---

## 🎯 WHAT'S THIS?

A **real, working Laravel project** (NOT code snippets) that demonstrates:
- Proper MVC architecture
- Database design with 7 normalized tables  
- Eloquent ORM with relationships
- CRUD operations with validation
- Transaction handling
- Bootstrap UI
- Sample data with seeders

---

## ⚡ QUICK START

```bash
# 1. Navigate to project
cd /Users/HCMPublic/Documents/Damar/PHP/techstore-app

# 2. Install dependencies
composer install

# 3. Create database
mysql -u root -p -e "CREATE DATABASE techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Configure .env
# Update DB_PASSWORD if needed

# 5. Run migrations & seed
php artisan migrate
php artisan db:seed

# 6. Start server
php artisan serve

# 7. Open browser
# Visit: http://127.0.0.1:8000
```

✅ **Application running in 5 minutes!**

---

## 📊 PROJECT STRUCTURE

```
app/
  ├── Models/              (7 Eloquent Models)
  └── Http/Controllers/    (6 Controllers)

database/
  ├── migrations/          (7 Table Schemas)
  └── seeders/             (Sample Data)

resources/views/            (Blade Templates)
  ├── layouts/
  ├── dashboard/
  ├── products/
  ├── customers/
  ├── orders/
  ├── categories/
  └── reviews/

routes/web.php              (All Routes)
SETUP_GUIDE.md             (Detailed Setup)
```

---

## 🗄️ DATABASE (7 Tables)

- `categories` - Product categories
- `products` - Inventory with pricing
- `customers` - Customer information
- `orders` - Order headers
- `order_items` - Order line items
- `reviews` - Customer reviews & ratings
- `inventory_logs` - Stock transaction history

**All with proper relationships, indexes, and constraints.**

---

## 🎨 FEATURES

✅ Dashboard with KPIs and analytics  
✅ Product management (CRUD + filtering)  
✅ Customer management + segmentation  
✅ Order processing with transactions  
✅ Review system with ratings  
✅ Inventory tracking  
✅ Advanced analytics & reports  
✅ Responsive Bootstrap 5 UI  
✅ Pagination & search  
✅ Form validation  

---

## 🛠 TECH STACK

- Laravel 10+
- PHP 8.1+
- MySQL 8.0+
- Eloquent ORM
- Blade Templates
- Bootstrap 5

---

## 🚀 QUICK COMMANDS

```bash
php artisan serve              # Start server
php artisan migrate            # Run migrations
php artisan db:seed            # Seed data
php artisan migrate:fresh      # Reset DB
php artisan route:list         # View routes
```

---

## 📖 DETAILED SETUP

See **SETUP_GUIDE.md** for:
- Step-by-step instructions
- Troubleshooting
- Useful commands
- Complete API reference

---

## 📚 LEARN BY EXPLORING

1. **Models** (`app/Models/`) - Relations & scopes
2. **Controllers** (`app/Http/Controllers/`) - Business logic
3. **Views** (`resources/views/`) - Blade templating
4. **Routes** (`routes/web.php`) - URL mappings
5. **Migrations** (`database/migrations/`) - Schema design

---

## 📁 FILES

- `.env` - Database config
- `SETUP_GUIDE.md` - Complete setup instructions
- `/app/Models/` - 7 Models
- `/app/Http/Controllers/` - 6 Controllers
- `/resources/views/` - Blade templates
- `/routes/web.php` - Routes

---

**For complete setup instructions, see SETUP_GUIDE.md**

*TechStore - Laravel Implementation of MySQL Masterclass*

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
