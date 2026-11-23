<x-admin.layout>

    <x-admin.page-header :title="$fish->name" :subtitle="$fish->scientific_name ?: 'Nama ilmiah belum diisi'">
        <a href="{{ route('admin.fishes.standards.create', $fish) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Standar
        </a>
        <a href="{{ route('admin.fishes.edit', $fish) }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ubah</a>
    </x-admin.page-header>

    <div class="grid gap-4 lg:grid-cols-3">

        <div class="space-y-4 lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Standar Kualitas Air</h2>
                        <p class="text-xs text-slate-500">Nilai acuan dipakai untuk menilai hasil pengukuran ikan ini.</p>
                    </div>
                    <a href="{{ route('admin.fishes.standards.create', $fish) }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-800">Tambah</a>
                </div>

                @if ($standards->isEmpty())
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm font-medium text-slate-700">Referensi belum tersedia.</p>
                        <p class="mx-auto mt-1 max-w-md text-xs text-slate-500">
                            Sistem akan menampilkan status "Referensi belum tersedia" pada analisis, bukan menebak batas
                            normal. Tambahkan standar dari sumber yang dapat dipertanggungjawabkan.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Parameter</th>
                                    <th class="px-5 py-3">Rentang Acuan</th>
                                    <th class="px-5 py-3">Sumber</th>
                                    <th class="px-5 py-3">Validasi</th>
                                    <th class="px-5 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($standards as $standard)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3">
                                            <p class="font-medium text-slate-900">{{ $standard->parameterLabel() }}</p>
                                            @if ($standard->parameter === 'ammonia' && $standard->ammonia_type)
                                                <p class="text-xs text-slate-500">
                                                    {{ \App\Models\WaterQualityStandard::AMMONIA_TYPES[$standard->ammonia_type] }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            @if ($standard->min_value !== null && $standard->max_value !== null)
                                                {{ $standard->min_value }}–{{ $standard->max_value }}
                                            @elseif ($standard->min_value !== null)
                                                min. {{ $standard->min_value }}
                                            @else
                                                maks. {{ $standard->max_value }}
                                            @endif
                                            <span class="text-xs text-slate-500">{{ $standard->unit }}</span>
                                        </td>
                                        <td class="max-w-xs px-5 py-3 text-xs">
                                            {{ $standard->source ?: 'Sumber belum dicatat' }}
                                        </td>
                                        <td class="px-5 py-3">
                                            @if ($standard->is_verified)
                                                <span
                                                    class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Tervalidasi</span>
                                            @else
                                                <span
                                                    class="rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-medium text-yellow-700">Belum
                                                    tervalidasi</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('admin.fishes.standards.edit', [$fish, $standard]) }}"
                                                    class="font-medium text-slate-600 hover:text-slate-900">Ubah</a>
                                                <form
                                                    action="{{ route('admin.fishes.standards.destroy', [$fish, $standard]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus standar {{ $standard->parameterLabel() }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="font-medium text-red-600 hover:text-red-800">Hapus</button>
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

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Pengukuran Terakhir</h2>
                    <p class="text-xs text-slate-500">Sepuluh catatan terakhir untuk ikan ini.</p>
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
                                    <th class="px-4 py-3">Suhu</th>
                                    <th class="px-4 py-3">DO</th>
                                    <th class="px-4 py-3">pH</th>
                                    <th class="px-4 py-3">Amonia</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($latestMeasurements as $measurement)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-4 py-3">
                                            <a href="{{ route('admin.measurements.show', $measurement) }}"
                                                class="font-medium text-blue-700 hover:underline">
                                                {{ $measurement->measured_at->format('d/m/Y H:i') }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">{{ $measurement->pond->name }}</td>
                                        <td class="px-4 py-3">{{ $measurement->temperature_c }} °C</td>
                                        <td class="px-4 py-3">{{ $measurement->dissolved_oxygen_mg_l }} mg/L</td>
                                        <td class="px-4 py-3">{{ $measurement->ph }}</td>
                                        <td class="px-4 py-3">{{ $measurement->ammonia_mg_l }} mg/L</td>
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
                <h2 class="text-base font-semibold text-slate-900">Profil Ikan</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Nama Ilmiah</dt>
                        <dd class="mt-0.5 italic text-slate-700">{{ $fish->scientific_name ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                        <dd class="mt-0.5">
                            @if ($fish->is_active)
                                <span class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Nonaktif</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Jumlah Standar</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ $standards->count() }} parameter</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Total Pengukuran</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900">{{ number_format($fish->measurements->count()) }}</dd>
                    </div>
                </dl>
                @if ($fish->description)
                    <p class="mt-4 border-t border-slate-100 pt-4 text-sm leading-relaxed text-slate-600">
                        {{ $fish->description }}
                    </p>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Catatan Referensi</h2>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">
                    Standar yang belum tervalidasi tetap ditampilkan, tetapi statusnya dibedakan agar tidak disimpulkan
                    sebagai kepastian ilmiah. Verifikasi nilai acuan bersama penyuluh atau balai perikanan sebelum
                    dipakai untuk keputusan budidaya.
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Aksi</h2>
                <form action="{{ route('admin.fishes.destroy', $fish) }}" method="POST" class="mt-3"
                    onsubmit="return confirm('Hapus {{ $fish->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">Hapus
                        Ikan</button>
                </form>
            </div>
        </div>

    </div>

</x-admin.layout>
