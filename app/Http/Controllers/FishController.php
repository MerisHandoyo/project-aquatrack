<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\WaterQualityStandard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FishController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Ikan';
        $search = 'Cari nama atau nama ilmiah ikan...';
        $searchTerm = trim($request->input('search', ''));

        $fishes = Fish::withCount(['standards', 'measurements'])
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($inner) use ($searchTerm) {
                    $inner->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('scientific_name', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.fishes.index', compact('title', 'search', 'searchTerm', 'fishes'));
    }

    public function create()
    {
        $title = 'Tambah Jenis Ikan';

        return view('admin.fishes.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fishes,name',
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama ikan wajib diisi.',
            'name.unique' => 'Nama ikan sudah terdaftar.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Fish::create($validated);

        return redirect()
            ->route('admin.fishes.index')
            ->with('success', 'Jenis ikan berhasil ditambahkan.');
    }

    public function show(Fish $fish)
    {
        $title = $fish->name;

        $fish->load(['standards', 'measurements.pond']);

        $parameterOrder = array_keys(WaterQualityStandard::PARAMETERS);
        $standards = $fish->standards
            ->sortBy(fn (WaterQualityStandard $standard) => array_search($standard->parameter, $parameterOrder))
            ->values();

        $latestMeasurements = $fish->measurements
            ->sortByDesc('measured_at')
            ->take(10);

        return view('admin.fishes.show', compact('title', 'fish', 'standards', 'latestMeasurements'));
    }

    public function edit(Fish $fish)
    {
        $title = 'Ubah Jenis Ikan';

        return view('admin.fishes.edit', compact('title', 'fish'));
    }

    public function update(Request $request, Fish $fish)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('fishes', 'name')->ignore($fish->id)],
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama ikan wajib diisi.',
            'name.unique' => 'Nama ikan sudah terdaftar.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $fish->update($validated);

        return redirect()
            ->route('admin.fishes.show', $fish)
            ->with('success', 'Data ikan berhasil diperbarui.');
    }

    public function destroy(Fish $fish)
    {
        if ($fish->measurements()->exists()) {
            return redirect()
                ->route('admin.fishes.index')
                ->with('error', 'Ikan tidak dapat dihapus karena sudah memiliki riwayat pengukuran.');
        }

        if ($fish->ponds()->exists()) {
            return redirect()
                ->route('admin.fishes.index')
                ->with('error', 'Ikan tidak dapat dihapus karena masih terhubung dengan data kolam.');
        }

        $fish->delete();

        return redirect()
            ->route('admin.fishes.index')
            ->with('success', 'Jenis ikan berhasil dihapus.');
    }
}
