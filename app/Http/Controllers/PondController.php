<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\Pond;
use App\Services\WaterQualityAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PondController extends Controller
{
    public function index(Request $request, WaterQualityAnalyzer $analyzer)
    {
        $title = 'Data Kolam';
        $search = 'Cari nama, kode, atau lokasi kolam...';
        $searchTerm = trim($request->input('search', ''));

        $ponds = Pond::withCount('measurements')
            ->with(['fishes', 'latestMeasurement.fish.standards'])
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($inner) use ($searchTerm) {
                    $inner->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('code', 'like', "%{$searchTerm}%")
                        ->orWhere('location', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('code')
            ->paginate(9)
            ->withQueryString();

        $evaluations = $ponds->getCollection()
            ->mapWithKeys(function (Pond $pond) use ($analyzer) {
                return [
                    $pond->id => $pond->latestMeasurement
                        ? $analyzer->evaluate($pond->latestMeasurement)['overall']
                        : null,
                ];
            });

        return view('admin.ponds.index', compact('title', 'search', 'searchTerm', 'ponds', 'evaluations'));
    }

    public function create()
    {
        $title = 'Tambah Kolam';

        return view('admin.ponds.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:ponds,code',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'volume_m3' => 'nullable|numeric|min:0',
            'depth_m' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ], [
            'code.required' => 'Kode kolam wajib diisi.',
            'code.unique' => 'Kode kolam sudah digunakan.',
            'name.required' => 'Nama kolam wajib diisi.',
        ]);

        Pond::create($validated);

        return redirect()
            ->route('admin.ponds.index')
            ->with('success', 'Data kolam berhasil ditambahkan.');
    }

    public function show(Pond $pond, WaterQualityAnalyzer $analyzer)
    {
        $title = $pond->name;

        $pond->load([
            'fishes',
            'latestMeasurement.fish.standards',
            'measurements' => fn ($query) => $query->with('fish')->latest('measured_at')->limit(10),
        ]);

        $latestEvaluation = $pond->latestMeasurement
            ? $analyzer->evaluate($pond->latestMeasurement)
            : null;

        $availableFishes = Fish::where('is_active', true)->orderBy('name')->get();

        return view('admin.ponds.show', compact('title', 'pond', 'latestEvaluation', 'availableFishes'));
    }

    public function edit(Pond $pond)
    {
        $title = 'Ubah Kolam';

        return view('admin.ponds.edit', compact('title', 'pond'));
    }

    public function update(Request $request, Pond $pond)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', Rule::unique('ponds', 'code')->ignore($pond->id)],
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'volume_m3' => 'nullable|numeric|min:0',
            'depth_m' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ], [
            'code.required' => 'Kode kolam wajib diisi.',
            'code.unique' => 'Kode kolam sudah digunakan.',
            'name.required' => 'Nama kolam wajib diisi.',
        ]);

        $pond->update($validated);

        return redirect()
            ->route('admin.ponds.show', $pond)
            ->with('success', 'Data kolam berhasil diperbarui.');
    }

    public function destroy(Pond $pond)
    {
        $pond->delete();

        return redirect()
            ->route('admin.ponds.index')
            ->with('success', 'Data kolam berhasil dihapus.');
    }

    public function storeFish(Request $request, Pond $pond)
    {
        $validated = $request->validate([
            'fish_id' => 'required|exists:fishes,id',
            'stock_date' => 'nullable|date',
            'population' => 'nullable|integer|min:1',
        ], [
            'fish_id.required' => 'Jenis ikan wajib dipilih.',
            'fish_id.exists' => 'Jenis ikan tidak ditemukan.',
        ]);

        $alreadyStocked = $pond->fishes()
            ->where('fishes.id', $validated['fish_id'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($alreadyStocked) {
            return back()->with('error', 'Jenis ikan tersebut sudah tercatat aktif di kolam ini.');
        }

        $pond->fishes()->attach($validated['fish_id'], [
            'stock_date' => $validated['stock_date'] ?? now()->toDateString(),
            'population' => $validated['population'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Jenis ikan berhasil ditambahkan ke kolam.');
    }

    public function destroyFish(Pond $pond, Fish $fish)
    {
        $pond->fishes()->detach($fish->id);

        return back()->with('success', 'Jenis ikan dikeluarkan dari kolam. Riwayat pengukuran tetap tersimpan.');
    }
}
