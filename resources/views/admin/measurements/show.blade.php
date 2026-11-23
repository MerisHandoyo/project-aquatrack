<x-admin.layout>

    <x-admin.page-header title="Detail Pengukuran"
        :subtitle="$measurement->pond->name . ' · ' . $measurement->fish->name . ' · ' . $measurement->measured_at->format('d/m/Y H:i')">
        <a href="{{ route('admin.measurements.edit', $measurement) }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ubah</a>
        <a href="{{ route('admin.measurements.index') }}"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Kembali</a>
    </x-admin.page-header>

    @if ($measurement->isSimulation())
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <span class="font-semibold">Data simulasi.</span> Catatan ini dibuat untuk pengembangan awal, bukan hasil
            pengukuran lapangan.
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Ringkasan Kondisi</h2>
                <p class="text-xs text-slate-500">Dibandingkan dengan standar {{ $measurement->fish->name }}.</p>
            </div>
            <div class="flex items-center gap-2">
                <x-admin.source-badge :source="$measurement->source" />
                <x-admin.status-badge :status="$evaluation['overall']['status']"
                    :label="$evaluation['overall']['label']" />
            </div>
        </div>
        <p class="mt-3 text-sm text-slate-600">{{ $evaluation['overall']['message'] }}</p>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($evaluation['parameters'] as $parameter)
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-medium text-slate-600">{{ $parameter['label'] }}</p>
                    <x-admin.status-badge :status="$parameter['status']" :label="$parameter['label_status']" />
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900">
                    {{ $parameter['value'] }}
                    <span class="text-sm font-medium text-slate-500">{{ $parameter['unit'] }}</span>
                </p>

                <div class="mt-2 text-xs text-slate-500">
                    @if ($parameter['standard'])
                        Acuan:
                        @if ($parameter['standard']['min'] !== null && $parameter['standard']['max'] !== null)
                            {{ $parameter['standard']['min'] }}–{{ $parameter['standard']['max'] }}
                        @elseif ($parameter['standard']['min'] !== null)
                            min. {{ $parameter['standard']['min'] }}
                        @else
                            maks. {{ $parameter['standard']['max'] }}
                        @endif
                        {{ $parameter['standard']['unit'] }}
                        @if ($parameter['key'] === 'ammonia' && $parameter['standard']['ammonia_type'])
                            ({{ \App\Models\WaterQualityStandard::AMMONIA_TYPES[$parameter['standard']['ammonia_type']] }})
                        @endif
                    @else
                        Acuan: belum tersedia
                    @endif
                </div>

                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $parameter['message'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="text-base font-semibold text-slate-900">Informasi Catatan</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Kolam</dt>
                    <dd class="mt-1 text-sm font-medium">
                        <a href="{{ route('admin.ponds.show', $measurement->pond) }}"
                            class="text-blue-700 hover:underline">{{ $measurement->pond->name }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Jenis Ikan</dt>
                    <dd class="mt-1 text-sm font-medium">
                        <a href="{{ route('admin.fishes.show', $measurement->fish) }}"
                            class="text-blue-700 hover:underline">{{ $measurement->fish->name }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Waktu Pengukuran</dt>
                    <dd class="mt-1 text-sm font-medium">{{ $measurement->measured_at->translatedFormat('l, d F Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Jenis Amonia</dt>
                    <dd class="mt-1 text-sm font-medium">
                        {{ \App\Models\WaterQualityStandard::AMMONIA_TYPES[$measurement->ammonia_type] }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Keterangan</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $measurement->notes ?: '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="space-y-4">
            @if ($nh3Estimate)
                <div class="rounded-xl border border-purple-200 bg-purple-50 p-5">
                    <h2 class="text-sm font-semibold text-purple-900">Estimasi NH3 dari TAN</h2>
                    <p class="mt-2 text-xs leading-relaxed text-purple-800">
                        Fraksi NH3 ≈ {{ number_format($nh3Estimate['fraction'] * 100, 2) }}% dari TAN pada pH
                        {{ $measurement->ph }} dan suhu {{ $measurement->temperature_c }} °C.
                    </p>
                    <p class="mt-2 text-xl font-bold text-purple-900">
                        {{ number_format($nh3Estimate['value'], 4) }} mg/L
                    </p>
                    <p class="mt-2 text-xs text-purple-700">
                        Estimasi memakai persamaan Emerson et al. (1975). Hasil ini perkiraan, bukan pengukuran langsung
                        NH3.
                    </p>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Sumber Acuan</h2>
                @if ($measurement->fish->standards->isEmpty())
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">
                        Jenis ikan ini belum memiliki standar di sistem, sehingga status tidak dapat disimpulkan.
                    </p>
                @else
                    <ul class="mt-3 space-y-2 text-xs text-slate-600">
                        @foreach ($measurement->fish->standards->pluck('source')->filter()->unique() as $source)
                            <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $source }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-xs leading-relaxed text-slate-500">
                        Beberapa acuan mungkin belum tervalidasi. Periksa halaman
                        <a href="{{ route('admin.fishes.show', $measurement->fish) }}"
                            class="font-medium text-blue-700 hover:underline">detail ikan</a> untuk status validasinya.
                    </p>
                @endif
            </div>

            <form action="{{ route('admin.measurements.destroy', $measurement) }}" method="POST"
                onsubmit="return confirm('Hapus pengukuran ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">Hapus
                    Pengukuran</button>
            </form>
        </div>
    </div>

</x-admin.layout>
