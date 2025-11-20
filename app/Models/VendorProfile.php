<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends BaseModel
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

    // Relationships - FIXED with correct foreign keys
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id'); // ADDED foreign key
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'vendor_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'vendor_id');
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

    // Accessors for stats
    public function getTotalProductsAttribute()
    {
        return $this->products()->count();
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orders()->where('order_status', 'delivered')->sum('grand_total');
    }

    public function getPendingOrdersAttribute()
    {
        return $this->orders()->where('order_status', 'pending')->count();
    }
}