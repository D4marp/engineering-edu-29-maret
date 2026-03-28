@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Statistics Cards -->
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Total Products</p>
                <div class="stat-value">{{ $stats['total_products'] }}</div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Total Customers</p>
                <div class="stat-value">{{ $stats['total_customers'] }}</div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Total Orders</p>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 text-xl">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="stat-label">Total Revenue</p>
                <div class="stat-value" style="font-size: 24px;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 text-xl">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Orders -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header flex items-center gap-2">
                <i class="fas fa-receipt text-lg"></i>
                <span>Recent Orders</span>
            </div>
            <div class="p-6">
                @if ($recent_orders->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b border-gray-200">
                                <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                    <th class="pb-3 px-4">Order ID</th>
                                    <th class="pb-3 px-4">Customer</th>
                                    <th class="pb-3 px-4">Total</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4">Date</th>
                                    <th class="pb-3 px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($recent_orders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4"><strong class="text-blue-600">#{{ $order->id }}</strong></td>
                                        <td class="py-3 px-4">{{ $order->customer->name }}</td>
                                        <td class="py-3 px-4"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-600 
                                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-600 text-xs">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <i class="fas fa-inbox text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500">No orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div>
        <div class="card border-l-4" style="border-left-color: #ef4444;">
            <div class="card-header bg-red-600">
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <span>Low Stock Alert</span>
            </div>
            <div class="p-6">
                @if ($low_stock->count())
                    <div class="space-y-3">
                        @foreach ($low_stock->take(5) as $product)
                            <a href="{{ route('products.show', $product) }}" class="block p-3 hover:bg-gray-50 rounded-lg transition border border-gray-200">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-600 text-gray-900 truncate text-sm">{{ $product->name }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-800 font-600 text-xs flex-shrink-0 ml-2">{{ $product->stock }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center">
                        <i class="fas fa-check-circle text-3xl text-green-300 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">All products have sufficient stock.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Top Products -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-star text-lg"></i>
            <span>Top Products</span>
        </div>
        <div class="p-6">
            @if ($top_products->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">Product</th>
                                <th class="pb-3 px-4 text-right">Sold</th>
                                <th class="pb-3 px-4 text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($top_products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">{{ $product->name }}</td>
                                    <td class="py-3 px-4 text-right font-600">{{ $product->order_items_count }}</td>
                                    <td class="py-3 px-4 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <i class="fas fa-chart-bar text-3xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No sales data yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Customers -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-crown text-lg"></i>
            <span>Top Customers</span>
        </div>
        <div class="p-6">
            @if (count($top_customers))
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">Customer</th>
                                <th class="pb-3 px-4 text-right">Orders</th>
                                <th class="pb-3 px-4 text-right">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($top_customers as $customer)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">{{ $customer['name'] }}</td>
                                    <td class="py-3 px-4 text-right">{{ $customer['total_orders'] }}</td>
                                    <td class="py-3 px-4 text-right font-600">Rp {{ number_format($customer['total_spent'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <i class="fas fa-users text-3xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No customer data yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
