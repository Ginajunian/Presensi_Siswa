<x-app-layout>
    <x-slot name="header">
        Tambah Akun Admin
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('admin-akun.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div>
                    <x-input-label for="password" value="Password Awal" />
                    <x-text-input id="password" name="password" type="text" value="{{ old('password') }}" class="w-full" />
                    <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Sarankan admin baru menggantinya setelah login pertama.</p>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <x-link-button :href="route('admin-akun.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>