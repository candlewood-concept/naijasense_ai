<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'name',
        'age',
        'location',
        'budget_style',
        'personality_type',
        'preferences',
        'context',
    ];

    protected $casts = [
        'preferences' => 'array',
        'context' => 'array',
    ];
}