<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'city', 'postal_code', 'country', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeSearch($query, $keywords)
    {
        return $query->where('name', 'like', "%{$keywords}%")
                     ->orWhere('email', 'like', "%{$keywords}%")
                     ->orWhere('phone', 'like', "%{$keywords}%");
    }

    // Accessors
    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()->sum('total_amount') ?? 0;
    }

    public function getAverageOrderValueAttribute()
    {
        $totalOrders = $this->total_orders;
        if ($totalOrders == 0) return 0;
        return $this->total_spent / $totalOrders;
    }

    public function getSegmentAttribute()
    {
        $totalSpent = $this->total_spent;
        
        if ($totalSpent >= 10000000) return 'Premium';
        if ($totalSpent >= 5000000) return 'Gold';
        if ($totalSpent >= 1000000) return 'Silver';
        return 'Regular';
    }
}
