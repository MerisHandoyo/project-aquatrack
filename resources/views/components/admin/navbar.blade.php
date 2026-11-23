<nav
    class="fixed top-0 left-0 right-0 z-40 h-16 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6">

        <div class="flex items-center gap-3">
            <button type="button" data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
                aria-controls="drawer-navigation"
                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:hidden">
                <span class="sr-only">Buka menu</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 2c2.5 2.8 5 5.9 5 8.7A5 5 0 015 10.7C5 7.9 7.5 4.8 10 2zm0 12a3.3 3.3 0 003.3-3.3c0-1.2-.8-2.8-2-4.3-.3 1.4-1 2.7-2.3 3.7-1.1.9-1.8 1.9-1.8 2.9A3.3 3.3 0 0010 14z"
                            clip-rule="evenodd"></path>
                    </svg>
                </span>
                <span class="leading-tight">
                    <span class="block text-lg font-bold tracking-tight text-slate-900">AquaTrack</span>
                    <span class="hidden text-xs text-slate-500 sm:block">Monitoring Kualitas Air Tawar</span>
                </span>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <span
                class="hidden rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 md:inline-flex">
                Tahap 1 &middot; Input manual
            </span>
            <span class="text-sm text-slate-500">{{ now()->translatedFormat('d M Y') }}</span>
        </div>

    </div>
</nav>
