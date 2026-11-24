<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
        <p class="text-sm text-gray-600 mt-1">Sistem Informasi Manajemen WashFlow</p>
    </div>

    <!-- Quick Demo Credential Buttons -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <p class="text-xs font-semibold text-blue-900 uppercase tracking-wider mb-2 flex items-center">
            <svg class="w-4 h-4 me-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Akun Demo Pengujian (1-Klik Isi Form)
        </p>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <button type="button" onclick="fillLogin('admin@laundry.com', 'password')" class="w-full text-left p-2.5 bg-white border border-blue-300 hover:bg-blue-100 rounded-lg transition-colors shadow-2xs group">
                <div class="font-bold text-blue-900 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Admin
                    </span>
                    <span class="text-[10px] text-blue-600 group-hover:underline">Klik Isi</span>
                </div>
                <div class="text-[11px] text-gray-600 truncate">admin@laundry.com</div>
                <div class="text-[11px] text-gray-400">Password: password</div>
            </button>
            <button type="button" onclick="fillLogin('kasir@laundry.com', 'password')" class="w-full text-left p-2.5 bg-white border border-emerald-300 hover:bg-emerald-50 rounded-lg transition-colors shadow-2xs group">
                <div class="font-bold text-emerald-900 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Kasir
                    </span>
                    <span class="text-[10px] text-emerald-600 group-hover:underline">Klik Isi</span>
                </div>
                <div class="text-[11px] text-gray-600 truncate">kasir@laundry.com</div>
                <div class="text-[11px] text-gray-400">Password: password</div>
            </button>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Pengguna')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@laundry.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-xs focus:ring-blue-500" name="remember">
                <span class="ms-2 text-xs text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-blue-600 hover:text-blue-800 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="w-full justify-center py-2.5 text-sm bg-blue-600 hover:bg-blue-700 active:bg-blue-800">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Masuk ke Sistem') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center text-xs text-gray-500">
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← Kembali ke Halaman Utama</a>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
