<aside id="drawer-navigation"
    class="fixed top-16 left-0 z-30 h-[calc(100vh-4rem)] w-64 -translate-x-full border-r border-slate-200 bg-white transition-transform sm:translate-x-0"
    aria-label="Menu utama">

    <div class="flex h-full flex-col overflow-y-auto px-3 py-5">

        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Menu Utama</p>
        <ul class="space-y-1">
            <x-admin.menu-item href="/admin/dashboard" label="Dashboard" icon='
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                ' />
        </ul>

        <p class="px-3 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Manajemen Budidaya</p>
        <ul class="space-y-1">
            <x-admin.menu-item href="/admin/ponds" label="Data Kolam" icon='
                    <path d="M4 3h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2zm0 2v2h12V5H4zm0 4v6h12V9H4z"></path>
                    <path d="M5 13c1.5-1 3-1 4.5 0s3 1 4.5 0" stroke="currentColor" stroke-width="1.4" fill="none"
                        stroke-linecap="round"></path>
                ' />
            <x-admin.menu-item href="/admin/fishes" label="Data Ikan" icon='
                    <path fill-rule="evenodd"
                        d="M10 3c-1.9 0-3.6.9-4.8 2.3L2 3.5v5l3.2-1.8C6.4 8.1 8.1 9 10 9c3.3 0 6-1.3 8-3.5C16 3.8 13.3 3 10 3zM3.5 13.2C4.7 14.6 6.4 15.5 8 15.5V17h4v-1.5c1.6 0 3.3-.8 4.5-2.2L20 15.5V9.9l-3.5 2.1z"
                        clip-rule="evenodd"></path>
                ' />
        </ul>

        <p class="px-3 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Monitoring</p>
        <ul class="space-y-1">
            <x-admin.menu-item href="/admin/measurements" label="Pengukuran" icon='
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm10 6H4v8h12V8z"></path>
                    <path d="M7 11h6M7 14h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"></path>
                ' />
            <x-admin.menu-item href="/admin/history" label="Riwayat" icon='
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd"></path>
                ' />
            <x-admin.menu-item href="/admin/analysis" label="Analisis" icon='
                    <path d="M3 3a1 1 0 011-1h1v16h13a1 1 0 110 2H4a1 1 0 01-1-1V3z"></path>
                    <path d="M7 12l3-4 3 2 4-6" stroke="currentColor" stroke-width="1.6" fill="none"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                ' />
        </ul>

        <div class="mt-auto rounded-xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-500">
            <p class="font-semibold text-slate-700">Catatan</p>
            <p class="mt-1">Data bertanda <span
                    class="rounded bg-amber-100 px-1.5 py-0.5 font-medium text-amber-700">Simulasi</span> bukan
                pengukuran nyata. Integrasi sensor IoT belum aktif.</p>
        </div>

    </div>

</aside>
