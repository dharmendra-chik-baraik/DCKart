<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends BaseModel
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description', 'status'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}