<x-app-layout>
    <x-slot name="header">
        Kartu QR Siswa
    </x-slot>

    <div class="max-w-md mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success" class="print:hidden">{{ session('success') }}</x-alert>
        @endif

        <div id="kartu" class="bg-white rounded-2xl border border-gray-100 shadow-sm shadow-gray-100 p-6 text-center">
            @if ($siswa->foto)
                <img src="{{ Storage::url($siswa->foto) }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3">
            @else
                <div class="w-20 h-20 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center mx-auto mb-3 text-2xl font-semibold">
                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                </div>
            @endif
            <h3 class="font-semibold text-lg text-gray-800">{{ $siswa->nama }}</h3>
            <p class="text-sm text-gray-400">NIS: {{ $siswa->nis }}</p>
            <p class="text-sm text-gray-400 mb-4">{{ $siswa->kelas->nama_kelas }}</p>

            <img src="{{ route('siswa.qr-image', $siswa) }}" alt="QR Code {{ $siswa->nama }}" class="mx-auto">
        </div>

        <div class="flex gap-2 flex-wrap print:hidden">
            <button onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v4H6v-4Zm-2-9h16a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-3v-3H6v3H3a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1Z" />
                </svg>
                Cetak Kartu
            </button>

            <form action="{{ route('siswa.regenerate-qr', $siswa) }}" method="POST"
                onsubmit="return confirm('QR Code lama akan langsung tidak berlaku. Lanjutkan generate ulang?')">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a8 8 0 0 1 14.5-4.5M20 15a8 8 0 0 1-14.5 4.5" />
                    </svg>
                    Generate Ulang QR
                </button>
            </form>

            <x-link-button :href="route('siswa.index')" variant="secondary">Kembali</x-link-button>
        </div>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            #kartu, #kartu * { visibility: visible; }
            #kartu { position: absolute; top: 0; left: 0; width: 100%; border: none; box-shadow: none; }
        }
    </style>
</x-app-layout>