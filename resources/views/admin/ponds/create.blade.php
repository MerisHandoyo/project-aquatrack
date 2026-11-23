<x-admin.layout>

    <x-admin.page-header title="Tambah Kolam" subtitle="Isi data dasar kolam. Jenis ikan dapat ditambahkan setelah kolam dibuat." />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.ponds.store') }}" method="POST" class="space-y-5">
            @include('admin.ponds._form', [
                'pond' => null,
                'method' => 'POST',
                'submitLabel' => 'Simpan Kolam',
            ])
        </form>
    </div>

</x-admin.layout>
