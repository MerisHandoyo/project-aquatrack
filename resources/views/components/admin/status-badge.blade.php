@props([
    'status',
    'label' => null,
])

@php
    $styles = match ($status) {
        'aman' => 'bg-green-100 text-green-800',
        'dalam_acuan' => 'bg-yellow-100 text-yellow-800',
        'perlu_perhatian' => 'bg-orange-100 text-orange-800',
        'bahaya' => 'bg-red-100 text-red-800',
        'tidak_sebanding' => 'bg-slate-100 text-slate-600',
        'tanpa_acuan', 'tidak_diketahui' => 'bg-gray-100 text-gray-600',
        default => 'bg-blue-100 text-blue-800',
    };
@endphp

<span
    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap {{ $styles }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
    {{ $label ?? ucfirst(str_replace('_', ' ', $status)) }}
</span>
