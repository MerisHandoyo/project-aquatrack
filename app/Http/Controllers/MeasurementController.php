<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\Measurement;
use App\Models\Pond;
use App\Services\WaterQualityAnalyzer;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Pengukuran Kualitas Air';
        $search = 'Cari catatan atau nama kolam...';
        $searchTerm = trim($request->input('search', ''));

        $filters = [
            'pond_id' => $request->input('pond_id'),
            'fish_id' => $request->input('fish_id'),
            'source' => $request->input('source'),
            'search' => $searchTerm,
        ];

        $measurements = Measurement::with(['pond', 'fish'])
            ->filter($filters)
            ->latest('measured_at')
            ->paginate(12)
            ->withQueryString();

        $ponds = Pond::orderBy('code')->get();
        $fishes = Fish::orderBy('name')->get();

        return view('admin.measurements.index', compact('title', 'search', 'searchTerm', 'measurements', 'ponds', 'fishes'));
    }

    public function create(Request $request)
    {
        $title = 'Tambah Pengukuran';

        $ponds = Pond::with(['fishes' => fn ($query) => $query->wherePivot('is_active', true)])
            ->orderBy('code')
            ->get();

        $fishes = Fish::where('is_active', true)
            ->with(['ponds' => fn ($query) => $query->wherePivot('is_active', true)])
            ->orderBy('name')
            ->get();

        $measurement = new Measurement([
            'measured_at' => now(),
            'ammonia_type' => 'nh3',
            'source' => Measurement::SOURCE_MANUAL,
            'pond_id' => $request->input('pond_id'),
            'fish_id' => $request->input('fish_id'),
        ]);

        return view('admin.measurements.create', compact('title', 'ponds', 'fishes', 'measurement'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMeasurement($request);

        $pond = Pond::findOrFail($validated['pond_id']);

        if (! $this->isFishStocked($pond, (int) $validated['fish_id'])) {
            return back()
                ->withErrors(['fish_id' => 'Jenis ikan belum tercatat aktif pada kolam yang dipilih.'])
                ->withInput();
        }

        $measurement = Measurement::create($validated);

        return redirect()
            ->route('admin.measurements.show', $measurement)
            ->with('success', 'Pengukuran berhasil disimpan.');
    }

    public function show(Measurement $measurement, WaterQualityAnalyzer $analyzer)
    {
        $title = 'Detail Pengukuran';

        $measurement->load(['pond', 'fish.standards']);

        $evaluation = $analyzer->evaluate($measurement);

        $nh3Estimate = null;

        if ($measurement->ammonia_type === 'tan') {
            $nh3Estimate = [
                'fraction' => $analyzer->nh3Fraction($measurement->ph, $measurement->temperature_c),
                'value' => $analyzer->estimatedNh3($measurement->ammonia_mg_l, $measurement->ph, $measurement->temperature_c),
            ];
        }

        return view('admin.measurements.show', compact('title', 'measurement', 'evaluation', 'nh3Estimate'));
    }

    public function edit(Measurement $measurement)
    {
        $title = 'Ubah Pengukuran';

        $ponds = Pond::with(['fishes' => fn ($query) => $query->wherePivot('is_active', true)])
            ->orderBy('code')
            ->get();

        $fishes = Fish::where('is_active', true)
            ->with(['ponds' => fn ($query) => $query->wherePivot('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('admin.measurements.edit', compact('title', 'measurement', 'ponds', 'fishes'));
    }

    public function update(Request $request, Measurement $measurement)
    {
        $validated = $this->validateMeasurement($request);

        $pond = Pond::findOrFail($validated['pond_id']);

        if (! $this->isFishStocked($pond, (int) $validated['fish_id'])) {
            return back()
                ->withErrors(['fish_id' => 'Jenis ikan belum tercatat aktif pada kolam yang dipilih.'])
                ->withInput();
        }

        $measurement->update($validated);

        return redirect()
            ->route('admin.measurements.show', $measurement)
            ->with('success', 'Pengukuran berhasil diperbarui.');
    }

    public function destroy(Measurement $measurement)
    {
        $measurement->delete();

        return redirect()
            ->route('admin.measurements.index')
            ->with('success', 'Pengukuran berhasil dihapus.');
    }

    private function validateMeasurement(Request $request): array
    {
        return $request->validate([
            'pond_id' => 'required|exists:ponds,id',
            'fish_id' => 'required|exists:fishes,id',
            'measured_at' => 'required|date',
            'temperature_c' => 'required|numeric|between:0,50',
            'dissolved_oxygen_mg_l' => 'required|numeric|between:0,20',
            'ph' => 'required|numeric|between:0,14',
            'ammonia_mg_l' => 'required|numeric|between:0,100',
            'ammonia_type' => 'required|in:nh3,nh4,tan',
            'alkalinity_mg_l' => 'required|numeric|between:0,500',
            'source' => 'required|in:manual,iot,simulation',
            'notes' => 'nullable|string|max:1000',
        ], [
            'pond_id.required' => 'Kolam wajib dipilih.',
            'fish_id.required' => 'Jenis ikan wajib dipilih.',
            'measured_at.required' => 'Tanggal dan waktu pengukuran wajib diisi.',
            'temperature_c.required' => 'Suhu air wajib diisi.',
            'dissolved_oxygen_mg_l.required' => 'Kadar DO wajib diisi.',
            'ph.required' => 'Nilai pH wajib diisi.',
            'ammonia_mg_l.required' => 'Kadar amonia wajib diisi.',
            'ammonia_type.required' => 'Jenis pengukuran amonia wajib dipilih.',
            'alkalinity_mg_l.required' => 'Alkalinitas wajib diisi.',
            'source.required' => 'Sumber data wajib dipilih.',
        ]);
    }

    private function isFishStocked(Pond $pond, int $fishId): bool
    {
        return $pond->fishes()
            ->whereKey($fishId)
            ->wherePivot('is_active', true)
            ->exists();
    }
}
