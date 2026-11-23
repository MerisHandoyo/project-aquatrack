<x-admin.layout>

    <x-admin.page-header title="Riwayat Pengukuran"
        subtitle="Seluruh catatan pengukuran dengan filter kolam, ikan, sumber data, dan rentang tanggal." />

    <form action="{{ route('admin.history') }}" method="GET"
        class="mb-4 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
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
                    <option value="{{ $pond->id }}" @selected(($filters['pond_id'] ?? null) == $pond->id)>{{ $pond->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="fish_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua ikan</option>
                @foreach ($fishes as $fish)
                    <option value="{{ $fish->id }}" @selected(($filters['fish_id'] ?? null) == $fish->id)>{{ $fish->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="source"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua sumber</option>
                <option value="manual" @selected(($filters['source'] ?? null) === 'manual')>Manual</option>
                <option value="iot" @selected(($filters['source'] ?? null) === 'iot')>IoT</option>
                <option value="simulation" @selected(($filters['source'] ?? null) === 'simulation')>Simulasi</option>
            </select>
        </div>
        <div>
            <label for="from" class="sr-only">Dari tanggal</label>
            <input type="date" name="from" id="from" value="{{ $filters['from'] ?? '' }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
            <label for="to" class="sr-only">Sampai tanggal</label>
            <input type="date" name="to" id="to" value="{{ $filters['to'] ?? '' }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div class="flex gap-2 lg:col-span-2">
            <button type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Terapkan</button>
            <a href="{{ route('admin.history') }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-semibold text-slate-900">{{ $measurements->total() }}</span> catatan
            </p>
            <a href="{{ route('admin.measurements.create') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800">+ Tambah pengukuran</a>
        </div>

        @if ($measurements->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Tidak ada catatan yang cocok dengan filter.</p>
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
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($measurements as $measurement)
                            @php $overall = $evaluations[$measurement->id]; @endphp
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
                                <td class="px-4 py-3"><x-admin.status-badge :status="$overall['status']"
                                        :label="$overall['label']" /></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.measurements.edit', $measurement) }}"
                                        class="font-medium text-slate-600 hover:text-slate-900">Ubah</a>
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
