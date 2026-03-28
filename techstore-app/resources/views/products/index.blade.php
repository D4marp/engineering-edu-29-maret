@extends('layouts.app')

@section('page-title', 'Products')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Products</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-box"></i> All Products
        </span>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Add Product
        </a>
    </div>
    
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('products.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" placeholder="Search product..." class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        @if ($products->count())
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><code>{{ $product->sku }}</code></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
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
                {{ $products->links('pagination::bootstrap-5') }}
            </nav>
        @else
            <p class="text-muted">No products found.</p>
        @endif
    </div>
</div>
@endsection
