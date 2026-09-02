<x-app-layout>
    <x-slot name="header">
        Import Data Siswa
    </x-slot>

    <div class="max-w-xl mx-auto space-y-4">

        <x-card>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 19h16" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Belum punya format file?</p>
                    <p class="text-xs text-gray-400 mb-2">Download template, isi sesuai contoh, lalu upload kembali.</p>
                    <a href="{{ route('siswa.import.template') }}" class="text-sm text-brand-700 hover:underline font-medium">
                        Download Template Excel &rarr;
                    </a>
                </div>
            </div>
        </x-card>

        <x-card>
            <form action="{{ route('siswa.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="file" value="File Excel/CSV" />
                    <input id="file" type="file" name="file" accept=".xlsx,.xls,.csv"
                        class="block w-full text-sm text-gray-600 rounded-lg border border-gray-200 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:text-sm file:font-medium hover:file:bg-brand-100">
                    <p class="text-xs text-gray-400 mt-1">
                        Kolom wajib: NIS, Nama, Nama Kelas. Nama kelas harus persis sama dengan yang sudah ada di sistem.
                    </p>
                    <x-input-error :messages="$errors->get('file')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Proses Import</x-primary-button>
                    <x-link-button :href="route('siswa.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>