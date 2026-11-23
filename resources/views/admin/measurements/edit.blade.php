<x-admin.layout>

    <x-admin.page-header title="Ubah Pengukuran"
        :subtitle="'Perbarui catatan ' . $measurement->measured_at->format('d/m/Y H:i') . ' untuk ' . $measurement->pond->name . '.'" />

    <div class="max-w-4xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.measurements.update', $measurement) }}" method="POST" class="space-y-6">
            @include('admin.measurements._form', [
                'measurement' => $measurement,
                'method' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
            ])
        </form>
    </div>

</x-admin.layout>
