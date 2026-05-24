<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRun extends Model
{
    protected $fillable = [
        'agent_name',
        'prompt',
        'input_payload',
        'output_payload',
        'latency_ms',
        'success',
        'error_message',
    ];

    protected $casts = [
        'input_payload' => 'array',
        'output_payload' => 'array',
        'success' => 'boolean',
    ];
}