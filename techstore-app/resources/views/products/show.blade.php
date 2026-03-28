@extends('layouts.app')

@section('page-title', isset($product) ? $product->name : 'Product Details')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">{{ isset($product) ? $product->name : 'Details' }}</li>
    </ol>
</nav>
@endsection

@section('content')
@if (isset($product))
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-box"></i> Product Information</span>
                <div>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td><strong>{{ $product->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <td><code>{{ $product->sku }}</code></td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td><strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Cost</th>
                        <td>Rp {{ number_format($product->cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Stock</th>
                        <td><span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">{{ $product->stock }} items</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($product->status) }}</span></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $product->description ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-star"></i> Reviews
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Rating:</strong> {{ round($product->average_rating, 1) }}/5.0
                </p>
                <p>
                    <strong>Total Reviews:</strong> {{ $product->total_reviews }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-percent"></i> Performance
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Total Sold:</strong> {{ $product->total_sold }}
                </p>
                <p>
                    <strong>Profit Margin:</strong> {{ round($product->profit_margin, 2) }}%
                </p>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">Product not found.</div>
@endif
@endsection
