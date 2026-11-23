@csrf

@if ($method !== 'POST')
    @method($method)
@endif

@php
    $ammoniaTypes = \App\Models\WaterQualityStandard::AMMONIA_TYPES;
    $currentSource = old('source', $measurement->source ?? 'manual');
    $sources = ['manual' => 'Manual', 'iot' => 'IoT (API)'];
    if ($currentSource === 'simulation') {
        $sources['simulation'] = 'Simulasi (data demo)';
    }
@endphp

<div class="space-y-6">
    <div>
        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Informasi Pengukuran</h3>
        <div class="mt-3 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="pond_id" class="block text-sm font-medium text-slate-700">Kolam <span class="text-red-500">*</span></label>
                <select name="pond_id" id="pond_id"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih kolam</option>
                    @foreach ($ponds as $pond)
                        <option value="{{ $pond->id }}" data-fish-ids="{{ $pond->fishes->pluck('id')->implode(',') }}"
                            @selected((string) old('pond_id', $measurement->pond_id) === (string) $pond->id)>
                            {{ $pond->code }} – {{ $pond->name }}</option>
                    @endforeach
                </select>
                @error('pond_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fish_id" class="block text-sm font-medium text-slate-700">Jenis Ikan <span class="text-red-500">*</span></label>
                <select name="fish_id" id="fish_id"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih ikan</option>
                    @foreach ($fishes as $fish)
                        <option value="{{ $fish->id }}" data-ponds="{{ $fish->ponds->pluck('id')->implode(',') }}"
                            @selected((string) old('fish_id', $measurement->fish_id) === (string) $fish->id)>
                            {{ $fish->name }}</option>
                    @endforeach
                </select>
                @error('fish_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-slate-500">Hanya ikan yang tercatat aktif pada kolam terpilih yang bisa dipakai.</p>
            </div>

            <div>
                <label for="measured_at" class="block text-sm font-medium text-slate-700">Tanggal &amp; Waktu <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="measured_at" id="measured_at"
                    value="{{ old('measured_at', $measurement->measured_at?->format('Y-m-d\TH:i')) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('measured_at')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="source" class="block text-sm font-medium text-slate-700">Sumber Data <span class="text-red-500">*</span></label>
                <select name="source" id="source"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @foreach ($sources as $value => $label)
                        <option value="{{ $value }}" @selected($currentSource === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('source')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Nilai Parameter</h3>
        <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="temperature_c" class="block text-sm font-medium text-slate-700">Suhu Air (°C) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" max="50" name="temperature_c" id="temperature_c"
                    value="{{ old('temperature_c', $measurement->temperature_c) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('temperature_c')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="dissolved_oxygen_mg_l" class="block text-sm font-medium text-slate-700">DO (mg/L) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" max="20" name="dissolved_oxygen_mg_l"
                    id="dissolved_oxygen_mg_l" value="{{ old('dissolved_oxygen_mg_l', $measurement->dissolved_oxygen_mg_l) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('dissolved_oxygen_mg_l')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ph" class="block text-sm font-medium text-slate-700">pH <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" max="14" name="ph" id="ph"
                    value="{{ old('ph', $measurement->ph) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('ph')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ammonia_mg_l" class="block text-sm font-medium text-slate-700">Amonia (mg/L) <span class="text-red-500">*</span></label>
                <input type="number" step="0.001" min="0" max="100" name="ammonia_mg_l" id="ammonia_mg_l"
                    value="{{ old('ammonia_mg_l', $measurement->ammonia_mg_l) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('ammonia_mg_l')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ammonia_type" class="block text-sm font-medium text-slate-700">Jenis Amonia <span class="text-red-500">*</span></label>
                <select name="ammonia_type" id="ammonia_type"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @foreach ($ammoniaTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('ammonia_type', $measurement->ammonia_type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ammonia_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-slate-500">Jangan mencampur NH3, NH4+, dan TAN dalam satu nilai.</p>
            </div>

            <div>
                <label for="alkalinity_mg_l" class="block text-sm font-medium text-slate-700">Alkalinitas (mg/L CaCO3) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" max="500" name="alkalinity_mg_l" id="alkalinity_mg_l"
                    value="{{ old('alkalinity_mg_l', $measurement->alkalinity_mg_l) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('alkalinity_mg_l')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-slate-700">Keterangan</label>
        <textarea name="notes" id="notes" rows="3" placeholder="Misalnya kondisi cuaca, pemberian pakan, atau alat ukur"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('notes', $measurement->notes) }}</textarea>
        @error('notes')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
    <a href="{{ $measurement->exists ? route('admin.measurements.show', $measurement) : route('admin.measurements.index') }}"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">{{ $submitLabel }}</button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pondSelect = document.getElementById('pond_id');
            const fishSelect = document.getElementById('fish_id');

            if (!pondSelect || !fishSelect) {
                return;
            }

            function syncFishOptions() {
                const pondId = pondSelect.value;

                Array.from(fishSelect.options).forEach(function(option) {
                    if (!option.value) {
                        return;
                    }

                    const pondIds = (option.dataset.ponds || '').split(',').filter(Boolean);
                    const allowed = pondId === '' || pondIds.includes(pondId);

                    option.disabled = !allowed;
                    option.hidden = !allowed;
                });

                if (fishSelect.selectedOptions[0] && fishSelect.selectedOptions[0].disabled) {
                    fishSelect.value = '';
                }
            }

            pondSelect.addEventListener('change', syncFishOptions);
            syncFishOptions();
        });
    </script>
@endpush
