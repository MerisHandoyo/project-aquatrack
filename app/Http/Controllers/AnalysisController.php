<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\Measurement;
use App\Models\Pond;
use App\Models\WaterQualityStandard;
use App\Services\WaterQualityAnalyzer;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(Request $request, WaterQualityAnalyzer $analyzer)
    {
        $title = 'Analisis Kualitas Air';

        $filters = [
            'pond_id' => $request->input('pond_id'),
            'fish_id' => $request->input('fish_id'),
            'source' => $request->input('source'),
        ];

        $days = in_array((int) $request->input('days'), [7, 30, 90], true)
            ? (int) $request->input('days')
            : 30;

        $measurements = Measurement::with(['pond', 'fish.standards'])
            ->filter($filters)
            ->where('measured_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->orderBy('measured_at')
            ->get();

        $latest = $measurements->last();
        $evaluation = $latest ? $analyzer->evaluate($latest) : null;

        $charts = [
            'labels' => $measurements
                ->map(fn (Measurement $measurement) => $measurement->measured_at->format('d/m H:i'))
                ->all(),
            'series' => collect(WaterQualityStandard::PARAMETERS)
                ->map(fn (array $meta, string $key) => [
                    'label' => $meta['label'],
                    'unit' => $meta['unit'],
                    'data' => $measurements
                        ->map(fn (Measurement $measurement) => $measurement->{$meta['column']})
                        ->all(),
                ])
                ->all(),
        ];

        $selectedFish = $filters['fish_id']
            ? Fish::with('standards')->find($filters['fish_id'])
            : null;

        $standards = $selectedFish
            ? $selectedFish->standards->sortBy(
                fn ($standard) => array_search($standard->parameter, array_keys(WaterQualityStandard::PARAMETERS))
            )->values()
            : collect();

        $sources = $standards->pluck('source')->filter()->unique()->values();

        $ponds = Pond::orderBy('code')->get();
        $fishes = Fish::orderBy('name')->get();

        return view('admin.analysis', compact(
            'title',
            'ponds',
            'fishes',
            'filters',
            'days',
            'measurements',
            'latest',
            'evaluation',
            'charts',
            'selectedFish',
            'standards',
            'sources',
        ));
    }
}
