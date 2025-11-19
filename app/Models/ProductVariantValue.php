<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'variant_id', 'value', 'price_adjustment', 'stock'
    ];

    // Relationships
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_value_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'variant_value_id');
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        $productPrice = $this->variant->product->final_price;
        return $productPrice + $this->price_adjustment;
    }
}