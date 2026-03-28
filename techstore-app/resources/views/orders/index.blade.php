@extends('layouts.app')

@section('page-title', 'Orders')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Orders</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-receipt"></i> All Orders
        </span>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Create Order
        </a>
    </div>
    
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('orders.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" placeholder="Search by customer..." class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        @if ($orders->count())
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->customer->name }}</td>
                            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($order->payment_method ?? '-') }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="pagination">
                {{ $orders->links('pagination::bootstrap-5') }}
            </nav>
        @else
            <p class="text-muted">No orders found.</p>
        @endif
    </div>
</div>
@endsection
