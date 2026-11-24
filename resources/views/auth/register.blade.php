<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Registrasi Akun</h1>
        <p class="text-sm text-gray-600 mt-1">Sistem Informasi Manajemen WashFlow</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@laundry.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Pendaftaran (Kasir / Petugas) -->
        <div>
            <x-input-label for="role" :value="__('Peran / Hak Akses')" />
            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="kasir" selected>Kasir (Pelayanan Transaksi POS)</option>
                <option value="admin">Admin (Akses Penuh Kelola Layanan & Laporan)</option>
            </select>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-2.5 text-sm bg-blue-600 hover:bg-blue-700 active:bg-blue-800">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                {{ __('Daftar Akun Baru') }}
            </x-primary-button>
        </div>

        <div class="text-center text-xs text-gray-500 pt-2">
            Sudah memiliki akun?
            <a class="text-blue-600 hover:text-blue-800 font-semibold underline ms-1" href="{{ route('login') }}">
                {{ __('Masuk ke Akun') }}
            </a>
        </div>
    </form>
</x-guest-layout>
