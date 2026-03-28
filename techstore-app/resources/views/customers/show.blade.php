@extends('layouts.app')

@section('page-title', isset($customer) ? $customer->name : 'Customer Details')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
        <li class="breadcrumb-item active">{{ isset($customer) ? $customer->name : 'Details' }}</li>
    </ol>
</nav>
@endsection

@section('content')
@if (isset($customer))
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-person"></i> Customer Information</span>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td><strong>{{ $customer->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $customer->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $customer->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $customer->city ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($customer->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt"></i> Recent Orders
            </div>
            <div class="card-body">
                @if ($orders->count())
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links('pagination::bootstrap-5') }}
                @else
                    <p class="text-muted">No orders yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Statistics
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Total Orders:</strong> {{ $customer->total_orders }}
                </p>
                <p class="mb-2">
                    <strong>Total Spent:</strong> Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                </p>
                <p class="mb-2">
                    <strong>Avg Order Value:</strong> Rp {{ number_format($customer->average_order_value, 0, ',', '.') }}
                </p>
                <p>
                    <strong>Segment:</strong> 
                    <span class="badge bg-primary">{{ $customer->segment }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">Customer not found.</div>
@endif
@endsection
