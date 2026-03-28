@extends('layouts.app')

@section('page-title', 'Analytics & Reports')

@section('page-subtitle', 'Monitor your business performance and customer insights')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Revenue by Category -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-chart-pie text-lg"></i>
            <span>Revenue by Category</span>
        </div>
        <div class="p-6">
            @if ($revenue_by_category->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">Category</th>
                                <th class="pb-3 px-4 text-right">Revenue</th>
                                <th class="pb-3 px-4 text-right">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $total_revenue = $revenue_by_category->sum('revenue');
                            @endphp
                            @foreach ($revenue_by_category as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">{{ $item->category->name ?? '-' }}</td>
                                    <td class="py-3 px-4 text-right font-600">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-blue-600 font-600">{{ round(($item->revenue / $total_revenue) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Customers by City -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-lg"></i>
            <span>Customers by City</span>
        </div>
        <div class="p-6">
            @if ($customer_by_city->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">City</th>
                                <th class="pb-3 px-4 text-right">Count</th>
                                <th class="pb-3 px-4 text-right">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $total_customers = $customer_by_city->sum('count');
                            @endphp
                            @foreach ($customer_by_city as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">{{ $item->city ?? 'Unknown' }}</td>
                                    <td class="py-3 px-4 text-right font-600">{{ $item->count }}</td>
                                    <td class="py-3 px-4 text-right text-green-600 font-600">{{ round(($item->count / $total_customers) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No data available</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Payment Methods -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-credit-card text-lg"></i>
            <span>Payment Methods</span>
        </div>
        <div class="p-6">
            @if ($payment_methods->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">Method</th>
                                <th class="pb-3 px-4 text-right">Orders</th>
                                <th class="pb-3 px-4 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($payment_methods as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">
                                        <span class="inline-flex items-center gap-2">
                                            @if ($item->payment_method === 'credit_card')
                                                <i class="fas fa-credit-card text-blue-600"></i>
                                            @elseif ($item->payment_method === 'bank_transfer')
                                                <i class="fas fa-university text-green-600"></i>
                                            @else
                                                <i class="fas fa-money-bill text-gray-500"></i>
                                            @endif
                                            {{ ucwords(str_replace('_', ' ', $item->payment_method ?? 'N/A')) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">{{ $item->count }}</td>
                                    <td class="py-3 px-4 text-right font-600">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No data available</p>
                </div>
            @endif
        </div>
        </div>
    </div>

        </div>
    </div>

    <!-- RFM Analysis -->
    <div class="card">
        <div class="card-header flex items-center gap-2">
            <i class="fas fa-users text-lg"></i>
            <span>RFM Analysis</span>
        </div>
        <div class="p-6">
            @if (isset($rfm_analysis) && collect($rfm_analysis)->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-gray-200">
                            <tr class="text-left text-gray-600 font-600 text-xs uppercase tracking-wide">
                                <th class="pb-3 px-4">Customer</th>
                                <th class="pb-3 px-4 text-right">Recency</th>
                                <th class="pb-3 px-4 text-right">Frequency</th>
                                <th class="pb-3 px-4 text-right">Monetary</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($rfm_analysis as $customer)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 font-500">{{ $customer['name'] }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-600 {{ $customer['recency'] < 30 ? 'bg-green-100 text-green-800' : ($customer['recency'] < 90 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $customer['recency'] }}d
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-600">{{ $customer['frequency'] }}</td>
                                    <td class="py-3 px-4 text-right font-600">Rp {{ number_format($customer['monetary'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-600 text-blue-900 mb-2">RFM Analysis Explanation</h4>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li><span class="font-600">Recency:</span> Days since last order (lower is better)</li>
                            <li><span class="font-600">Frequency:</span> Total number of orders</li>
                            <li><span class="font-600">Monetary:</span> Total amount spent by customer</li>
                        </ul>
                        <p class="text-xs text-blue-600 mt-3 italic">RFM segments help identify your most valuable customers</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
