<x-app-layout>
    <x-slot name="header">
        Generate QR
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <x-card>
            <form method="GET" class="flex gap-3 items-end">
                <div>
                    <x-input-label for="kelas_id" value="Filter Kelas" />
                    <x-select id="kelas_id" name="kelas_id" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected($kelasId == $kelas->id)>{{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
           </form>
        </x-card>

        <form id="form-qr-massal" method="POST" action="{{ route('siswa.qr-massal.download') }}">
            @csrf

            <x-card :padding="false">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 flex-wrap gap-2">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" id="check-all"
                            class="rounded border-gray-300 text-brand-600 focus:ring-brand-400">
                        Pilih Semua ({{ $siswaList->count() }} siswa)
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" formaction="{{ route('siswa.qr-massal.regenerate') }}"
                            onclick="return confirm('QR lama siswa terpilih akan tidak berlaku lagi. Lanjutkan generate ulang?')"
                            class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-sm font-medium transition">
                            Generate Ulang Terpilih
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-sm font-medium transition">
                            Download PDF Terpilih
                        </button>
                    </div>
                </div>

                <div class="max-h-[28rem] overflow-y-auto divide-y divide-gray-100">
                    @forelse ($siswaList as $siswa)
                        <label class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/60 cursor-pointer">
                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}"
                                class="siswa-check rounded border-gray-300 text-brand-600 focus:ring-brand-400">

                            @if ($siswa->foto)
                                <img src="{{ Storage::url($siswa->foto) }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ $siswa->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $siswa->nis }} &middot; {{ $siswa->kelas->nama_kelas }}
                                </p>
                            </div>

                            <a href="{{ route('siswa.qr-card', $siswa) }}" target="_blank" rel="noopener"
                                title="Lihat detail QR {{ $siswa->nama }}"
                                class="shrink-0 w-10 h-10 rounded-lg border border-gray-200 hover:border-brand-400 hover:ring-2 hover:ring-brand-100 transition p-1 bg-white">
                                <img src="{{ route('siswa.qr-image', ['siswa' => $siswa, 'size' => 80]) }}"
                                    alt="QR {{ $siswa->nama }}" loading="lazy" class="w-full h-full object-contain">
                            </a>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-8">Tidak ada data siswa.</p>
                    @endforelse
                </div>
            </x-card>
        </form>
    </div>

    <script>
        document.getElementById('check-all').addEventListener('change', (e) => {
            document.querySelectorAll('.siswa-check').forEach(cb => cb.checked = e.target.checked);
        });

        document.getElementById('form-qr-massal').addEventListener('submit', (e) => {
            const adaTerpilih = document.querySelectorAll('.siswa-check:checked').length > 0;
            if (!adaTerpilih) {
                e.preventDefault();
                alert('Pilih minimal 1 siswa terlebih dahulu.');
            }
        });
    </script>
</x-app-layout>