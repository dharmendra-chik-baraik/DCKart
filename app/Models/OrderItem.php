<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id', 'product_id', 'variant_value_id', 'vendor_id',
        'quantity', 'price', 'total'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
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

    public function variantValue()
    {
        return $this->belongsTo(ProductVariantValue::class, 'variant_value_id');
    }

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
    }
}