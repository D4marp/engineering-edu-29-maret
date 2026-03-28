<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total_amount') ?? 0,
            'active_products' => Product::where('status', 'active')->count(),
            'active_customers' => Customer::where('status', 'active')->count(),
        ];

        $recent_orders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $top_products = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        $low_stock = Product::where('stock', '<=', 10)
            ->where('status', 'active')
            ->get();

        $top_customers = Customer::with('orders')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'total_spent' => $customer->orders()->sum('total_amount'),
                    'total_orders' => $customer->orders()->count(),
                ];
            })
            ->sortByDesc('total_spent')
            ->take(5)
            ->values();

        $order_trend = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recent_orders',
            'top_products',
            'low_stock',
            'top_customers',
            'order_trend'
        ));
    }

    public function analytics()
    {
        $revenue_by_category = Product::select('category_id')
            ->with('category')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('category_id')
            ->selectRaw('SUM(order_items.quantity * order_items.unit_price) as revenue')
            ->get();

        $customer_by_city = Customer::groupBy('city')
            ->selectRaw('city, COUNT(*) as count')
            ->orderByDesc('count')
            ->get();

        $payment_methods = Order::groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as revenue')
            ->get();

        $rfm_analysis = Customer::select('id', 'name', 'email')
            ->with('orders')
            ->get()
            ->map(function ($customer) {
                $orders = $customer->orders;
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'recency' => $orders->count() > 0 ? $orders->sortByDesc('created_at')->first()->created_at->diffInDays(now()) : 999,
                    'frequency' => $orders->count(),
                    'monetary' => $orders->sum('total_amount'),
                ];
            });

        return view('dashboard.analytics', compact(
            'revenue_by_category',
            'customer_by_city',
            'payment_methods',
            'rfm_analysis'
        ));
    }
}
