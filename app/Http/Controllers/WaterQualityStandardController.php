<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use App\Models\WaterQualityStandard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WaterQualityStandardController extends Controller
{
    public function index(Fish $fish)
    {
        return redirect()->route('admin.fishes.show', $fish);
    }

    public function create(Fish $fish)
    {
        $title = 'Tambah Standar Kualitas Air';
        $parameters = WaterQualityStandard::PARAMETERS;
        $ammoniaTypes = WaterQualityStandard::AMMONIA_TYPES;

        return view('admin.fishes.standards.create', compact('title', 'fish', 'parameters', 'ammoniaTypes'));
    }

    public function store(Request $request, Fish $fish)
    {
        $validated = $this->validateStandard($request, $fish);

        $fish->standards()->create($validated);

        return redirect()
            ->route('admin.fishes.show', $fish)
            ->with('success', 'Standar kualitas air berhasil ditambahkan.');
    }

    public function show(Fish $fish, WaterQualityStandard $standard)
    {
        $this->ensureOwnedBy($fish, $standard);

        return redirect()->route('admin.fishes.show', $fish);
    }

    public function edit(Fish $fish, WaterQualityStandard $standard)
    {
        $this->ensureOwnedBy($fish, $standard);

        $title = 'Ubah Standar Kualitas Air';
        $parameters = WaterQualityStandard::PARAMETERS;
        $ammoniaTypes = WaterQualityStandard::AMMONIA_TYPES;

        return view('admin.fishes.standards.edit', compact('title', 'fish', 'standard', 'parameters', 'ammoniaTypes'));
    }

    public function update(Request $request, Fish $fish, WaterQualityStandard $standard)
    {
        $this->ensureOwnedBy($fish, $standard);

        $validated = $this->validateStandard($request, $fish, $standard);

        $standard->update($validated);

        return redirect()
            ->route('admin.fishes.show', $fish)
            ->with('success', 'Standar kualitas air berhasil diperbarui.');
    }

    public function destroy(Fish $fish, WaterQualityStandard $standard)
    {
        $this->ensureOwnedBy($fish, $standard);

        $standard->delete();

        return redirect()
            ->route('admin.fishes.show', $fish)
            ->with('success', 'Standar kualitas air berhasil dihapus.');
    }

    private function validateStandard(Request $request, Fish $fish, ?WaterQualityStandard $standard = null): array
    {
        $validated = $request->validate([
            'parameter' => [
                'required',
                Rule::in(array_keys(WaterQualityStandard::PARAMETERS)),
                Rule::unique('water_quality_standards', 'parameter')
                    ->where('fish_id', $fish->id)
                    ->ignore($standard?->id),
            ],
            'min_value' => 'nullable|numeric|min:0',
            'max_value' => 'nullable|numeric|min:0',
            'ammonia_type' => 'nullable|required_if:parameter,ammonia|in:nh3,nh4,tan',
            'source' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_verified' => 'boolean',
        ], [
            'parameter.required' => 'Parameter wajib dipilih.',
            'parameter.unique' => 'Standar untuk parameter ini sudah ada pada jenis ikan tersebut.',
            'ammonia_type.required_if' => 'Jenis amonia wajib dipilih untuk parameter amonia.',
        ]);

        if ($validated['min_value'] === null && $validated['max_value'] === null) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'min_value' => 'Isi minimal salah satu batas: nilai minimum atau maksimum.',
            ]);
        }

        if ($validated['min_value'] !== null && $validated['max_value'] !== null
            && (float) $validated['min_value'] > (float) $validated['max_value']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'max_value' => 'Nilai maksimum harus lebih besar atau sama dengan nilai minimum.',
            ]);
        }

        $parameter = WaterQualityStandard::PARAMETERS[$validated['parameter']];

        $validated['unit'] = $parameter['unit'];
        $validated['ammonia_type'] = $validated['parameter'] === 'ammonia'
            ? $validated['ammonia_type']
            : null;
        $validated['is_verified'] = $request->boolean('is_verified');

        return $validated;
    }

    private function ensureOwnedBy(Fish $fish, WaterQualityStandard $standard): void
    {
        abort_unless($standard->fish_id === $fish->id, 404);
    }
}
