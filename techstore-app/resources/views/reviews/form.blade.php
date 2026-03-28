@extends('layouts.app')

@section('page-title', 'Add Review')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-chat"></i> Add New Review
    </div>
    <div class="card-body">
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product *</label>
                    <select class="form-select @error('product_id') is-invalid @enderror" name="product_id" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rating (1-5) *</label>
                    <select class="form-select @error('rating') is-invalid @enderror" name="rating" required>
                        <option value="">Select Rating</option>
                        <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Excellent</option>
                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ Good</option>
                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ Average</option>
                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ Poor</option>
                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ Very Poor</option>
                    </select>
                    @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Comment</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" name="comment" rows="4" placeholder="Write your review...">{{ old('comment') }}</textarea>
                    @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit Review</button>
            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
