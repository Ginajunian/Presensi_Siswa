<x-app-layout>
    <x-slot name="header">
        Data Siswa
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="flex justify-between items-center gap-3 flex-wrap">
            <div class="flex gap-2 flex-wrap">
                <x-link-button :href="route('siswa.create')">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa
                </x-link-button>
                <x-link-button :href="route('siswa.import.form')" variant="secondary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Import Excel
                </x-link-button>
            </div>
            <form method="GET" class="flex gap-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" d="m20 20-3-3" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIS..."
                        class="pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400">
                </div>
                <select name="kelas_id"
                    class="border border-gray-200 rounded-lg px-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-400">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Filter
                </button>
            </form>
        </div>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">Nama Siswa</th>
                            <th class="px-5 py-3 font-medium">NISN</th>
                            <th class="px-5 py-3 font-medium">Kelas</th>
                            <th class="px-5 py-3 font-medium">L/P</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswa as $item)
                            <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($item->foto)
                                            <img src="{{ Storage::url($item->foto) }}"
                                                class="w-9 h-9 rounded-full object-cover">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-xs font-semibold">
                                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-700">{{ $item->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->nis }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->kelas->nama_kelas }}</td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : ($item->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                                </td>
                                <td class="px-5 py-3">
                                    <x-badge :color="$item->status_aktif ? 'green' : 'gray'">
                                        {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2 whitespace-nowrap">
                                            <x-icon-link :href="route('siswa.edit', $item)" label="Edit Siswa">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.75">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" />
                                                </svg>
                                            </x-icon-link>

                                            <form action="{{ route('siswa.toggle-status', $item) }}" method="POST"
                                                onsubmit="return confirm('Yakin ubah status siswa ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <x-icon-button color="amber" :label="$item->status_aktif ? 'Nonaktifkan' : 'Aktifkan'">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.75">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v6" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7 6.5a7 7 0 1 0 10 0" />
                                                    </svg>
                                                </x-icon-button>
                                            </form>

                                            <form action="{{ route('siswa.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Hapus permanen siswa \'{{ $item->nama }}\'? Aksi ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <x-icon-button color="rose" label="Hapus Siswa">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="1.75">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 7h16M9 7V4h6v3m-8 0 1 13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1l1-13" />
                                                    </svg>
                                                </x-icon-button>
                                            </form>
                                        </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siswa->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $siswa->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>