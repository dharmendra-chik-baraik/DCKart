<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends BaseModel
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_number', 'user_id', 'vendor_id', 'total_amount', 'discount',
        'shipping_charge', 'tax', 'grand_total', 'payment_status', 'order_status',
        'shipping_method', 'payment_method', 'transaction_id', 'shipping_address_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    // Accessors for compatibility with blade template
    public function getSubtotalAttribute()
    {
        return $this->total_amount;
    }

    public function getShippingCostAttribute()
    {
        return $this->shipping_charge;
    }

    public function getTaxAmountAttribute()
    {
        return $this->tax;
    }

    public function getDiscountAmountAttribute()
    {
        return $this->discount;
    }

    // Get default shipping address from user
    public function getDefaultShippingAddressAttribute()
    {
        return $this->user->addresses()->where('is_default', true)->first();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }

    // Methods
    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->order_status] ?? 'secondary';
    }
}