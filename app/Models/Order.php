<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_number', 'user_id', 'vendor_id', 'total_amount', 'discount',
        'shipping_charge', 'tax', 'grand_total', 'payment_status', 'order_status',
        'shipping_method', 'payment_method', 'transaction_id'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorProfile::class);
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
    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }
}