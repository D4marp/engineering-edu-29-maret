<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

// Products
Route::resource('products', ProductController::class);

// Categories
Route::resource('categories', CategoryController::class);

// Customers
Route::resource('customers', CustomerController::class);

// Orders
Route::resource('orders', OrderController::class);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Reviews
Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'create', 'store', 'destroy']);

