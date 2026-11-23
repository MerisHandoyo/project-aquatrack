<x-admin.layout>

    <x-admin.page-header title="Tambah Pengukuran"
        subtitle="Catat hasil pengukuran manual. Pastikan jenis amonia sesuai dengan yang dibaca alat ukur." />

    <div class="max-w-4xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.measurements.store') }}" method="POST" class="space-y-6">
            @include('admin.measurements._form', [
                'measurement' => $measurement,
                'method' => 'POST',
                'submitLabel' => 'Simpan Pengukuran',
            ])
        </form>
    </div>

</x-admin.layout>
