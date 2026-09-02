<x-app-layout>
    <x-slot name="header">
        Rekap Presensi Harian
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        <x-card>
            <form method="GET" class="flex gap-3 items-end flex-wrap">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                    <select name="kelas_id" class="border border-gray-200 rounded-lg px-6 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected($kelasId == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Tampilkan
                </button>

                @if ($kelasId)
                    <a href="{{ route('laporan.harian.pdf', ['kelas_id' => $kelasId, 'tanggal' => $tanggal]) }}"
                        class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 19h16" />
                        </svg>
                        Export PDF
                    </a>
                @endif
            </form>
        </x-card>

        @if ($kelasId)
            <x-card :padding="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                                <th class="px-5 py-3 font-medium">Nama</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                                <th class="px-5 py-3 font-medium">Jam Masuk</th>
                                <th class="px-5 py-3 font-medium">Jam Pulang</th>
                                <th class="px-5 py-3 font-medium">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $row)
                                @php
                                    $p = $row['presensi'];
                                    $warna = match ($p?->status) {
                                        'hadir' => 'green',
                                        'terlambat' => 'yellow',
                                        'izin', 'sakit' => 'yellow',
                                        'alpa' => 'red',
                                        default => 'gray',
                                    };
                                @endphp
                                <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-3 font-medium text-gray-700">{{ $row['siswa']->nama }}</td>
                                    <td class="px-5 py-3">
                                        <x-badge :color="$warna">{{ $p ? ucfirst($p->status) : 'Belum tercatat' }}</x-badge>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ $p?->waktu_masuk ? \Carbon\Carbon::parse($p->waktu_masuk)->format('H:i') : '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $p?->waktu_pulang ? \Carbon\Carbon::parse($p->waktu_pulang)->format('H:i') : '-' }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $p?->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada data siswa aktif di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif
    </div>
</x-app-layout>