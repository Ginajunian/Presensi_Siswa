<x-app-layout>
    <x-slot name="header">
        Tambah Kelas
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('kelas.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="nama_kelas" value="Nama Kelas" />
                    <x-text-input id="nama_kelas" name="nama_kelas" type="text" value="{{ old('nama_kelas') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('nama_kelas')" />
                </div>

                <div>
                    <x-input-label for="wali_kelas_id" value="Wali Kelas" />
                    <x-select id="wali_kelas_id" name="wali_kelas_id" class="w-full">
                        <option value="">-- Belum ditentukan --</option>
                        @foreach ($guruList as $guru)
                            <option value="{{ $guru->id }}" @selected(old('wali_kelas_id') == $guru->id)>{{ $guru->nama }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('wali_kelas_id')" />
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-100 mt-2">
                    <x-primary-button class="mt-4">Simpan</x-primary-button>
                    <x-link-button :href="route('kelas.index')" variant="secondary" class="mt-4">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>