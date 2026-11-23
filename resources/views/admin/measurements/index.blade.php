<x-admin.layout>

    <x-admin.page-header title="Pengukuran Kualitas Air"
        subtitle="Catatan manual parameter suhu, DO, pH, amonia, dan alkalinitas per kolam.">
        <a href="{{ route('admin.measurements.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Pengukuran
        </a>
    </x-admin.page-header>

    <form action="{{ route('admin.measurements.index') }}" method="GET"
        class="mb-4 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <label for="search" class="sr-only">Cari</label>
            <input type="text" name="search" id="search" value="{{ $searchTerm }}" placeholder="{{ $search }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
            <select name="pond_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua kolam</option>
                @foreach ($ponds as $pond)
                    <option value="{{ $pond->id }}" @selected(request('pond_id') == $pond->id)>{{ $pond->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="fish_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua ikan</option>
                @foreach ($fishes as $fish)
                    <option value="{{ $fish->id }}" @selected(request('fish_id') == $fish->id)>{{ $fish->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <select name="source"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua sumber</option>
                <option value="manual" @selected(request('source') === 'manual')>Manual</option>
                <option value="iot" @selected(request('source') === 'iot')>IoT</option>
                <option value="simulation" @selected(request('source') === 'simulation')>Simulasi</option>
            </select>
            <button type="submit"
                class="whitespace-nowrap rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($measurements->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Tidak ada pengukuran yang cocok dengan filter.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Kolam</th>
                            <th class="px-4 py-3">Ikan</th>
                            <th class="px-4 py-3">Suhu</th>
                            <th class="px-4 py-3">DO</th>
                            <th class="px-4 py-3">pH</th>
                            <th class="px-4 py-3">Amonia</th>
                            <th class="px-4 py-3">Alkalinitas</th>
                            <th class="px-4 py-3">Sumber</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($measurements as $measurement)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-3">
                                    <a href="{{ route('admin.measurements.show', $measurement) }}"
                                        class="font-medium text-blue-700 hover:underline">
                                        {{ $measurement->measured_at->format('d/m/Y H:i') }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $measurement->pond->name }}</td>
                                <td class="px-4 py-3">{{ $measurement->fish->name }}</td>
                                <td class="px-4 py-3">{{ $measurement->temperature_c }} °C</td>
                                <td class="px-4 py-3">{{ $measurement->dissolved_oxygen_mg_l }} mg/L</td>
                                <td class="px-4 py-3">{{ $measurement->ph }}</td>
                                <td class="px-4 py-3">
                                    {{ $measurement->ammonia_mg_l }} mg/L
                                    <span class="text-xs uppercase text-slate-500">{{ $measurement->ammonia_type }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $measurement->alkalinity_mg_l }} mg/L</td>
                                <td class="px-4 py-3"><x-admin.source-badge :source="$measurement->source" /></td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.measurements.edit', $measurement) }}"
                                            class="font-medium text-slate-600 hover:text-slate-900">Ubah</a>
                                        <form action="{{ route('admin.measurements.destroy', $measurement) }}" method="POST"
                                            onsubmit="return confirm('Hapus pengukuran {{ $measurement->measured_at->format('d/m/Y H:i') }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">{{ $measurements->links() }}</div>

</x-admin.layout>
