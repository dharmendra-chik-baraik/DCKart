<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'price', 'delivery_time', 'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}