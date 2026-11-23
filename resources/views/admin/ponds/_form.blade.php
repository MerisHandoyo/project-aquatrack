@csrf

@if ($method !== 'POST')
    @method($method)
@endif

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="code" class="block text-sm font-medium text-slate-700">Kode Kolam <span class="text-red-500">*</span></label>
        <input type="text" name="code" id="code" value="{{ old('code', $pond?->code) }}" placeholder="Contoh: K-05"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('code')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama Kolam <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $pond?->name) }}"
            placeholder="Contoh: Kolam Nila E"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="location" class="block text-sm font-medium text-slate-700">Lokasi</label>
        <input type="text" name="location" id="location" value="{{ old('location', $pond?->location) }}"
            placeholder="Contoh: Blok E"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('location')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="volume_m3" class="block text-sm font-medium text-slate-700">Volume (m³)</label>
        <input type="number" step="0.01" min="0" name="volume_m3" id="volume_m3"
            value="{{ old('volume_m3', $pond?->volume_m3) }}"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('volume_m3')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="depth_m" class="block text-sm font-medium text-slate-700">Kedalaman (m)</label>
        <input type="number" step="0.01" min="0" name="depth_m" id="depth_m"
            value="{{ old('depth_m', $pond?->depth_m) }}"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('depth_m')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label for="notes" class="block text-sm font-medium text-slate-700">Keterangan</label>
    <textarea name="notes" id="notes" rows="3" placeholder="Catatan tambahan tentang kolam"
        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('notes', $pond?->notes) }}</textarea>
    @error('notes')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
    <a href="{{ $pond ? route('admin.ponds.show', $pond) : route('admin.ponds.index') }}"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">{{ $submitLabel }}</button>
</div>
