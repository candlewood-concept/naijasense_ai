<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationLog extends Model
{
    protected $fillable = [
        'persona_id',
        'input_context',
        'recommendations',
        'reasoning',
        'confidence',
    ];

    protected $casts = [
        'input_context' => 'array',
        'recommendations' => 'array',
        'confidence' => 'float',
    ];
}