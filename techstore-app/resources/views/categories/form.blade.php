@extends('layouts.app')

@section('page-title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="card">
    <div class="card-header">{{ isset($category) ? 'Edit' : 'Add' }} Category</div>
    <div class="card-body">
        <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST">
            @csrf
            @if (isset($category)) @method('PUT') @endif
            
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name ?? '') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
