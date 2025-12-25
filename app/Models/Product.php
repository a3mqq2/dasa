<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'main_image',
        'has_variants',
        'is_active',
        'is_available',
        'min_order_quantity',
    ];

    protected $casts = [
        'has_variants' => 'boolean',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'min_order_quantity' => 'integer',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function stock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->stock()->sum('quantity');
    }
}
