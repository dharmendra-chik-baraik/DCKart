<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'product_id', 'variant_value_id', 'quantity'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantValue()
    {
        return $this->belongsTo(ProductVariantValue::class, 'variant_value_id');
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        $price = $this->variantValue ? $this->variantValue->final_price : $this->product->final_price;
        return $price * $this->quantity;
    }
}