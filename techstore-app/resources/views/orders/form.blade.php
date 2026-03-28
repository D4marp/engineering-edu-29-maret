@extends('layouts.app')

@section('page-title', 'Create Order')

@section('content')
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> <strong>Create Order from Dashboard</strong><br>
    This page is a placeholder. In a full implementation, you would have an order builder interface here with product selection, quantity, discounts, etc.
</div>

<div class="card">
    <div class="card-header">Create New Order</div>
    <div class="card-body">
        <p class="text-muted">
            For now, you can:
        </p>
        <ul>
            <li><a href="{{ route('orders.index') }}">View all orders</a> (with sample data from seeder)</li>
            <li><a href="{{ route('products.index') }}">Browse products</a> to understand inventory</li>
            <li><a href="{{ route('customers.index') }}">View customers</a> for order references</li>
        </ul>
        
        <p class="mt-3 text-muted">
            To manually create orders, you would typically use the API or extend this form with product selection fields.
        </p>

        <a href="{{ route('orders.index') }}" class="btn btn-primary mt-3">Back to Orders</a>
    </div>
</div>
@endsection
