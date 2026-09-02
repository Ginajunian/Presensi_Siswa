<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        @if ($kelasList->isEmpty())
            <x-card>
                @if (auth()->user()->hasRole('admin'))
                    <p class="text-sm text-amber-700">
                        Belum ada data kelas di sistem. Tambahkan kelas terlebih dahulu di menu Data Master.
                    </p>
                @else
                    <p class="text-sm text-gray-600 mb-3">
                        Dashboard statistik ini khusus untuk wali kelas. Anda tetap dapat melakukan presensi siswa seperti
                        biasa.
                    </p>
                    <x-link-button :href="route('presensi.index')">Mulai Presensi &rarr;</x-link-button>
                @endif
            </x-card>
        @else

            {{-- Shortcut cepat --}}
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('presensi.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition">
                    Mulai Presensi
                </a>
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('siswa.create') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                        + Tambah Siswa
                    </a>
                @endif
            </div>

            {{-- Row 1: Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <x-card>
                    <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <circle cx="12" cy="8" r="3.25" />
                            <path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mt-3">{{ $totalSiswaAktif }}</p>
                    <p class="text-sm text-gray-400">Siswa Aktif</p>
                </x-card>

                <x-card>
                    <div class="flex items-center justify-between">
                        <div class="w-11 h-11 rounded-xl bg-brand-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <x-trend-badge :value="$trendKehadiran" />
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mt-3">{{ $hadirHariIni }}</p>
                    <p class="text-sm text-gray-400">Hadir Hari Ini</p>
                </x-card>

                <x-card>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <circle cx="12" cy="12" r="8.25" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mt-3">{{ $izinHariIni + $sakitHariIni }}</p>
                    <p class="text-sm text-gray-400">Izin / Sakit</p>
                </x-card>

                <x-card>
                    <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <circle cx="12" cy="12" r="8.25" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 9.5 5 5m0-5-5 5" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mt-3">{{ $alpaHariIni }}</p>
                    <p class="text-sm text-gray-400">Alpa Hari Ini</p>
                </x-card>
            </div>

            {{-- Grid: kiri (Kehadiran, full height) + kanan (Tren di atas, Top Alpa di bawah) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Kiri: Kehadiran Hari Ini — row-span-2 bikin dia otomatis setinggi 2 card di kanan --}}
                <div class="lg:row-span-2">
                    <x-card title="Kehadiran Hari Ini" class="h-full flex flex-col">
                        <div class="flex-1 flex flex-col items-center justify-center">
                            <div class="relative flex flex-col items-center">
                                <canvas id="chart-gauge" class="max-w-[240px] w-full"></canvas>
                                <div class="absolute" style="top: 58%;">
                                    <p class="text-3xl font-bold text-gray-800 text-center">{{ $persentaseHariIni }}%</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <x-trend-badge :value="$trendKehadiran" />
                            </div>
                        </div>

                        <div class="space-y-3 border-t border-gray-100 pt-4 mt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-gray-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span> Hadir
                                </span>
                                <span class="font-medium text-gray-700">{{ $hadirHariIni }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-gray-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Izin/Sakit
                                </span>
                                <span class="font-medium text-gray-700">{{ $izinHariIni + $sakitHariIni }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-gray-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Alpa
                                </span>
                                <span class="font-medium text-gray-700">{{ $alpaHariIni }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-gray-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span> Belum Tercatat
                                </span>
                                <span class="font-medium text-gray-700">{{ $belumTercatat }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- Kanan atas: Tren 7 hari --}}
                <div class="lg:col-span-2">
                    <x-card title="Tren Kehadiran 7 Hari Terakhir" class="h-full">
                        <canvas id="chart-tren" height="90"></canvas>
                    </x-card>
                </div>

                {{-- Kanan bawah: Top Alpa --}}
                <div class="lg:col-span-2">
                    <x-card title="Siswa dengan Alpa Terbanyak (Bulan Ini)" :padding="false" class="h-full">
                        @if ($topAlpa->isEmpty())
                            <p class="text-sm text-gray-400 p-5">Belum ada catatan alpa bulan ini.</p>
                        @else
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-gray-400 text-xs uppercase tracking-wide">
                                        <th class="px-5 py-3 font-medium">Nama</th>
                                        <th class="px-5 py-3 font-medium text-center">Jumlah Alpa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topAlpa as $item)
                                        <tr class="border-t border-gray-100">
                                            <td class="px-5 py-3 text-gray-700">{{ $item->siswa->nama }}</td>
                                            <td class="px-5 py-3 text-center">
                                                <x-badge color="red">{{ $item->total }}</x-badge>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </x-card>
                </div>
            </div>
        @endif
    </div>

    @if ($kelasList->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // --- Chart garis: tren 7 hari ---
            new Chart(document.getElementById('chart-tren'), {
                type: 'line',
                data: {
                    labels: @json($tren->pluck('label')),
                    datasets: [{
                        label: '% Kehadiran',
                        data: @json($tren->pluck('persentase')),
                        borderColor: '#379A5E',
                        backgroundColor: 'rgba(55, 154, 94, 0.08)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#379A5E',
                    }]
                },
                options: {
                    scales: {
                        y: { min: 0, max: 100, grid: { color: '#F3F4F6' } },
                        x: { grid: { display: false } },
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // --- Chart gauge (setengah donat): persentase kehadiran hari ini ---
            const persentase = {{ $persentaseHariIni }};
            new Chart(document.getElementById('chart-gauge'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [persentase, 100 - persentase],
                        backgroundColor: ['#379A5E', '#F0F9F4'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    circumference: 180,
                    rotation: 270,
                    cutout: '75%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        </script>
    @endif
</x-app-layout>