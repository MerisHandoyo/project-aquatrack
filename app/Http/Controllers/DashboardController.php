<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\Measurement;
use App\Models\Pond;
use App\Services\WaterQualityAnalyzer;

class DashboardController extends Controller
{
    public function index(WaterQualityAnalyzer $analyzer)
    {
        $ponds = Pond::with(['fishes', 'latestMeasurement.fish.standards'])
            ->orderBy('code')
            ->get();

        $summary = [
            'baik' => 0,
            'perlu_validasi' => 0,
            'peringatan' => 0,
            'bahaya' => 0,
            'tanpa_acuan' => 0,
            'tidak_diketahui' => 0,
        ];

        $attention = [];

        foreach ($ponds as $pond) {
            $measurement = $pond->latestMeasurement;

            if (! $measurement) {
                $summary['tidak_diketahui']++;

                continue;
            }

            $overall = $analyzer->evaluate($measurement)['overall'];

            match ($overall['status']) {
                WaterQualityAnalyzer::STATUS_DANGER => $summary['bahaya']++,
                WaterQualityAnalyzer::STATUS_WARNING => $summary['peringatan']++,
                WaterQualityAnalyzer::STATUS_UNVERIFIED_OK => $summary['perlu_validasi']++,
                WaterQualityAnalyzer::STATUS_NO_STANDARD => $summary['tanpa_acuan']++,
                'tidak_diketahui' => $summary['tidak_diketahui']++,
                default => $summary['baik']++,
            };

            if (in_array($overall['status'], [WaterQualityAnalyzer::STATUS_DANGER, WaterQualityAnalyzer::STATUS_WARNING], true)) {
                $attention[] = [
                    'pond' => $pond,
                    'measurement' => $measurement,
                    'overall' => $overall,
                ];
            }
        }

        $latestMeasurements = Measurement::with(['pond', 'fish.standards'])
            ->latest('measured_at')
            ->take(6)
            ->get();

        $latestEvaluations = $latestMeasurements
            ->mapWithKeys(fn (Measurement $measurement) => [
                $measurement->id => $analyzer->evaluate($measurement)['overall'],
            ]);

        $trendMeasurements = Measurement::where('measured_at', '>=', now()->subDays(13)->startOfDay())
            ->orderBy('measured_at')
            ->get();

        $trend = $trendMeasurements
            ->groupBy(fn (Measurement $measurement) => $measurement->measured_at->format('Y-m-d'))
            ->map(fn ($group, string $date) => [
                'label' => now()->parse($date)->format('d/m'),
                'temperature' => round($group->avg('temperature_c'), 2),
                'oxygen' => round($group->avg('dissolved_oxygen_mg_l'), 2),
            ])
            ->values();

        $stats = [
            'ponds' => $ponds->count(),
            'fishes' => Fish::where('is_active', true)->count(),
            'measurements' => Measurement::count(),
            'today' => Measurement::whereDate('measured_at', today())->count(),
        ];

        return view('admin.dashboard', compact('stats', 'summary', 'attention', 'latestMeasurements', 'latestEvaluations', 'trend'));
    }
}
