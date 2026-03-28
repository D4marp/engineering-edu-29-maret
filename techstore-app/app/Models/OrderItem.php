<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'discount_percent'];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function getDiscountAmountAttribute()
    {
        return ($this->subtotal * $this->discount_percent) / 100;
    }

    public function getTotalAttribute()
    {
        return $this->subtotal - $this->discount_amount;
    }
}
