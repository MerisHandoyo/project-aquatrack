<x-admin.layout>

    <x-admin.page-header title="Tambah Jenis Ikan" subtitle="Standar kualitas air dapat ditambahkan setelah data ikan disimpan." />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.fishes.store') }}" method="POST" class="space-y-5">
            @include('admin.fishes._form', [
                'fish' => null,
                'method' => 'POST',
                'submitLabel' => 'Simpan Ikan',
            ])
        </form>
    </div>

</x-admin.layout>
