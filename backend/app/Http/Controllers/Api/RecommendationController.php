<?php

namespace App\Http\Controllers\Api;

use App\Agents\RecommendationAgent;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\RecommendationLog;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function recommend(Request $request, RecommendationAgent $agent)
    {
        try{
            
                $validated = $request->validate([
                'persona' => 'required|array',
                'persona.name' => 'nullable|string',
                'persona.age' => 'nullable|integer',
                'persona.location' => 'nullable|string',
                'persona.budget_style' => 'nullable|string',
                'persona.personality_type' => 'nullable|string',
                'persona.preferences' => 'nullable|array',
                'persona.context' => 'nullable|array',
    
                'context' => 'nullable|array',
    
                'items' => 'required|array|min:2',
                'items.*.name' => 'required|string',
                'items.*.category' => 'nullable|string',
                'items.*.price_level' => 'nullable|integer',
                'items.*.description' => 'nullable|string',
                'items.*.metadata' => 'nullable|array',
            ]);
    
            $persona = Persona::create($validated['persona']);
    
            $result = $agent->recommend(
                $persona->toArray(),
                $validated['context'] ?? [],
                $validated['items']
            );
    
            $log = RecommendationLog::create([
                'persona_id' => $persona->id,
                'input_context' => $validated['context'] ?? [],
                'recommendations' => $result['recommendations'],
                'reasoning' => $result['overall_reasoning'],
                'confidence' => $result['confidence'],
            ]);
    
            return response()->json([
                'success' => true,
                'data' => [
                    'recommendation_log_id' => $log->id,
                    'persona' => $persona,
                    'result' => $result,
                ],
            ]);
            
        }catch(\Exception $e){
            \Log::error('Recommendation failed: ' . $e->getMessage());
        }
    }
}