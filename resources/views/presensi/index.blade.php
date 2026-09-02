<x-app-layout>
    <x-slot name="header">
        Presensi
    </x-slot>

    <div class="max-w-md mx-auto space-y-4">

        @if (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        @if ($isLibur)
            <x-card>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="8.25" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-amber-700">Hari ini libur. Sesi presensi tidak dibuka.</p>
                </div>
            </x-card>
        @else
            <x-card>
                <form action="{{ route('presensi.scanner') }}" method="GET" class="space-y-4">
                    <div>
                        <x-input-label for="kelas_id" value="Pilih Kelas" />
                        <x-select id="kelas_id" name="kelas_id" required class="w-full">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <div>
                        <x-input-label value="Sesi" class="mb-2" />
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="radio" name="sesi" value="masuk" checked class="text-brand-600 focus:ring-brand-400">
                                Presensi Masuk
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="radio" name="sesi" value="pulang" class="text-brand-600 focus:ring-brand-400">
                                Presensi Pulang
                            </label>
                        </div>
                    </div>

                    <x-primary-button class="w-full justify-center">Mulai Presensi</x-primary-button>
                </form>
            </x-card>

            <div class="text-center">
                <x-link-button :href="route('presensi.manual')" variant="ghost">
                    Tandai Izin / Sakit / Alpa untuk siswa yang belum hadir &rarr;
                </x-link-button>
            </div>
        @endif
    </div>
</x-app-layout>