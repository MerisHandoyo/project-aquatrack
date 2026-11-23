@props([
    'title',
    'subtitle' => null,
])

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 max-w-3xl text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>

    @if (trim((string) $slot) !== '')
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
