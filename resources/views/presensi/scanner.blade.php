<x-app-layout>
    <x-slot name="header">
        Presensi {{ ucfirst($sesi) }} — {{ $kelas->nama_kelas }}
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <x-card class="mb-4">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-sm text-gray-500">Arahkan kamera ke QR Code siswa.</p>
                    <button id="btn-toggle-kamera"
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition">
                        Mulai Kamera
                    </button>
                </div>

                <div id="reader" class="w-full rounded-xl overflow-hidden border border-gray-100"></div>
                <p id="kamera-error" class="text-rose-600 text-sm mt-2 hidden"></p>

                <div id="feedback" class="hidden"></div>
            </x-card>

            <div class="flex items-center gap-2 mb-4 px-4 py-2.5 rounded-lg bg-brand-50 text-brand-700 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="3.25" />
                    <path stroke-linecap="round" d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                </svg>
                Sesi ini dicatat oleh <span class="font-semibold">{{ auth()->user()->name }}</span>
            </div>

            <x-card :padding="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                                <th class="p-3">Nama</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Jam Masuk</th>
                                <th class="p-3">Jam Pulang</th>
                                @if ($sesi === 'masuk')
                                    <th class="p-3">Tandai Manual</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="daftar-siswa">
                            @forelse ($siswaList as $siswa)
                                @php
                                    $p = $presensiHariIni->get($siswa->id);
                                    $badgeClass = match (true) {
                                        $p && in_array($p->status, ['hadir', 'terlambat']) => 'bg-brand-50 text-brand-700',
                                        $p && in_array($p->status, ['izin', 'sakit']) => 'bg-amber-50 text-amber-700',
                                        $p && $p->status === 'alpa' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-gray-100 text-gray-500',
                                    };
                                    $statusLabel = $sesi === 'masuk'
                                        ? ($p ? ucfirst($p->status) : '-')
                                        : ($p && $p->waktu_pulang ? 'Sudah Pulang' : '-');
                                    $jamMasukLabel = $p && $p->waktu_masuk ? \Carbon\Carbon::parse($p->waktu_masuk)->format('H:i') : '-';
                                    $jamPulangLabel = $p && $p->waktu_pulang ? \Carbon\Carbon::parse($p->waktu_pulang)->format('H:i') : '-';
                                    $sudahScan = $p && in_array($p->status, ['hadir', 'terlambat']);
                                @endphp
                                <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition"
                                    data-nama="{{ $siswa->nama }}" data-siswa-id="{{ $siswa->id }}">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $siswa->nama }}</td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="status-cell inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 jam-masuk-cell text-gray-500">{{ $jamMasukLabel }}</td>
                                    <td class="px-5 py-3 jam-pulang-cell text-gray-500">{{ $jamPulangLabel }}</td>
                                    @if ($sesi === 'masuk')
                                        <td class="px-5 py-3">
                                            <select
                                                class="manual-select border-gray-200 focus:border-brand-400 focus:ring focus:ring-brand-100 focus:ring-opacity-50 rounded-lg text-sm py-1.5"
                                                @disabled($sudahScan)>
                                                <option value="">-- Pilih --</option>
                                                <option value="izin" @selected($p?->status === 'izin')>Izin</option>
                                                <option value="sakit" @selected($p?->status === 'sakit')>Sakit</option>
                                                <option value="alpa" @selected($p?->status === 'alpa')>Alpa</option>
                                            </select>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $sesi === 'masuk' ? 5 : 4 }}"
                                        class="px-5 py-8 text-center text-gray-400">
                                        Tidak ada siswa untuk ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <x-link-button :href="route('presensi.index')" variant="ghost">&larr; Kembali</x-link-button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
            const kelasId = {{ $kelas->id }};
            const sesi = @json($sesi);
            const csrfToken = @json(csrf_token());

            let html5QrCode = null;
            let kameraAktif = false;
            let lastToken = null;
            let lastScanTime = 0;

            function badgeClassFor(status) {
                if (status === 'Hadir' || status === 'Terlambat' || status === 'Sudah Pulang') return 'bg-brand-50 text-brand-700';
                if (status === 'Izin' || status === 'Sakit') return 'bg-amber-50 text-amber-700';
                if (status === 'Alpa') return 'bg-rose-50 text-rose-700';
                return 'bg-gray-100 text-gray-500';
            }

            function tampilkanFeedback(message, tipe) {
                const el = document.getElementById('feedback');
                el.textContent = message;
                el.className = 'mt-3 rounded-xl border px-4 py-3 text-sm ' +
                    (tipe === 'sukses' ? 'bg-brand-50 text-brand-700 border-brand-100'
                        : tipe === 'duplikat' ? 'bg-amber-50 text-amber-700 border-amber-100'
                            : 'bg-rose-50 text-rose-700 border-rose-100');
            }

            function updateBaris(nama, statusText, jamText) {
                const baris = document.querySelector(`#daftar-siswa tr[data-nama="${nama}"]`);
                if (baris) {
                    baris.querySelector('.status-cell').textContent = statusText;

                    const selectorJam = sesi === 'masuk' ? '.jam-masuk-cell' : '.jam-pulang-cell';
                    baris.querySelector(selectorJam).textContent = jamText;

                    const manualSelect = baris.querySelector('.manual-select');
                    if (manualSelect && (statusText === 'Hadir' || statusText === 'Terlambat')) {
                        manualSelect.disabled = true;
                        manualSelect.value = '';
                    }
                }
            }

            async function prosesScan(token) {
                try {
                    const res = await fetch("{{ route('presensi.proses-scan') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ qr_token: token, kelas_id: kelasId, sesi: sesi }),
                    });

                    if (res.status === 429) {
                        tampilkanFeedback('Terlalu banyak scan dalam waktu singkat. Tunggu sebentar lalu coba lagi.', 'gagal');
                        return;
                    }

                    const data = await res.json();

                    tampilkanFeedback(data.message, data.status === 'sukses' ? 'sukses' : data.status);

                    if (data.nama && (data.status === 'sukses' || data.status === 'duplikat')) {
                        const jam = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const statusText = data.sub_status
                            ? (data.sub_status === 'terlambat' ? 'Terlambat' : 'Hadir')
                            : (sesi === 'pulang' ? 'Sudah Pulang' : '-');
                        updateBaris(data.nama, statusText, jam);
                    }
                } catch (err) {
                    tampilkanFeedback('Terjadi kesalahan koneksi.', 'gagal');
                }
            }

            // --- Kamera ---

            function onScanSuccess(decodedText) {
                const sekarang = Date.now();
                if (decodedText === lastToken && (sekarang - lastScanTime) < 3000) return;
                lastToken = decodedText;
                lastScanTime = sekarang;
                prosesScan(decodedText);
            }

            async function mulaiKamera() {
                document.getElementById('kamera-error').classList.add('hidden');
                html5QrCode = new Html5Qrcode("reader");
                const btn = document.getElementById('btn-toggle-kamera');

                try {
                    await html5QrCode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess,
                        () => { }
                    );
                    kameraAktif = true;
                    btn.textContent = 'Hentikan Kamera';
                    btn.classList.remove('bg-brand-600', 'hover:bg-brand-700');
                    btn.classList.add('bg-rose-600', 'hover:bg-rose-700');
                } catch (err) {
                    document.getElementById('kamera-error').textContent =
                        'Tidak bisa mengakses kamera. Pastikan izin kamera diizinkan, atau gunakan input manual di bawah.';
                    document.getElementById('kamera-error').classList.remove('hidden');
                }
            }

            async function hentikanKamera() {
                if (html5QrCode && kameraAktif) {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    kameraAktif = false;
                    const btn = document.getElementById('btn-toggle-kamera');
                    btn.textContent = 'Mulai Kamera';
                    btn.classList.remove('bg-rose-600', 'hover:bg-rose-700');
                    btn.classList.add('bg-brand-600', 'hover:bg-brand-700');
                }
            }

            document.getElementById('btn-toggle-kamera').addEventListener('click', () => {
                kameraAktif ? hentikanKamera() : mulaiKamera();
            });

            // --- Tandai manual langsung dari list (Izin/Sakit/Alpa) ---

            document.querySelectorAll('.manual-select').forEach((select) => {
                select.addEventListener('change', async (e) => {
                    const baris = e.target.closest('tr');
                    const siswaId = baris.dataset.siswaId;
                    const nama = baris.dataset.nama;
                    const nilai = e.target.value;
                    const statusKirim = nilai === '' ? 'batal' : nilai;

                    try {
                        const res = await fetch("{{ route('presensi.manual-single') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ siswa_id: siswaId, tanggal: "{{ \Carbon\Carbon::today()->toDateString() }}", status: statusKirim }),
                        });
                        const data = await res.json();

                        if (data.status === 'sukses') {
                            const statusCell = baris.querySelector('.status-cell');
                            const label = data.status_baru ? (data.status_baru.charAt(0).toUpperCase() + data.status_baru.slice(1)) : '-';
                            statusCell.textContent = label;
                            statusCell.className = 'status-cell inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ' + badgeClassFor(label);
                            tampilkanFeedback(data.message, 'sukses');
                        } else {
                            tampilkanFeedback(data.message, 'gagal');
                            e.target.value = '';
                        }
                    } catch (err) {
                        tampilkanFeedback('Terjadi kesalahan koneksi.', 'gagal');
                    }
                });
            });

            window.addEventListener('beforeunload', () => {
                if (kameraAktif) hentikanKamera();
            });
        </script>
</x-app-layout>