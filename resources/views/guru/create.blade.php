<x-app-layout>
    <x-slot name="header">
        Tambah Guru
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('guru.store') }}" method="POST" class="space-y-4">
                @csrf

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
                    <x-input-label for="nip" value="NIP (opsional)" />
                    <x-text-input id="nip" name="nip" type="text" value="{{ old('nip') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('nip')" />
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 mt-3">Akun Login</p>
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div>
                    <x-input-label for="password" value="Password Awal" />
                    <x-text-input id="password" name="password" type="text" value="{{ old('password') }}"
                        class="w-full" />
                    <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Sarankan guru menggantinya setelah login
                        pertama.</p>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <x-link-button :href="route('guru.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>