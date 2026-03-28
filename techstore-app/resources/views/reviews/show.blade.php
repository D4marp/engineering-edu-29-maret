@extends('layouts.app')

@section('page-title', 'Review Details')

@section('content')
@if (isset($review))
<div class="card">
    <div class="card-header">
        <i class="bi bi-chat"></i> Review for {{ $review->product->name }}
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th style="width: 200px;">Product</th>
                <td><a href="{{ route('products.show', $review->product) }}">{{ $review->product->name }}</a></td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $review->customer->name }}</td>
            </tr>
            <tr>
                <th>Rating</th>
                <td>
                    <span class="badge {{ $review->rating >= 4 ? 'bg-success' : ($review->rating >= 3 ? 'bg-warning' : 'bg-danger') }}">
                        ⭐ {{ $review->rating }}/5
                    </span>
                </td>
            </tr>
            <tr>
                <th>Comment</th>
                <td>{{ $review->comment }}</td>
            </tr>
            <tr>
                <th>Helpful Count</th>
                <td>{{ $review->helpful_count }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $review->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>

        <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this review?')">Delete Review</button>
        </form>
        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">Back to Reviews</a>
    </div>
</div>
@else
<div class="alert alert-warning">Review not found.</div>
@endif
@endsection
