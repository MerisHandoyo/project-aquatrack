@props(['source'])

@php
    $styles = match ($source) {
        'manual' => 'bg-blue-50 text-blue-700 border-blue-100',
        'iot' => 'bg-purple-50 text-purple-700 border-purple-100',
        'simulation' => 'bg-amber-50 text-amber-700 border-amber-100',
        default => 'bg-slate-50 text-slate-600 border-slate-200',
    };

    $labels = [
        'manual' => 'Manual',
        'iot' => 'IoT',
        'simulation' => 'Simulasi',
    ];
@endphp

<span class="inline-flex rounded-full border px-2 py-0.5 text-xs font-medium {{ $styles }}">
    {{ $labels[$source] ?? $source }}
</span>
