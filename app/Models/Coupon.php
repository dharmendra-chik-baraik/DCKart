<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'min_order_value',
        'max_discount', 'start_date', 'end_date', 'usage_limit', 'used_count', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    // Relationships
    public function couponUsages()
    {
        return $this->hasMany(CouponUser::class);
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            CouponUser::class,
            'coupon_id',
            'id',
            'id',
            'user_id'
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeValidForOrder($query, $orderAmount)
    {
        return $query->active()
                    ->where('min_order_value', '<=', $orderAmount)
                    ->where(function($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('used_count < usage_limit');
                    });
    }

    // Methods
    public function calculateDiscount($orderAmount)
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                return $this->max_discount;
            }
            return $discount;
        }
        
        return $this->discount_value;
    }

    public function isExpired()
    {
        return now()->gt($this->end_date);
    }

    public function hasReachedUsageLimit()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }
}