<x-admin.layout>

    <x-admin.page-header title="Ubah Standar Kualitas Air"
        :subtitle="'Standar ' . $standard->parameterLabel() . ' untuk ' . $fish->name . '.'" />

    <div class="max-w-3xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <form action="{{ route('admin.fishes.standards.update', [$fish, $standard]) }}" method="POST" class="space-y-5">
            @include('admin.fishes.standards._form', [
                'standard' => $standard,
                'method' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
            ])
        </form>
    </div>

</x-admin.layout>
