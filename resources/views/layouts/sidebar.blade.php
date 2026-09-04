<aside x-cloak
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-700 border-r border-gray-100 transition-transform duration-200 ease-in-out sm:!translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="h-16 flex items-center gap-2 px-6 bg-brand-700">
        <img src="{{ asset('img/:Logo_MTs.png') }}" alt="Logo"
            style="width: 40px; height: auto; margin-right: 10px; bg-white rounded-full;">
        <span class="font-bold text-white">{{ config('app.name', 'Presensi Siswa') }}</span>
    </div>

    <nav class="px-3 py-4 space-y-1 overflow-y-auto" style="height: calc(100vh - 4rem);">

        @php
            $item = fn($active) => 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition '
                . ($active ? 'bg-white text-brand-800' : 'text-brand-100 hover:bg-white/10 hover:text-brand-300');
        @endphp
        @php
            $bisaLihatLaporan = auth()->user()->hasRole('admin') || auth()->user()->isWaliKelas();
        @endphp

        <a href="{{ route('dashboard') }}" class="{{ $item(request()->routeIs('dashboard')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('presensi.index') }}" class="{{ $item(request()->routeIs('presensi.*')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 8V5a1 1 0 0 1 1-1h3M4 16v3a1 1 0 0 0 1 1h3M20 8V5a1 1 0 0 0-1-1h-3M20 16v3a1 1 0 0 1-1 1h-3" />
                <rect x="9" y="9" width="6" height="6" rx="1" />
            </svg>
            Presensi
        </a>
        
        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('siswa.qr-massal') }}" class="{{ $item(request()->routeIs('siswa.qr-massal')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <rect x="4" y="4" width="6" height="6" rx="1" />
                    <rect x="14" y="4" width="6" height="6" rx="1" />
                    <rect x="4" y="14" width="6" height="6" rx="1" />
                    <path stroke-linecap="round" d="M14 15h3m0 0h3m-3 0v3m0-3v-3" />
                </svg>
                Generate QR
            </a>
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-brand-300 uppercase tracking-wide">Data Master</p>

            <a href="{{ route('siswa.index') }}"
                class="{{ $item(request()->routeIs('siswa.*') && !request()->routeIs('siswa.qr-massal')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="3.25" />
                    <path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                </svg>
                Siswa
            </a>

            <a href="{{ route('guru.index') }}" class="{{ $item(request()->routeIs('guru.*')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <rect x="4" y="5" width="16" height="14" rx="2" />
                    <circle cx="9" cy="10.5" r="2" />
                    <path stroke-linecap="round" d="M6 16c.5-2 2-3 3-3s2.5 1 3 3" />
                    <path stroke-linecap="round" d="M14 9h4M14 13h4" />
                </svg>
                Guru
            </a>

            <a href="{{ route('kelas.index') }}" class="{{ $item(request()->routeIs('kelas.*')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <rect x="5" y="3" width="14" height="18" rx="1" />
                    <path stroke-linecap="round" d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1" />
                    <path stroke-linecap="round" d="M10 21v-4h4v4" />
                </svg>
                Kelas
            </a>
        @endif

        @if ($bisaLihatLaporan)
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-brand-300 uppercase tracking-wide">Laporan</p>

            <a href="{{ route('laporan.harian') }}" class="{{ $item(request()->routeIs('laporan.harian')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" d="M5 21V10M12 21V4M19 21v-7" />
                </svg>
                Rekap Harian
            </a>

            <a href="{{ route('laporan.bulanan') }}" class="{{ $item(request()->routeIs('laporan.bulanan')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" d="M5 21V10M12 21V4M19 21v-7" />
                </svg>
                Rekap Bulanan
            </a>
        @endif
        @if (auth()->user()->hasRole('admin'))
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-brand-300 uppercase tracking-wide">Lainnya</p>

            <a href="{{ route('pengaturan.edit') }}" class="{{ $item(request()->routeIs('pengaturan.*')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" d="M4 6h10M18 6h2M4 12h2M10 12h10M4 18h14" />
                    <circle cx="15" cy="6" r="2" />
                    <circle cx="7" cy="12" r="2" />
                    <circle cx="17" cy="18" r="2" />
                </svg>
                Pengaturan
            </a>
            <a href="{{ route('admin-akun.index') }}" class="{{ $item(request()->routeIs('admin-akun.*')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="3.25" />
                    <path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                    <path stroke-linecap="round" d="M18 8h3m-1.5-1.5v3" />
                </svg>
                Kelola Admin
            </a>
        @endif
    </nav>
</aside>