@csrf

@if ($method !== 'POST')
    @method($method)
@endif

@php
    $currentParameter = old('parameter', $standard?->parameter);
    $currentUnit = $parameters[$currentParameter]['unit'] ?? '';
@endphp

<div class="space-y-4">
    <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-xs leading-relaxed text-blue-800">
        Gunakan nilai dari sumber ilmiah atau pedoman budidaya. Jangan mengisi batas berdasarkan perkiraan. Untuk
        amonia, pastikan jenis pengukurannya (NH3, NH4+, atau TAN) sama dengan yang dicatat pada form pengukuran.
    </div>

    <div>
        <label for="parameter" class="block text-sm font-medium text-slate-700">Parameter <span class="text-red-500">*</span></label>
        <select name="parameter" id="parameter"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Pilih parameter</option>
            @foreach ($parameters as $key => $meta)
                <option value="{{ $key }}" @selected($currentParameter === $key)>{{ $meta['label'] }}
                    {{ $meta['unit'] ? '(' . $meta['unit'] . ')' : '' }}</option>
            @endforeach
        </select>
        @error('parameter')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="min_value" class="block text-sm font-medium text-slate-700">Nilai Minimum</label>
            <input type="number" step="any" min="0" name="min_value" id="min_value"
                value="{{ old('min_value', $standard?->min_value) }}" placeholder="Kosongkan bila tanpa batas bawah"
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            @error('min_value')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="max_value" class="block text-sm font-medium text-slate-700">Nilai Maksimum</label>
            <input type="number" step="any" min="0" name="max_value" id="max_value"
                value="{{ old('max_value', $standard?->max_value) }}" placeholder="Kosongkan bila tanpa batas atas"
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            @error('max_value')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div id="ammoniaTypeWrapper" class="{{ $currentParameter === 'ammonia' ? '' : 'hidden' }}">
        <label for="ammonia_type" class="block text-sm font-medium text-slate-700">Jenis Amonia</label>
        <select name="ammonia_type" id="ammonia_type"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Pilih jenis amonia</option>
            @foreach ($ammoniaTypes as $key => $label)
                <option value="{{ $key }}" @selected(old('ammonia_type', $standard?->ammonia_type) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('ammonia_type')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="source" class="block text-sm font-medium text-slate-700">Sumber / Referensi</label>
        <input type="text" name="source" id="source" value="{{ old('source', $standard?->source) }}"
            placeholder="Contoh: FAO – Cultured Aquatic Species (ringkasan parameter air)"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('source')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium text-slate-700">Catatan</label>
        <textarea name="notes" id="notes" rows="3"
            placeholder="Catatan tambahan, misalnya pengaruh suhu/pH terhadap parameter ini"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('notes', $standard?->notes) }}</textarea>
        @error('notes')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
        <input type="hidden" name="is_verified" value="0">
        <input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $standard?->is_verified ?? false))
            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <span class="text-sm">
            <span class="block font-medium text-slate-800">Sudah tervalidasi</span>
            <span class="text-xs text-slate-500">Centang hanya jika nilai acuan sudah diverifikasi dengan sumber resmi
                atau penyuluh.</span>
        </span>
    </label>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
    <a href="{{ route('admin.fishes.show', $fish) }}"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">{{ $submitLabel }}</button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const parameter = document.getElementById('parameter');
            const wrapper = document.getElementById('ammoniaTypeWrapper');

            if (!parameter || !wrapper) {
                return;
            }

            parameter.addEventListener('change', function() {
                wrapper.classList.toggle('hidden', parameter.value !== 'ammonia');
            });
        });
    </script>
@endpush
