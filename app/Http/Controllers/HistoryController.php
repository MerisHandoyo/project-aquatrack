<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\Measurement;
use App\Models\Pond;
use App\Services\WaterQualityAnalyzer;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request, WaterQualityAnalyzer $analyzer)
    {
        $title = 'Riwayat Pengukuran';
        $search = 'Cari catatan, kolam, atau ikan...';
        $searchTerm = trim($request->input('search', ''));

        $filters = [
            'pond_id' => $request->input('pond_id'),
            'fish_id' => $request->input('fish_id'),
            'source' => $request->input('source'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'search' => $searchTerm,
        ];

        $measurements = Measurement::with(['pond', 'fish.standards'])
            ->filter($filters)
            ->latest('measured_at')
            ->paginate(15)
            ->withQueryString();

        $evaluations = $measurements->getCollection()
            ->mapWithKeys(fn (Measurement $measurement) => [
                $measurement->id => $analyzer->evaluate($measurement)['overall'],
            ]);

        $ponds = Pond::orderBy('code')->get();
        $fishes = Fish::orderBy('name')->get();

        return view('admin.history', compact(
            'title',
            'search',
            'searchTerm',
            'measurements',
            'evaluations',
            'ponds',
            'fishes',
            'filters',
        ));
    }
}
