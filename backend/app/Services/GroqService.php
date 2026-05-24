<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    public function chat(array $messages, float $temperature = 0.7): array
    {
        $response = Http::withToken(config('services.groq.key'))
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => config('services.groq.model'),
                'messages' => $messages,
                'temperature' => $temperature,
                'response_format' => [
                    'type' => 'json_object'
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception($response->body());
        }

        $content = $response->json('choices.0.message.content');

        return json_decode($content, true) ?? [
            'raw' => $content
        ];
    }
}