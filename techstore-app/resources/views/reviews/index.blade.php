@extends('layouts.app')

@section('page-title', 'Customer Reviews')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Reviews</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-chat"></i> Customer Reviews</span>
        <a href="{{ route('reviews.create') }}" class="btn btn-sm btn-primary">Add Review</a>
    </div>
    <div class="card-body">
        @if ($reviews->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td><a href="{{ route('products.show', $review->product) }}">{{ $review->product->name }}</a></td>
                            <td>{{ $review->customer->name }}</td>
                            <td>
                                <span class="badge {{ $review->rating >= 4 ? 'bg-success' : ($review->rating >= 3 ? 'bg-warning' : 'bg-danger') }}">
                                    ⭐ {{ $review->rating }}/5
                                </span>
                            </td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $reviews->links('pagination::bootstrap-5') }}
        @else
            <p class="text-muted">No reviews yet.</p>
        @endif
    </div>
</div>
@endsection
