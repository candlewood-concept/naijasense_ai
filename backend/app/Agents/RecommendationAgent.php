<?php

namespace App\Agents;

use App\Models\AgentRun;
use App\Services\GroqService;
use Illuminate\Support\Arr;

class RecommendationAgent
{
    protected $groq;
    
    public function __construct(GroqService $groq) {
        $this->groq = $groq;
    }

    public function recommend(array $persona, array $context, array $items): array
    {
        $start = microtime(true);
        $prompt = $this->buildPrompt($persona, $context, $items);

        try {
            $result = $this->groq->chat([
                [
                    'role' => 'system',
                    'content' => 'You are an agentic recommendation system that understands Nigerian consumer behavior. Return valid JSON only.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ], 0.65);

            $output = $this->normalize($result);

            AgentRun::create([
                'agent_name' => 'RecommendationAgent',
                'prompt' => $prompt,
                'input_payload' => compact('persona', 'context', 'items'),
                'output_payload' => $output,
                'latency_ms' => (int) ((microtime(true) - $start) * 1000),
                'success' => true,
            ]);

            return $output;

        } catch (\Throwable $e) {
            AgentRun::create([
                'agent_name' => 'RecommendationAgent',
                'prompt' => $prompt,
                'input_payload' => compact('persona', 'context', 'items'),
                'latency_ms' => (int) ((microtime(true) - $start) * 1000),
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function buildPrompt(array $persona, array $context, array $items): string
    {
        return <<<PROMPT
You are recommending items to a real user.

PERSONA:
{$this->json($persona)}

CURRENT CONTEXT:
{$this->json($context)}

AVAILABLE ITEMS:
{$this->json($items)}

Your task:
Rank the best items for this user.

Think about:
- budget
- location
- mood
- time of day
- Nigerian consumer behavior
- value for money
- delivery speed
- trust
- quality
- previous preferences
- cold-start reasoning

Return JSON ONLY in this schema:

{
  "recommendations": [
    {
      "rank": 1,
      "item_name": "string",
      "score": 0.92,
      "reason": "short human explanation",
      "matched_signals": ["budget fit", "fast delivery"]
    }
  ],
  "overall_reasoning": "short explanation of the ranking strategy",
  "confidence": 0.88,
  "contextual_signals_used": ["budget", "location", "mood"]
}
PROMPT;
    }

    private function normalize(array $result): array
    {
        return [
            'recommendations' => Arr::get($result, 'recommendations', []),
            'overall_reasoning' => Arr::get($result, 'overall_reasoning', ''),
            'confidence' => (float) Arr::get($result, 'confidence', 0.75),
            'contextual_signals_used' => Arr::get($result, 'contextual_signals_used', []),
            'raw' => $result,
        ];
    }

    private function json(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}