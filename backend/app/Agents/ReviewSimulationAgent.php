<?php

namespace App\Agents;

use App\Models\AgentRun;
use App\Services\GroqService;
use Illuminate\Support\Arr;

class ReviewSimulationAgent
{
    protected $groq;

    public function __construct(GroqService $groq) 
    {
        $this->groq = $groq;
    }

    public function simulate(array $persona, array $item): array
    {
        $start = microtime(true);

        $prompt = $this->buildPrompt($persona, $item);

        try {
            $result = $this->groq->chat([
                [
                    'role' => 'system',
                    'content' => 'You are a behavioral AI agent that simulates realistic Nigerian consumer reviews and ratings. Always respond with valid JSON only.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ], 0.75);

            $output = $this->normalize($result);

            AgentRun::create([
                'agent_name' => 'ReviewSimulationAgent',
                'prompt' => $prompt,
                'input_payload' => [
                    'persona' => $persona,
                    'item' => $item,
                ],
                'output_payload' => $output,
                'latency_ms' => (int) ((microtime(true) - $start) * 1000),
                'success' => true,
            ]);

            return $output;

        } catch (\Throwable $e) {
            AgentRun::create([
                'agent_name' => 'ReviewSimulationAgent',
                'prompt' => $prompt,
                'input_payload' => [
                    'persona' => $persona,
                    'item' => $item,
                ],
                'output_payload' => null,
                'latency_ms' => (int) ((microtime(true) - $start) * 1000),
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function buildPrompt(array $persona, array $item): string
    {
        return <<<PROMPT
You are simulating how a real user would review an item.

The user persona is:

PERSONA:
{$this->json($persona)}

The product/business/item is:

ITEM:
{$this->json($item)}

Your task:
Generate a realistic star rating and written review for this user.

Important behavior rules:
- The review must match the user's personality, budget level, location, preferences, and context.
- If the persona is Nigerian or located in Nigeria, reflect realistic Nigerian consumer behavior.
- Consider factors like price sensitivity, delivery delay, portion size, trust, quality, customer service, traffic, and value for money.
- Do not overuse slang. Use it naturally only when appropriate.
- The rating must be an integer from 1 to 5.
- The review should sound like a real person, not an AI.
- Include a confidence score between 0 and 1.
- Include behavioral tags.
- Include brief reasoning explaining why this user likely gave that rating.

Return JSON ONLY using this schema:

{
  "rating": 4,
  "review": "string",
  "confidence": 0.87,
  "behavior_tags": ["price_sensitive", "delivery_conscious"],
  "reasoning": "short explanation",
  "nigerian_context_signals": ["budget awareness", "delivery delay"]
}
PROMPT;
    }

    private function normalize(array $result): array
    {
        return [
            'rating' => max(1, min(5, (int) Arr::get($result, 'rating', 3))),
            'review' => (string) Arr::get($result, 'review', ''),
            'confidence' => (float) Arr::get($result, 'confidence', 0.7),
            'behavior_tags' => Arr::get($result, 'behavior_tags', []),
            'reasoning' => Arr::get($result, 'reasoning', ''),
            'nigerian_context_signals' => Arr::get($result, 'nigerian_context_signals', []),
            'raw' => $result,
        ];
    }

    private function json(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}