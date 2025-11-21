<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message',
        'attachment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationship with the ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // Relationship with the user who sent the message
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Accessor for attachment URL
    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return asset('storage/' . $this->attachment);
        }
        return null;
    }

    // Accessor for attachment name
    public function getAttachmentNameAttribute()
    {
        if ($this->attachment) {
            return basename($this->attachment);
        }
        return null;
    }
}