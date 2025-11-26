<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Helper method to get notification title
    public function getTitleAttribute(): string
    {
        return $this->data['title'] ?? 'Notification';
    }

    // Helper method to get notification message
    public function getMessageAttribute(): string
    {
        return $this->data['message'] ?? '';
    }

    // Helper method to get action URL
    public function getActionUrlAttribute(): ?string
    {
        return $this->data['action_url'] ?? null;
    }

    // Check if notification is read
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    // Mark as read
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    // Mark as unread
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }
}