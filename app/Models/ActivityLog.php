<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by module.
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Get the icon for the activity module.
     */
    public function getModuleIconAttribute(): string
    {
        $icons = [
            'user' => 'fas fa-users',
            'product' => 'fas fa-box',
            'category' => 'fas fa-tags',
            'order' => 'fas fa-shopping-cart',
            'vendor' => 'fas fa-store',
            'payment' => 'fas fa-credit-card',
            'coupon' => 'fas fa-ticket-alt',
            'page' => 'fas fa-file',
            'settings' => 'fas fa-cog',
            'inventory' => 'fas fa-warehouse',
            'review' => 'fas fa-star',
            'ticket' => 'fas fa-headset',
            'payout' => 'fas fa-money-bill-wave',
            'auth' => 'fas fa-shield-alt',
        ];

        return $icons[strtolower($this->module)] ?? 'fas fa-history';
    }

    /**
     * Get the badge color for the action.
     */
    public function getActionBadgeAttribute(): string
    {
        $colors = [
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'viewed' => 'info',
            'login' => 'success',
            'logout' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'warning',
            'exported' => 'info',
        ];

        return $colors[strtolower($this->action)] ?? 'secondary';
    }

    /**
     * Get the formatted created at time.
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M j, Y g:i A');
    }

    /**
     * Get the time ago format.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}