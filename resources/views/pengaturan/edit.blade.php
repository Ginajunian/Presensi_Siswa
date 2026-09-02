<x-app-layout>
    <x-slot name="header">
        Pengaturan Presensi
    </x-slot>

    <div class="max-w-xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <x-card>
            <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="jam_masuk_standar" value="Jam Masuk Standar" />
                    <x-text-input id="jam_masuk_standar" name="jam_masuk_standar" type="time" class="w-full"
                        value="{{ old('jam_masuk_standar', \Carbon\Carbon::parse($pengaturan->jam_masuk_standar)->format('H:i')) }}" />
                    <x-input-error :messages="$errors->get('jam_masuk_standar')" />
                </div>

                <div>
                    <x-input-label for="toleransi_terlambat_menit" value="Toleransi Terlambat (menit)" />
                    <x-text-input id="toleransi_terlambat_menit" name="toleransi_terlambat_menit" type="number" class="w-full"
                        value="{{ old('toleransi_terlambat_menit', $pengaturan->toleransi_terlambat_menit) }}" />
                    <p class="text-xs text-gray-400 mt-1">Scan masuk setelah jam masuk + toleransi ini otomatis ditandai "Terlambat".</p>
                    <x-input-error :messages="$errors->get('toleransi_terlambat_menit')" />
                </div>

                <div>
                    <x-input-label for="jam_pulang_standar" value="Jam Pulang Standar" />
                    <x-text-input id="jam_pulang_standar" name="jam_pulang_standar" type="time" class="w-full"
                        value="{{ old('jam_pulang_standar', \Carbon\Carbon::parse($pengaturan->jam_pulang_standar)->format('H:i')) }}" />
                    <x-input-error :messages="$errors->get('jam_pulang_standar')" />
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <x-input-label value="Hari Libur Mingguan" class="mb-2" />
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($hariList as $hari)
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="hari_libur[]" value="{{ $hari }}"
                                    class="rounded border-gray-300 text-brand-600 focus:ring-brand-400"
                                    @checked(in_array($hari, old('hari_libur', $hariLiburAktif)))>
                                {{ $hari }}
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Sesi presensi (Masuk & Pulang) tidak akan dibuka di hari yang dicentang.</p>
                </div>

                <x-primary-button class="mt-2">Simpan Pengaturan</x-primary-button>
            </form>
        </x-card>
    </div>
</x-app-layout>