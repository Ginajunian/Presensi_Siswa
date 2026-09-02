<x-app-layout>
    <x-slot name="header">
        Presensi Manual (Izin / Sakit / Alpa)
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <x-card>
            <form method="GET" class="flex gap-3 items-end flex-wrap">
                <div>
                    <x-input-label for="kelas_id" value="Kelas" />
                    <x-select id="kelas_id" name="kelas_id" class="w-full">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected($kelasId == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <x-text-input id="tanggal" name="tanggal" type="date" value="{{ $tanggal }}" />
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Tampilkan
                </button>
            </form>
        </x-card>

        @if ($kelasId)
            @if ($siswaList->isEmpty())
                <x-card>
                    <p class="text-sm text-gray-400 text-center py-4">
                        Semua siswa di kelas ini sudah tercatat Hadir/Terlambat pada tanggal ini. Tidak ada yang perlu ditandai manual.
                    </p>
                </x-card>
            @else
                <form action="{{ route('presensi.manual.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    <x-card :padding="false">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                                        <th class="px-5 py-3 font-medium">Nama</th>
                                        <th class="px-5 py-3 font-medium">Status</th>
                                        <th class="px-5 py-3 font-medium">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswaList as $siswa)
                                        @php $existing = $presensiSaatIni->get($siswa->id); @endphp
                                        <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                            <td class="px-5 py-3 font-medium text-gray-700">{{ $siswa->nama }}</td>
                                            <td class="px-5 py-3">
                                                <select name="status[{{ $siswa->id }}]"
                                                    class="border-gray-200 focus:border-brand-400 focus:ring focus:ring-brand-100 focus:ring-opacity-50 rounded-lg text-sm py-1.5">
                                                    <option value="">-- Belum diisi --</option>
                                                    <option value="izin" @selected($existing?->status === 'izin')>Izin</option>
                                                    <option value="sakit" @selected($existing?->status === 'sakit')>Sakit</option>
                                                    <option value="alpa" @selected($existing?->status === 'alpa')>Alpa</option>
                                                </select>
                                            </td>
                                            <td class="px-5 py-3">
                                                <input type="text" name="keterangan[{{ $siswa->id }}]"
                                                    value="{{ $existing->keterangan ?? '' }}" placeholder="Opsional"
                                                    class="border-gray-200 focus:border-brand-400 focus:ring focus:ring-brand-100 focus:ring-opacity-50 rounded-lg text-sm w-full py-1.5">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5 py-4 border-t border-gray-100">
                            <x-primary-button>Simpan Semua</x-primary-button>
                        </div>
                    </x-card>
                </form>
            @endif
        @endif
    </div>
</x-app-layout>