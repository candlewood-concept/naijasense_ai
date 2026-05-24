<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price_level',
        'metadata',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}