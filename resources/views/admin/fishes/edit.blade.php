<x-admin.layout>

    <x-admin.page-header title="Ubah Jenis Ikan" :subtitle="'Perbarui data ' . $fish->name . '.'" />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.fishes.update', $fish) }}" method="POST" class="space-y-5">
            @include('admin.fishes._form', [
                'fish' => $fish,
                'method' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
            ])
        </form>
    </div>

</x-admin.layout>
