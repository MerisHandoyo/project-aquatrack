<x-admin.layout>

    <x-admin.page-header title="Ubah Kolam" :subtitle="'Perbarui data ' . $pond->name . ' (' . $pond->code . ').'" />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.ponds.update', $pond) }}" method="POST" class="space-y-5">
            @include('admin.ponds._form', [
                'pond' => $pond,
                'method' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
            ])
        </form>
    </div>

</x-admin.layout>
