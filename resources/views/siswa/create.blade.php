<x-app-layout>
    <x-slot name="header">
        Tambah Siswa
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="nis" value="NIS" />
                    <x-text-input id="nis" name="nis" type="text" inputmode="numeric" maxlength="10"
                        value="{{ old('nis') }}" class="w-full"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                    <p class="text-xs text-gray-400 mt-1">10 digit angka, sesuai format NISN.</p>
                    <x-input-error :messages="$errors->get('nis')" />
                </div>

                <div>
                    <x-input-label for="nama" value="Nama" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('nama')" />
                </div>
                <div>
                    <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                    <x-select id="jenis_kelamin" name="jenis_kelamin" class="w-full">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('jenis_kelamin') == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin') == 'P')>Perempuan</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('jenis_kelamin')" />
                </div>
                <div>
                    <x-input-label for="kelas_id" value="Kelas" />
                    <x-select id="kelas_id" name="kelas_id" class="w-full">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('kelas_id')" />
                </div>

                <div>
                    <x-input-label for="foto" value="Foto (opsional)" />
                    <input id="foto" type="file" name="foto" accept="image/*"
                        class="block w-full text-sm text-gray-600 rounded-lg border border-gray-200 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:text-sm file:font-medium hover:file:bg-brand-100">
                    <x-input-error :messages="$errors->get('foto')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <x-link-button :href="route('siswa.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>