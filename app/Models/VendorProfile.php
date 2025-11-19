<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'shop_name', 'shop_slug', 'description', 'logo', 'cover_image',
        'verified_at', 'gst_number', 'pan_number', 'address', 'city', 'state',
        'country', 'pincode', 'bank_account_number', 'bank_name', 'branch',
        'ifsc_code', 'status'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }
}