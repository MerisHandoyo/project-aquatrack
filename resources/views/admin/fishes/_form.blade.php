@csrf

@if ($method !== 'POST')
    @method($method)
@endif

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama Ikan <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $fish?->name) }}" placeholder="Contoh: Ikan Mujair"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="scientific_name" class="block text-sm font-medium text-slate-700">Nama Ilmiah</label>
        <input type="text" name="scientific_name" id="scientific_name"
            value="{{ old('scientific_name', $fish?->scientific_name) }}" placeholder="Contoh: Oreochromis mossambicus"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        @error('scientific_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
        <textarea name="description" id="description" rows="4"
            placeholder="Penjelasan singkat tentang ikan ini"
            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description', $fish?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $fish?->is_active ?? true))
            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        <span class="text-sm">
            <span class="block font-medium text-slate-800">Ikan aktif</span>
            <span class="text-xs text-slate-500">Ikan aktif dapat dipilih pada form pengukuran.</span>
        </span>
    </label>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
    <a href="{{ $fish ? route('admin.fishes.show', $fish) : route('admin.fishes.index') }}"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">{{ $submitLabel }}</button>
</div>
