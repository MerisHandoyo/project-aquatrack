@props([
    'href' => '#',
    'label' => '',
    'icon' => '',
])

@php
    $path = trim(parse_url($href, PHP_URL_PATH) ?? $href, '/');
    $active = $path !== '' && request()->is($path, $path . '/*');
@endphp

<li>
    <a href="{{ $href }}" @if ($active) aria-current="page" @endif
        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors {{ $active ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
        <span
            class="flex h-5 w-5 items-center justify-center {{ $active ? 'text-blue-600' : 'text-slate-400' }}">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                {!! $icon !!}
            </svg>
        </span>
        <span>{{ $label }}</span>
    </a>
</li>
