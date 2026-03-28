@extends('layouts.app')

@section('page-title', 'Categories')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Categories</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-tags"></i> All Categories</span>
        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary">Add Category</a>
    </div>
    <div class="card-body">
        @if ($categories->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->description }}</td>
                            <td><span class="badge bg-info">{{ $category->products_count }}</span></td>
                            <td>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links('pagination::bootstrap-5') }}
        @else
            <p class="text-muted">No categories found.</p>
        @endif
    </div>
</div>
@endsection
