<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategory extends Pivot
{
    protected $table = 'product_categories';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'product_id' => 'string',
        'category_id' => 'string',
    ];
}