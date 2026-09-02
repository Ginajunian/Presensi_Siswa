<x-app-layout>
    <x-slot name="header">
        Edit Guru
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('guru.update', $guru) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nama" value="Nama" />
                    <x-text-input id="nama" name="nama" type="text" value="{{ old('nama', $guru->nama) }}"
                        class="w-full" />
                    <x-input-error :messages="$errors->get('nama')" />
                </div>
                <div>
                    <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                    <x-select id="jenis_kelamin" name="jenis_kelamin" class="w-full">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('jenis_kelamin', $guru->jenis_kelamin) == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $guru->jenis_kelamin) == 'P')>Perempuan</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('jenis_kelamin')" />
                </div>
                <div>
                    <x-input-label for="nip" value="NIP (opsional)" />
                    <x-text-input id="nip" name="nip" type="text" value="{{ old('nip', $guru->nip) }}" class="w-full" />
                    <x-input-error :messages="$errors->get('nip')" />
                </div>

                <div>
                    <x-input-label value="Email" />
                    <x-text-input type="email" value="{{ $guru->user->email }}" class="w-full bg-gray-50 text-gray-400"
                        disabled />
                    <!-- <p class="text-xs text-gray-400 mt-1">Ubah email login belum didukung di sini (fitur lanjutan).</p> -->
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <x-input-label for="password" value="Reset Password" />
                    <x-text-input id="password" name="password" type="text" class="w-full"
                        placeholder="Kosongkan jika tidak ingin mengubah" />
                    <p class="text-xs text-gray-400 mt-1">Isi hanya jika ingin mengatur ulang password guru ini. Minimal
                        8 karakter.</p>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Update</x-primary-button>
                    <x-link-button :href="route('guru.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>