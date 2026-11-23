<x-admin.layout>

    <x-admin.page-header title="Tambah Standar Kualitas Air"
        :subtitle="'Standar untuk ' . $fish->name . '. Nilai akan dipakai saat menilai hasil pengukuran.'" />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.fishes.standards.store', $fish) }}" method="POST" class="space-y-5">
            @include('admin.fishes.standards._form', [
                'standard' => null,
                'method' => 'POST',
                'submitLabel' => 'Simpan Standar',
            ])
        </form>
    </div>

</x-admin.layout>
