<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\InventoryLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
        ]);

        $computers = Category::create([
            'name' => 'Computers',
            'description' => 'Laptops, desktops, and accessories',
        ]);

        $mobile = Category::create([
            'name' => 'Mobile Devices',
            'description' => 'Smartphones and tablets',
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'description' => 'Phone and computer accessories',
        ]);

        // Products
        $laptop = Product::create([
            'category_id' => $computers->id,
            'name' => 'ASUS ROG Gaming Laptop',
            'description' => 'High-performance gaming laptop with RTX 4090',
            'price' => 35000000,
            'cost' => 25000000,
            'stock' => 8,
            'sku' => 'LAPTOP-ASUS-ROG',
            'weight' => 2.8,
            'status' => 'active',
        ]);

        $monitor = Product::create([
            'category_id' => $electronics->id,
            'name' => 'LG 27" 4K Monitor',
            'description' => 'Professional grade 4K monitor with HDR support',
            'price' => 5000000,
            'cost' => 3500000,
            'stock' => 15,
            'sku' => 'MON-LG-27-4K',
            'weight' => 6.5,
            'status' => 'active',
        ]);

        $keyboard = Product::create([
            'category_id' => $accessories->id,
            'name' => 'Mechanical Gaming Keyboard RGB',
            'description' => 'RGB mechanical keyboard with switches',
            'price' => 1500000,
            'cost' => 800000,
            'stock' => 45,
            'sku' => 'KBD-RGB-MECH',
            'weight' => 1.2,
            'status' => 'active',
        ]);

        // Customers
        $customer1 = Customer::create([
            'name' => 'Ahmad Suryanto',
            'email' => 'ahmad.suryanto@email.com',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123',
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'country' => 'Indonesia',
            'status' => 'active',
        ]);

        $customer2 = Customer::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@email.com',
            'phone' => '082345678901',
            'address' => 'Jl. Sudirman No. 456',
            'city' => 'Surabaya',
            'postal_code' => '60245',
            'country' => 'Indonesia',
            'status' => 'active',
        ]);

        // Orders
        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'order_date' => now()->subDays(15),
            'total_amount' => 36500000,
            'tax_amount' => 3650000,
            'shipping_cost' => 100000,
            'discount_amount' => 0,
            'status' => 'delivered',
            'payment_method' => 'Credit Card',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $laptop->id,
            'quantity' => 1,
            'unit_price' => 35000000,
            'discount_percent' => 0,
        ]);

        InventoryLog::create([
            'product_id' => $laptop->id,
            'quantity_change' => -1,
            'transaction_type' => 'sale',
            'reference_id' => 'ORDER-' . $order1->id,
            'note' => 'Order transaction',
        ]);

        // Reviews
        Review::create([
            'product_id' => $laptop->id,
            'customer_id' => $customer1->id,
            'rating' => 5,
            'comment' => 'Excellent laptop! Very fast and reliable.',
            'helpful_count' => 12,
        ]);
    }
}
