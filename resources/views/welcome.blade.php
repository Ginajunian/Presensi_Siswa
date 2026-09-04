<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Presensi Siswa') }} — Presensi Siswa Berbasis QR Code</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes scan-sweep {

            0%,
            100% {
                top: 8%;
                opacity: 0;
            }

            12% {
                opacity: 1;
            }

            50% {
                top: 82%;
                opacity: 1;
            }

            88% {
                opacity: 1;
            }
        }

        .scan-beam {
            animation: scan-sweep 3.2s ease-in-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .scan-beam {
                animation: none;
                opacity: 0.5;
                top: 50%;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-700 bg-white">

    {{-- Navbar --}}
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/Logo_MTs.png') }}" alt="Logo"
                    style="width: 40px; height: auto; margin-right: 10px; bg-white rounded-full;">
                <span class="font-semibold text-gray-800">{{ config('app.name', 'Presensi Siswa') }}</span>
            </div>

            <!-- <a href="{{ route('login') }}"
                class="inline-flex items-center px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition">
                Masuk ke Sistem
            </a> -->
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-brand-50/60 to-white -z-10"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-medium mb-4">
                    Absenan &middot; QR Code
                </span>

                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                    Presensi siswa,<br>
                    selesai dalam <span class="text-brand-600">sekali scan.</span>
                </h1>

                <p class="mt-5 text-gray-500 text-lg max-w-md">
                    Ganti buku absen dan rekap manual dengan pemindaian QR Code sederhana.
                    Status hadir, terlambat, dan laporan bulanan tersimpan otomatis — tanpa input ulang.
                </p>

                <div class="mt-8 flex items-center gap-4 flex-wrap">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition">
                        Masuk ke Sistem
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12" />
                        </svg>
                    </a>
                    <a href="#fitur" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        Lihat cara kerjanya &darr;
                    </a>
                </div>
            </div>

            {{-- Signature: kartu QR "sedang di-scan" --}}
            <div class="relative w-full max-w-xs mx-auto">
                <div class="absolute inset-0 bg-brand-100 rounded-[2rem] rotate-6"></div>

                <div
                    class="relative bg-white rounded-[1.75rem] border border-gray-100 shadow-xl shadow-brand-900/5 p-8">
                    <div class="relative h-40">
                        @php
                            // Pola dekoratif menyerupai QR Code (ilustrasi, bukan QR sungguhan)
                            $modul = [
                                [1, 1],
                                [1, 2],
                                [1, 3],
                                [2, 1],
                                [2, 3],
                                [3, 1],
                                [3, 2],
                                [3, 3],
                                [1, 5],
                                [1, 6],
                                [1, 7],
                                [2, 5],
                                [2, 7],
                                [3, 5],
                                [3, 6],
                                [3, 7],
                                [5, 1],
                                [5, 2],
                                [5, 3],
                                [6, 1],
                                [6, 3],
                                [7, 1],
                                [7, 2],
                                [7, 3],
                                [4, 4],
                                [4, 0],
                                [0, 4],
                                [8, 4],
                                [4, 8],
                                [5, 5],
                                [5, 7],
                                [6, 6],
                                [7, 5],
                                [7, 7],
                                [6, 4],
                                [4, 6],
                            ];
                        @endphp
                        <svg viewBox="0 0 9 9" class="w-40 h-40 mx-auto">
                            @foreach ($modul as [$x, $y])
                                <rect x="{{ $x }}" y="{{ $y }}" width="0.9" height="0.9" rx="0.15" fill="#22633B" />
                            @endforeach
                        </svg>

                        <div
                            class="scan-beam absolute inset-x-2 h-1 bg-gradient-to-r from-transparent via-brand-500 to-transparent rounded-full">
                        </div>
                    </div>
                </div>

                <div
                    class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-lg border border-gray-100 px-3 py-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-gray-600">Presensi tercatat</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Semua yang dibutuhkan guru piket</h2>
            <p class="mt-3 text-gray-500">Dari pencatatan harian sampai laporan bulanan, dalam satu sistem.</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl border border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                        <path stroke-linecap="round" d="M8 8h2v2H8zM14 8h2v2h-2zM8 14h2v2H8z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Presensi Sekali Scan</h3>
                <p class="text-sm text-gray-500">Arahkan kamera ke kartu siswa. Status hadir atau terlambat tercatat
                    otomatis, tanpa tulis manual.</p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" d="M5 21V10M12 21V4M19 21v-7" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Rekap Otomatis</h3>
                <p class="text-sm text-gray-500">Laporan harian dan bulanan siap pakai, tinggal diunduh ke PDF atau
                    Excel kapan saja.</p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="12" cy="8" r="3.25" />
                        <path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Data Terpusat</h3>
                <p class="text-sm text-gray-500">Siswa, guru, dan kelas dikelola di satu tempat. Tidak ada lagi buku
                    catatan terpisah-pisah.</p>
            </div>
        </div>
    </section>

    {{-- Cara Kerja --}}
    <section class="bg-gray-50 border-y border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
            <div class="text-center max-w-xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Cara kerjanya</h2>
                <p class="mt-3 text-gray-500">Tiga langkah, kurang dari 3 menit untuk satu kelas.</p>
            </div>

            <div class="grid sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div
                        class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-semibold mx-auto mb-3">
                        1</div>
                    <h3 class="font-semibold text-gray-800 mb-1">Pilih Kelas &amp; Sesi</h3>
                    <p class="text-sm text-gray-500">Guru memilih kelas, lalu sesi presensi masuk atau pulang.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-semibold mx-auto mb-3">
                        2</div>
                    <h3 class="font-semibold text-gray-800 mb-1">Scan Kartu QR</h3>
                    <p class="text-sm text-gray-500">Arahkan kamera ke kartu siswa satu per satu, langsung dari browser.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-semibold mx-auto mb-3">
                        3</div>
                    <h3 class="font-semibold text-gray-800 mb-1">Rekap Tersimpan</h3>
                    <p class="text-sm text-gray-500">Data langsung masuk ke laporan harian dan bulanan, siap diunduh.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Siap gantikan absen kertas di sekolah Anda?</h2>
        <p class="mt-3 text-gray-500 max-w-md mx-auto">Masuk menggunakan akun yang sudah didaftarkan oleh admin sekolah.
        </p>
        <a href="{{ route('login') }}"
            class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition">
            Masuk ke Sistem
        </a>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">{{ config('app.name', 'Presensi Siswa') }} &copy;
                    {{ date('Y') }}</span>
            </div>
            <!-- <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-brand-700">Masuk ke Sistem</a> -->
        </div>
    </footer>

</body>

</html>