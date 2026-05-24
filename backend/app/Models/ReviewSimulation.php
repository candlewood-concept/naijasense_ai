<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewSimulation extends Model
{
    protected $fillable = [
        'persona_id',
        'item_id',
        'rating',
        'review',
        'confidence',
        'behavior_tags',
        'raw_output',
    ];

    protected $casts = [
        'behavior_tags' => 'array',
        'raw_output' => 'array',
    ];
}