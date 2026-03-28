@extends('layouts.app')

@section('page-title', 'Customers')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Customers</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-person"></i> All Customers
        </span>
        <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> Add Customer
        </a>
    </div>
    
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('customers.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" placeholder="Search customer..." class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="city" class="form-select">
                        <option value="">All Cities</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        @if ($customers->count())
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>{{ $customer->city ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;">
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
                {{ $customers->links('pagination::bootstrap-5') }}
            </nav>
        @else
            <p class="text-muted">No customers found.</p>
        @endif
    </div>
</div>
@endsection
