@extends('layouts.app')

@section('page-title', isset($order) ? 'Order #' . $order->id : 'Order Details')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
        <li class="breadcrumb-item active">{{ isset($order) ? '#' . $order->id : 'Details' }}</li>
    </ol>
</nav>
@endsection

@section('content')
@if (isset($order))
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-receipt"></i> Order #{{ $order->id }}</span>
                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>{{ $order->customer->name }}</strong></p>
                        <p>{{ $order->customer->email }}</p>
                        <p>{{ $order->customer->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p><strong>Order Date:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
                        <p><strong>Payment Method:</strong> {{ $order->payment_method ?? '-' }}</p>
                    </div>
                </div>

                <h6 class="mb-3">Order Items</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td><a href="{{ route('products.show', $item->product) }}">{{ $item->product->name }}</a></td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calculator"></i> Order Summary
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tax:</th>
                        <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Discount:</th>
                        <td class="text-end">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 2px solid #ddd;">
                        <th style="padding-top: 10px;">Total:</th>
                        <td class="text-end" style="padding-top: 10px;"><strong class="text-success" style="font-size: 18px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                @if ($order->status !== 'delivered' && $order->status !== 'cancelled')
                    <form action="{{ route('orders.update-status', $order) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Update Status</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">Order not found.</div>
@endif
@endsection
