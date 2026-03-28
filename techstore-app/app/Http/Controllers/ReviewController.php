<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'customer')->latest()->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load('product', 'customer');
        return view('reviews.show', compact('review'));
    }

    public function create()
    {
        $products = Product::all();
        return view('reviews.form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create($validated);

        return redirect()->back()->with('success', 'Review created successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
