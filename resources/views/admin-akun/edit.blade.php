<x-app-layout>
    <x-slot name="header">
        Edit Akun Admin
    </x-slot>

    <div class="max-w-xl mx-auto">
        <x-card>
            <form action="{{ route('admin-akun.update', $user) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label value="Email" />
                    <x-text-input type="email" value="{{ $user->email }}" class="w-full bg-gray-50 text-gray-400" disabled />
                    <!-- <p class="text-xs text-gray-400 mt-1">Ubah email login belum didukung di sini (fitur lanjutan).</p> -->
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <x-input-label for="password" value="Reset Password" />
                    <x-text-input id="password" name="password" type="text" class="w-full" placeholder="Kosongkan jika tidak ingin mengubah" />
                    <p class="text-xs text-gray-400 mt-1">Isi hanya jika ingin mengatur ulang password akun ini. Minimal 8 karakter.</p>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="flex gap-2 pt-2">
                    <x-primary-button>Update</x-primary-button>
                    <x-link-button :href="route('admin-akun.index')" variant="secondary">Batal</x-link-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>