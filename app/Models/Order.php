<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'business_id',
        'user_id',
        'customer_id',
        'status',
        'payment_status',
        'payment_method',
        'shipping_method',
        'delivery_type',
        'delivery_address',
        'pickup_time',
        'notes',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'total_amount',
        'billing_address',
        'shipping_address',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
