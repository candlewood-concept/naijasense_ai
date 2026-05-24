<?php

namespace App\Http\Controllers\Api;

use App\Agents\ReviewSimulationAgent;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Persona;
use App\Models\ReviewSimulation;
use Illuminate\Http\Request;

class ReviewSimulationController extends Controller
{
    public function simulate(Request $request, ReviewSimulationAgent $agent)
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

            'item' => 'required|array',
            'item.name' => 'required|string',
            'item.category' => 'nullable|string',
            'item.price_level' => 'nullable|integer',
            'item.metadata' => 'nullable|array',
            'item.description' => 'nullable|string',
        ]);

        $persona = Persona::create($validated['persona']);

        $item = Item::create([
            'name' => $validated['item']['name'],
            'category' => $validated['item']['category'] ?? null,
            'price_level' => $validated['item']['price_level'] ?? 1,
            'metadata' => $validated['item']['metadata'] ?? [],
            'description' => $validated['item']['description'] ?? null,
        ]);

        $result = $agent->simulate($persona->toArray(), $item->toArray());

        $simulation = ReviewSimulation::create([
            'persona_id' => $persona->id,
            'item_id' => $item->id,
            'rating' => $result['rating'],
            'review' => $result['review'],
            'confidence' => $result['confidence'],
            'behavior_tags' => $result['behavior_tags'],
            'raw_output' => $result,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'simulation_id' => $simulation->id,
                'persona' => $persona,
                'item' => $item,
                'result' => $result,
            ],
        ]);
       }catch(\Exception $e){
           \Log::error('api call failed: ' . $e->getMessage());
       }
    }
}