<x-admin.layout>

    <x-admin.page-header :title="$pond->name" :subtitle="'Kode ' . $pond->code . ($pond->location ? ' · ' . $pond->location : '')">
        <a href="{{ route('admin.measurements.create', ['pond_id' => $pond->id]) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Pengukuran
        </a>
        <a href="{{ route('admin.ponds.edit', $pond) }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ubah</a>
    </x-admin.page-header>

    <div class="grid gap-4 lg:grid-cols-3">

        <div class="space-y-4 lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Informasi Kolam</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Kode</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $pond->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Volume</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $pond->volume_m3 !== null ? $pond->volume_m3 . ' m³' : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Kedalaman</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $pond->depth_m !== null ? $pond->depth_m . ' m' : '-' }}</dd>
                    </div>
                </dl>
                @if ($pond->notes)
                    <p class="mt-4 rounded-lg bg-slate-50 px-3 py-3 text-sm text-slate-600">{{ $pond->notes }}</p>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Kondisi Terbaru</h2>
                        <p class="text-xs text-slate-500">
                            @if ($pond->latestMeasurement)
                                Diukur {{ $pond->latestMeasurement->measured_at->format('d/m/Y H:i') }} ·
                                {{ $pond->latestMeasurement->fish->name }}
                            @else
                                Belum ada pengukuran untuk kolam ini.
                            @endif
                        </p>
                    </div>
                    @if ($latestEvaluation)
                        <x-admin.status-badge :status="$latestEvaluation['overall']['status']"
                            :label="$latestEvaluation['overall']['label']" />
                    @endif
                </div>

                @if ($latestEvaluation)
                    <p class="mt-3 text-sm text-slate-600">{{ $latestEvaluation['overall']['message'] }}</p>

                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-3 py-2">Parameter</th>
                                    <th class="px-3 py-2">Nilai</th>
                                    <th class="px-3 py-2">Acuan</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($latestEvaluation['parameters'] as $parameter)
                                    <tr title="{{ $parameter['message'] }}">
                                        <td class="px-3 py-2 font-medium text-slate-900">{{ $parameter['label'] }}</td>
                                        <td class="px-3 py-2">{{ $parameter['value'] }} {{ $parameter['unit'] }}</td>
                                        <td class="px-3 py-2 text-xs">
                                            @if ($parameter['standard'])
                                                @if ($parameter['standard']['min'] !== null && $parameter['standard']['max'] !== null)
                                                    {{ $parameter['standard']['min'] }}–{{ $parameter['standard']['max'] }}
                                                @elseif ($parameter['standard']['min'] !== null)
                                                    min. {{ $parameter['standard']['min'] }}
                                                @else
                                                    maks. {{ $parameter['standard']['max'] }}
                                                @endif
                                                {{ $parameter['standard']['unit'] }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <x-admin.status-badge :status="$parameter['status']"
                                                :label="$parameter['label_status']" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-4 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                        Kondisi belum dapat dinilai karena belum ada data pengukuran.
                    </p>
                @endif
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Pengukuran Terakhir</h2>
                    <a href="{{ route('admin.history', ['pond_id' => $pond->id]) }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat riwayat</a>
                </div>

                @if ($pond->measurements->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pengukuran.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Ikan</th>
                                    <th class="px-4 py-3">Suhu</th>
                                    <th class="px-4 py-3">DO</th>
                                    <th class="px-4 py-3">pH</th>
                                    <th class="px-4 py-3">Amonia</th>
                                    <th class="px-4 py-3">Sumber</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pond->measurements as $measurement)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-4 py-3">
                                            <a href="{{ route('admin.measurements.show', $measurement) }}"
                                                class="font-medium text-blue-700 hover:underline">
                                                {{ $measurement->measured_at->format('d/m/Y H:i') }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">{{ $measurement->fish->name }}</td>
                                        <td class="px-4 py-3">{{ $measurement->temperature_c }} °C</td>
                                        <td class="px-4 py-3">{{ $measurement->dissolved_oxygen_mg_l }} mg/L</td>
                                        <td class="px-4 py-3">{{ $measurement->ph }}</td>
                                        <td class="px-4 py-3">{{ $measurement->ammonia_mg_l }} mg/L</td>
                                        <td class="px-4 py-3"><x-admin.source-badge :source="$measurement->source" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Ikan di Kolam</h2>
                <p class="text-xs text-slate-500">Jenis ikan aktif yang ditebar pada kolam ini.</p>

                <div class="mt-4 space-y-2">
                    @forelse ($pond->fishes as $fish)
                        <div class="rounded-lg border border-slate-200 px-3 py-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <a href="{{ route('admin.fishes.show', $fish) }}"
                                        class="text-sm font-semibold text-slate-900 hover:text-blue-700">{{ $fish->name }}</a>
                                    <p class="text-xs text-slate-500">
                                        Tebar {{ $fish->pivot->stock_date ?? '-' }} ·
                                        {{ $fish->pivot->population ? number_format($fish->pivot->population) . ' ekor' : 'jumlah tidak dicatat' }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.ponds.fishes.destroy', [$pond, $fish]) }}" method="POST"
                                    onsubmit="return confirm('Keluarkan {{ $fish->name }} dari {{ $pond->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-medium text-red-600 hover:text-red-800">Keluarkan</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-slate-300 px-3 py-6 text-center text-xs text-slate-500">
                            Belum ada ikan yang ditebar.</p>
                    @endforelse
                </div>

                <form action="{{ route('admin.ponds.fishes.store', $pond) }}" method="POST"
                    class="mt-4 space-y-3 border-t border-slate-100 pt-4">
                    @csrf
                    <div>
                        <label for="fish_id" class="block text-sm font-medium text-slate-700">Jenis Ikan</label>
                        <select name="fish_id" id="fish_id"
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih ikan</option>
                            @foreach ($availableFishes as $fish)
                                <option value="{{ $fish->id }}" @selected(old('fish_id') == $fish->id)>{{ $fish->name }}</option>
                            @endforeach
                        </select>
                        @error('fish_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="stock_date" class="block text-sm font-medium text-slate-700">Tanggal Tebar</label>
                            <input type="date" name="stock_date" id="stock_date" value="{{ old('stock_date', now()->toDateString()) }}"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label for="population" class="block text-sm font-medium text-slate-700">Jumlah</label>
                            <input type="number" min="1" name="population" id="population" value="{{ old('population') }}"
                                placeholder="Ekor"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Tambahkan</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Aksi</h2>
                <form action="{{ route('admin.ponds.destroy', $pond) }}" method="POST" class="mt-3"
                    onsubmit="return confirm('Hapus {{ $pond->name }}? Seluruh pengukuran pada kolam ini ikut terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">Hapus
                        Kolam</button>
                </form>
            </div>
        </div>

    </div>

</x-admin.layout>
