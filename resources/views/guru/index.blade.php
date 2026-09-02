<x-app-layout>
    <x-slot name="header">
        Data Guru
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-4">

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <x-link-button :href="route('guru.create')">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Guru
        </x-link-button>

        <x-card :padding="false">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wide border-b border-gray-100">
                            <th class="px-5 py-3 font-medium">Nama</th>
                            <th class="px-5 py-3 font-medium">NIP</th>
                            <th class="px-5 py-3 font-medium">L/P</th>
                            <th class="px-5 py-3 font-medium">Email</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guru as $item)
                            <tr class="border-t border-gray-100 hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3 font-medium text-gray-700">{{ $item->nama }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->nip ?? '-' }}</td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : ($item->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $item->user->email }}</td>
                                <td class="px-5 py-3">
                                    <x-badge :color="$item->user->is_active ? 'green' : 'gray'">
                                        {{ $item->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <x-icon-link :href="route('guru.edit', $item)" label="Edit Guru">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" />
                                            </svg>
                                        </x-icon-link>

                                        <form action="{{ route('guru.toggle-status', $item) }}" method="POST"
                                            onsubmit="return confirm('Yakin ubah status akun guru ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <x-icon-button color="amber" :label="$item->user->is_active ? 'Nonaktifkan' : 'Aktifkan'">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.75">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v6" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7 6.5a7 7 0 1 0 10 0" />
                                                </svg>
                                            </x-icon-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($guru->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $guru->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>