<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentRun;
use App\Models\RecommendationLog;
use App\Models\ReviewSimulation;

class AnalyticsController extends Controller
{
    public function index()
    {
       try{
           
            $simulationsCount = ReviewSimulation::count();
            $recommendationsCount = RecommendationLog::count();
    
            $avgReviewConfidence = ReviewSimulation::avg('confidence') ?? 0;
            $avgRecommendationConfidence = RecommendationLog::avg('confidence') ?? 0;
    
            $confidenceSources = collect([
                $avgReviewConfidence,
                $avgRecommendationConfidence,
            ])->filter(function($v) {
                return $v > 0;
            });
    
            $avgConfidence = $confidenceSources->count()
                ? round($confidenceSources->avg(), 2)
                : 0;
    
            $tags = ReviewSimulation::query()
                ->pluck('behavior_tags')
                ->filter()
                ->flatMap(function ($tags) {
                    return is_array($tags) ? $tags : json_decode($tags, true);
                })
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take(6)
                ->map(function($count, $tag){
                    return [
                            'signal' => $tag,
                            'score' => $count,
                        ];
                })
                ->values();
    
            $traces = AgentRun::query()
                ->where('success', true)
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($run) {
                    $output = $run->output_payload ?? [];
    
                    return [
                        'agent' => $run->agent_name,
                        'text' => $output['reasoning']
                            ?? $output['overall_reasoning']
                            ?? 'Agent completed successfully.',
                        'latency_ms' => $run->latency_ms,
                    ];
                });
    
            return response()->json([
                'success' => true,
                'data' => [
                    'simulations' => $simulationsCount,
                    'recommendations' => $recommendationsCount,
                    'avg_confidence' => $avgConfidence,
                    'top_signals' => $tags,
                    'reasoning_traces' => $traces,
                ],
            ]);
           
       }catch(\Exception $e){
           \Log::error('Failure while loading aanlytics data: ' . $e->getMessage());
       }
    }
}