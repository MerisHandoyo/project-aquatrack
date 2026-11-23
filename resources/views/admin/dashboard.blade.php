<x-admin.layout>

    <x-admin.page-header title="Dashboard"
        subtitle="Ringkasan kolam, pengukuran terbaru, dan kondisi kualitas air budidaya ikan air tawar.">
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

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Kolam</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['ponds'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2zm0 2v2h12V5H4zm0 4v6h12V9H4z"></path>
                    </svg>
                </span>
            </div>
            <a href="{{ route('admin.ponds.index') }}"
                class="mt-4 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-800">Kelola kolam &rarr;</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Jenis Ikan Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['fishes'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 3c-1.9 0-3.6.9-4.8 2.3L2 3.5v5l3.2-1.8C6.4 8.1 8.1 9 10 9c3.3 0 6-1.3 8-3.5C16 3.8 13.3 3 10 3zM3.5 13.2C4.7 14.6 6.4 15.5 8 15.5V17h4v-1.5c1.6 0 3.3-.8 4.5-2.2L20 15.5V9.9l-3.5 2.1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
            <a href="{{ route('admin.fishes.index') }}"
                class="mt-4 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-800">Kelola ikan &rarr;</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pengukuran</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['measurements']) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm10 6H4v8h12V8z"></path>
                    </svg>
                </span>
            </div>
            <a href="{{ route('admin.history') }}"
                class="mt-4 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-800">Lihat riwayat &rarr;</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pengukuran Hari Ini</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['today'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Tren 14 Hari Terakhir</h2>
                    <p class="text-xs text-slate-500">Rata-rata suhu dan DO dari seluruh kolam.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full bg-green-50 px-2.5 py-1 font-medium text-green-700">Baik
                        {{ $summary['baik'] }}</span>
                    <span class="rounded-full bg-yellow-50 px-2.5 py-1 font-medium text-yellow-700">Perlu validasi
                        {{ $summary['perlu_validasi'] }}</span>
                    <span class="rounded-full bg-orange-50 px-2.5 py-1 font-medium text-orange-700">Peringatan
                        {{ $summary['peringatan'] }}</span>
                    <span class="rounded-full bg-red-50 px-2.5 py-1 font-medium text-red-700">Bahaya
                        {{ $summary['bahaya'] }}</span>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">Tanpa acuan
                        {{ $summary['tanpa_acuan'] }}</span>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-500">Tanpa data
                        {{ $summary['tidak_diketahui'] }}</span>
                </div>
            </div>

            @if ($trend->isEmpty())
                <p class="mt-8 rounded-lg bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                    Belum ada data pengukuran untuk ditampilkan.
                </p>
            @else
                <div class="relative mt-4 h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Kolam Perlu Perhatian</h2>
            <p class="text-xs text-slate-500">Berdasarkan pengukuran terbaru tiap kolam.</p>

            <div class="mt-4 space-y-3">
                @forelse ($attention as $item)
                    <a href="{{ route('admin.ponds.show', $item['pond']) }}"
                        class="block rounded-lg border border-slate-200 px-3 py-3 hover:border-blue-300 hover:bg-blue-50/50">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-slate-900">{{ $item['pond']->name }}</span>
                            <x-admin.status-badge :status="$item['overall']['status']" :label="$item['overall']['label']" />
                        </div>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $item['overall']['message'] }} &middot; {{ $item['measurement']->measured_at->format('d/m/Y H:i') }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-6 text-center">
                        <p class="text-sm font-medium text-green-800">Tidak ada kolam yang memerlukan perhatian.</p>
                        <p class="mt-1 text-xs text-green-700">Semua kolam berstatus baik atau belum memiliki acuan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 rounded-lg bg-slate-50 px-3 py-3 text-xs leading-relaxed text-slate-500">
                Status mengikuti standar per jenis ikan. Acuan yang belum tervalidasi ditandai kuning, dan data
                simulasi diberi label tersendiri pada tabel.
            </div>
        </div>
    </div>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Pengukuran Terbaru</h2>
                <p class="text-xs text-slate-500">Enam catatan terakhir dari seluruh kolam.</p>
            </div>
            <a href="{{ route('admin.measurements.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat semua</a>
        </div>

        @if ($latestMeasurements->isEmpty())
            <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pengukuran.</p>
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
                            <th class="px-4 py-3">Sumber</th>
                            <th class="px-4 py-3">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($latestMeasurements as $measurement)
                            @php $overall = $latestEvaluations[$measurement->id]; @endphp
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
                                <td class="px-4 py-3">{{ $measurement->ammonia_mg_l }} mg/L</td>
                                <td class="px-4 py-3"><x-admin.source-badge :source="$measurement->source" /></td>
                                <td class="px-4 py-3"><x-admin.status-badge :status="$overall['status']"
                                        :label="$overall['label']" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const trend = @json($trend);
                const canvas = document.getElementById('trendChart');

                if (!canvas || trend.length === 0 || !window.Chart) {
                    return;
                }

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: trend.map((item) => item.label),
                        datasets: [{
                                label: 'Suhu (°C)',
                                data: trend.map((item) => item.temperature),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.08)',
                                tension: 0.35,
                                fill: true,
                                yAxisID: 'y',
                            },
                            {
                                label: 'DO (mg/L)',
                                data: trend.map((item) => item.oxygen),
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                                tension: 0.35,
                                fill: true,
                                yAxisID: 'y1',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Suhu (°C)',
                                },
                            },
                            y1: {
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                title: {
                                    display: true,
                                    text: 'DO (mg/L)',
                                },
                            },
                        },
                    },
                });
            });
        </script>
    @endpush

</x-admin.layout>
