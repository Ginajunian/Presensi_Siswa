<x-app-layout>
    <x-slot name="header">
        Hasil Import Siswa
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-4">

        <div class="grid grid-cols-3 gap-4">
            <x-card>
                <p class="text-2xl font-bold text-brand-600">{{ $berhasil }}</p>
                <p class="text-sm text-gray-400">Berhasil</p>
            </x-card>
            <x-card>
                <p class="text-2xl font-bold text-amber-600">{{ count($dilewati) }}</p>
                <p class="text-sm text-gray-400">Dilewati</p>
            </x-card>
            <x-card>
                <p class="text-2xl font-bold text-rose-600">{{ count($gagal) }}</p>
                <p class="text-sm text-gray-400">Gagal</p>
            </x-card>
        </div>

        @if (count($dilewati) > 0)
            <x-card title="Baris Dilewati (NIS Sudah Terdaftar)" :padding="false">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">Baris</th>
                            <th class="px-5 py-3 font-medium">NIS</th>
                            <th class="px-5 py-3 font-medium">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dilewati as $item)
                            <tr class="border-t border-gray-100">
                                <td class="px-5 py-3 text-gray-500">{{ $item['baris'] }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item['nis'] }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item['nama'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        @endif

        @if (count($gagal) > 0)
            <x-card title="Baris Gagal Diproses" :padding="false">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">Baris</th>
                            <th class="px-5 py-3 font-medium">NIS</th>
                            <th class="px-5 py-3 font-medium">Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gagal as $item)
                            <tr class="border-t border-gray-100">
                                <td class="px-5 py-3 text-gray-500">{{ $item['baris'] }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item['nis'] }}</td>
                                <td class="px-5 py-3 text-rose-600">{{ $item['alasan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        @endif

        <x-link-button :href="route('siswa.index')">Kembali ke Data Siswa &rarr;</x-link-button>
    </div>
</x-app-layout>