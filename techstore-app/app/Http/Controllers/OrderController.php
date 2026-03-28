<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('customer', 'orderItems.product');
        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('status', 'active')->get();

        return view('orders.form', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for ' . $product->name);
                }

                $item_subtotal = $item['quantity'] * $item['unit_price'];
                if (isset($item['discount_percent'])) {
                    $item_subtotal -= ($item_subtotal * $item['discount_percent']) / 100;
                }
                $subtotal += $item_subtotal;

                // Update inventory
                $product->update(['stock' => $product->stock - $item['quantity']]);
            }

            $total_amount = $subtotal + ($validated['tax_amount'] ?? 0) + ($validated['shipping_cost'] ?? 0) - ($validated['discount_amount'] ?? 0);

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'total_amount' => $total_amount,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                ]);

                // Log inventory
                InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'quantity_change' => -$item['quantity'],
                    'transaction_type' => 'sale',
                    'reference_id' => 'ORDER-' . $order->id,
                    'note' => 'Order transaction',
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Can only delete pending orders.');
        }

        DB::beginTransaction();
        try {
            // Restore inventory
            foreach ($order->orderItems as $item) {
                $product = Product::findOrFail($item->product_id);
                $product->update(['stock' => $product->stock + $item->quantity]);

                InventoryLog::create([
                    'product_id' => $item->product_id,
                    'quantity_change' => $item->quantity,
                    'transaction_type' => 'return',
                    'reference_id' => 'ORDER-' . $order->id . '-CANCELLED',
                    'note' => 'Order cancelled',
                ]);
            }

            $order->delete();
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
