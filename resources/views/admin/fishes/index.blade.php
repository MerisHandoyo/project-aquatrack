<x-admin.layout>

    <x-admin.page-header title="Data Ikan" subtitle="Daftar jenis ikan yang dibudidayakan beserta ketersediaan standar kualitas airnya.">
        <form action="{{ route('admin.fishes.index') }}" method="GET" class="relative">
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
        <a href="{{ route('admin.fishes.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Ikan
        </a>
    </x-admin.page-header>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($fishes->isEmpty())
            <p class="px-6 py-16 text-center text-sm text-slate-500">Tidak ada jenis ikan yang cocok dengan pencarian.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama Ikan</th>
                            <th class="px-5 py-3">Nama Ilmiah</th>
                            <th class="px-5 py-3">Standar Acuan</th>
                            <th class="px-5 py-3">Pengukuran</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($fishes as $fish)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.fishes.show', $fish) }}"
                                        class="font-semibold text-slate-900 hover:text-blue-700">{{ $fish->name }}</a>
                                </td>
                                <td class="px-5 py-3 italic text-slate-500">{{ $fish->scientific_name ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    @if ($fish->standards_count > 0)
                                        <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                            {{ $fish->standards_count }} parameter
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                                            Belum tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">{{ number_format($fish->measurements_count) }}</td>
                                <td class="px-5 py-3">
                                    @if ($fish->is_active)
                                        <span class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.fishes.edit', $fish) }}"
                                            class="font-medium text-slate-600 hover:text-slate-900">Ubah</a>
                                        <form action="{{ route('admin.fishes.destroy', $fish) }}" method="POST"
                                            onsubmit="return confirm('Hapus {{ $fish->name }}?')">
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

    <div class="mt-4">{{ $fishes->links() }}</div>

</x-admin.layout>
