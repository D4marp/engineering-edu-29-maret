@extends('layouts.app')

@section('page-title', isset($customer) ? 'Edit Customer' : 'Add Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil"></i> {{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}
    </div>
    
    <div class="card-body">
        <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" method="POST">
            @csrf
            @if (isset($customer))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $customer->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $customer->email ?? '') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $customer->phone ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city" value="{{ old('city', $customer->city ?? '') }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
