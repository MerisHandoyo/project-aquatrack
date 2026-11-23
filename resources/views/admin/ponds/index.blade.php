<x-admin.layout>

    <x-admin.page-header title="Data Kolam" subtitle="Kelola kolam budidaya beserta lokasi, volume, dan jenis ikan yang ditebar.">
        <form action="{{ route('admin.ponds.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ $searchTerm }}" placeholder="{{ $search }}"
                class="w-full rounded-lg border border-slate-300 bg-white py-2 pe-10 ps-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:w-72">
            <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-blue-600">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </form>
        <a href="{{ route('admin.ponds.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Kolam
        </a>
    </x-admin.page-header>

    @if ($ponds->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <p class="text-sm font-medium text-slate-700">Belum ada kolam.</p>
            <p class="mt-1 text-sm text-slate-500">Tambahkan kolam pertama untuk mulai mencatat pengukuran.</p>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($ponds as $pond)
                @php $overall = $evaluations[$pond->id] ?? null; @endphp
                <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span
                                class="inline-flex rounded-md bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700">{{ $pond->code }}</span>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $pond->name }}</h2>
                            <p class="text-sm text-slate-500">{{ $pond->location ?? 'Lokasi belum diisi' }}</p>
                        </div>
                        @if ($overall)
                            <x-admin.status-badge :status="$overall['status']" :label="$overall['label']" />
                        @else
                            <span
                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Belum
                                ada data</span>
                        @endif
                    </div>

                    <dl class="mt-4 grid grid-cols-3 gap-2 rounded-lg bg-slate-50 px-3 py-3 text-center">
                        <div>
                            <dt class="text-xs text-slate-500">Volume</dt>
                            <dd class="text-sm font-semibold text-slate-900">
                                {{ $pond->volume_m3 !== null ? $pond->volume_m3 . ' m³' : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Kedalaman</dt>
                            <dd class="text-sm font-semibold text-slate-900">
                                {{ $pond->depth_m !== null ? $pond->depth_m . ' m' : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Pengukuran</dt>
                            <dd class="text-sm font-semibold text-slate-900">{{ $pond->measurements_count }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @forelse ($pond->fishes as $fish)
                            <span
                                class="rounded-full border border-teal-100 bg-teal-50 px-2.5 py-0.5 text-xs font-medium text-teal-700">{{ $fish->name }}</span>
                        @empty
                            <span class="text-xs text-slate-500">Belum ada jenis ikan.</span>
                        @endforelse
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <a href="{{ route('admin.ponds.show', $pond) }}"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-800">Detail</a>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('admin.ponds.edit', $pond) }}"
                                class="font-medium text-slate-600 hover:text-slate-900">Ubah</a>
                            <form action="{{ route('admin.ponds.destroy', $pond) }}" method="POST"
                                onsubmit="return confirm('Hapus {{ $pond->name }}? Seluruh pengukuran pada kolam ini ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $ponds->links() }}</div>
    @endif

</x-admin.layout>
