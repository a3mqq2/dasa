<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'delivery_address',
        'delivery_type',
        'delivery_fee',
        'order_type',
        'delivery_date',
        'payment_method',
        'deposit_amount',
        'subtotal',
        'total',
        'status',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
