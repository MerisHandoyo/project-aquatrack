<x-admin.layout>

    <x-admin.page-header title="Analisis Kualitas Air"
        subtitle="Grafik perubahan parameter dari data pengukuran, beserta ringkasan kondisi terhadap acuan jenis ikan." />

    <form action="{{ route('admin.analysis') }}" method="GET"
        class="mb-4 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-5">
        <div>
            <label for="pond_id" class="sr-only">Kolam</label>
            <select name="pond_id" id="pond_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua kolam</option>
                @foreach ($ponds as $pond)
                    <option value="{{ $pond->id }}" @selected(($filters['pond_id'] ?? null) == $pond->id)>{{ $pond->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="fish_id" class="sr-only">Ikan</label>
            <select name="fish_id" id="fish_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua ikan</option>
                @foreach ($fishes as $fish)
                    <option value="{{ $fish->id }}" @selected(($filters['fish_id'] ?? null) == $fish->id)>{{ $fish->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="source" class="sr-only">Sumber</label>
            <select name="source" id="source"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua sumber</option>
                <option value="manual" @selected(($filters['source'] ?? null) === 'manual')>Manual</option>
                <option value="iot" @selected(($filters['source'] ?? null) === 'iot')>IoT</option>
                <option value="simulation" @selected(($filters['source'] ?? null) === 'simulation')>Simulasi</option>
            </select>
        </div>
        <div>
            <label for="days" class="sr-only">Periode</label>
            <select name="days" id="days"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="7" @selected($days === 7)>7 hari terakhir</option>
                <option value="30" @selected($days === 30)>30 hari terakhir</option>
                <option value="90" @selected($days === 90)>90 hari terakhir</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Terapkan</button>
            <a href="{{ route('admin.analysis') }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Data dalam periode</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($measurements->count()) }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ $days }} hari terakhir</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Data simulasi</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($measurements->where('source', 'simulation')->count()) }}</p>
            <p class="mt-1 text-xs text-slate-500">Bukan pengukuran nyata</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pengukuran terbaru</p>
            <p class="mt-2 text-lg font-bold text-slate-900">
                {{ $latest ? $latest->measured_at->format('d/m/Y H:i') : '-' }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ $latest ? $latest->pond->name . ' · ' . $latest->fish->name : 'Belum ada data' }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Kondisi terbaru</p>
            <div class="mt-2">
                @if ($evaluation)
                    <x-admin.status-badge :status="$evaluation['overall']['status']"
                        :label="$evaluation['overall']['label']" />
                @else
                    <span class="text-sm text-slate-500">Belum ada data</span>
                @endif
            </div>
            <p class="mt-2 text-xs text-slate-500">{{ $evaluation['overall']['message'] ?? '' }}</p>
        </div>
    </div>

    @if ($measurements->isEmpty())
        <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <p class="text-sm font-medium text-slate-700">Belum ada data pada filter ini.</p>
            <p class="mt-1 text-sm text-slate-500">Ubah filter atau tambahkan pengukuran terlebih dahulu.</p>
        </div>
    @else
        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @foreach (\App\Models\WaterQualityStandard::PARAMETERS as $key => $meta)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">{{ $meta['label'] }}</h2>
                            <p class="text-xs text-slate-500">{{ $meta['unit'] ?: 'Tanpa satuan' }}</p>
                        </div>
                    </div>
                    <div class="relative mt-4 h-64">
                        <canvas id="chart-{{ $key }}"></canvas>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                <h2 class="text-base font-semibold text-slate-900">Ringkasan Parameter Terbaru</h2>
                @if ($evaluation)
                    <p class="text-xs text-slate-500">
                        {{ $latest->pond->name }} · {{ $latest->fish->name }} ·
                        {{ $latest->measured_at->format('d/m/Y H:i') }}
                    </p>
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
                                @foreach ($evaluation['parameters'] as $parameter)
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
                                        <td class="px-3 py-2"><x-admin.status-badge :status="$parameter['status']"
                                                :label="$parameter['label_status']" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Acuan &amp; Sumber</h2>

                @if (! $selectedFish)
                    <p class="mt-3 text-xs leading-relaxed text-slate-500">
                        Pilih jenis ikan pada filter untuk melihat rentang acuan dan sumber yang dipakai.
                    </p>
                @elseif ($standards->isEmpty())
                    <p class="mt-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-4 text-xs leading-relaxed text-slate-600">
                        Referensi belum tersedia untuk {{ $selectedFish->name }}. Sistem tidak menampilkan status aman
                        atau bahaya tanpa acuan.
                    </p>
                @else
                    <ul class="mt-3 space-y-2 text-xs">
                        @foreach ($standards as $standard)
                            <li class="rounded-lg border border-slate-200 px-3 py-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-semibold text-slate-800">{{ $standard->parameterLabel() }}</span>
                                    @if ($standard->is_verified)
                                        <span class="text-green-700">Tervalidasi</span>
                                    @else
                                        <span class="text-yellow-700">Belum tervalidasi</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-slate-600">
                                    @if ($standard->min_value !== null && $standard->max_value !== null)
                                        {{ $standard->min_value }}–{{ $standard->max_value }}
                                    @elseif ($standard->min_value !== null)
                                        min. {{ $standard->min_value }}
                                    @else
                                        maks. {{ $standard->max_value }}
                                    @endif
                                    {{ $standard->unit }}
                                    @if ($standard->parameter === 'ammonia' && $standard->ammonia_type)
                                        ({{ \App\Models\WaterQualityStandard::AMMONIA_TYPES[$standard->ammonia_type] }})
                                    @endif
                                </p>
                                <p class="mt-1 text-slate-500">{{ $standard->source ?: 'Sumber belum dicatat' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-5">
            <h2 class="text-sm font-semibold text-blue-900">Catatan analisis</h2>
            <ul class="mt-2 list-disc space-y-1 pl-5 text-xs leading-relaxed text-blue-800">
                <li>Suhu dan pH memengaruhi proporsi amonia bebas (NH3) dari total amonia; nilai pH tinggi meningkatkan
                    proporsi NH3.</li>
                <li>Alkalinitas berfungsi sebagai penyangga perubahan pH, sehingga fluktuasi pH lebih kecil pada
                    alkalinitas yang cukup.</li>
                <li>DO dapat berbeda antara pagi dan sore karena aktivitas fotosintesis dan respirasi.</li>
                <li>Status hanya dihitung untuk parameter yang memiliki acuan dengan jenis amonia yang sama. Data
                    simulasi diberi label tersendiri.</li>
            </ul>
        </div>
    @endif

    @push('scripts')
        @if ($measurements->isNotEmpty())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (!window.Chart) {
                        return;
                    }

                    const charts = @json($charts);

                    const styles = {
                        temperature: {
                            color: '#ef4444',
                            background: 'rgba(239, 68, 68, 0.08)'
                        },
                        dissolved_oxygen: {
                            color: '#2563eb',
                            background: 'rgba(37, 99, 235, 0.08)'
                        },
                        ph: {
                            color: '#7c3aed',
                            background: 'rgba(124, 58, 237, 0.08)'
                        },
                        ammonia: {
                            color: '#f59e0b',
                            background: 'rgba(245, 158, 11, 0.08)'
                        },
                        alkalinity: {
                            color: '#0d9488',
                            background: 'rgba(13, 148, 136, 0.08)'
                        },
                    };

                    Object.entries(charts.series).forEach(function([key, series]) {
                        const canvas = document.getElementById('chart-' + key);
                        const style = styles[key];

                        if (!canvas || !style) {
                            return;
                        }

                        new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: charts.labels,
                                datasets: [{
                                    label: series.label,
                                    data: series.data,
                                    borderColor: style.color,
                                    backgroundColor: style.background,
                                    tension: 0.3,
                                    fill: true,
                                    pointRadius: 2,
                                }],
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
                                        title: {
                                            display: series.unit !== '',
                                            text: series.unit,
                                        },
                                    },
                                    x: {
                                        ticks: {
                                            maxRotation: 45,
                                            autoSkip: true,
                                            maxTicksLimit: 10,
                                        },
                                    },
                                },
                            },
                        });
                    });
                });
            </script>
        @endif
    @endpush

</x-admin.layout>
