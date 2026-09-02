<x-app-layout>
    <x-slot name="header">
        Data Kelas
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <x-link-button :href="route('kelas.create')">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelas
        </x-link-button>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">Nama Kelas</th>
                            <th class="px-5 py-3 font-medium">Wali Kelas</th>
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $item)
                            <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3 font-medium text-gray-700">{{ $item->nama_kelas }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->waliKelas->nama ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <x-icon-link :href="route('kelas.edit', $item)" label="Edit Kelas">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" />
                                            </svg>
                                        </x-icon-link>

                                        <form action="{{ route('kelas.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-icon-button color="rose" label="Hapus Kelas">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.75">
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
                                <td colspan="3" class="px-5 py-8 text-center text-gray-400">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kelas->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $kelas->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>