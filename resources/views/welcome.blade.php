<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WashFlow - Sistem Informasi Manajemen Laundry</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <!-- Top Navigation -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-application-logo class="w-9 h-9" />
                <div>
                    <span class="font-black text-xl text-blue-600 tracking-tight">WASHFLOW</span>
                    <span class="hidden sm:inline-block text-xs text-slate-500 ms-2 pl-2 border-l border-slate-300">Sistem Informasi Manajemen</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-xs">
                        Buka Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-xs">
                        Login Admin / Kasir →
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="py-16 sm:py-20 bg-linear-to-b from-blue-50/70 to-slate-50 border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 mb-4">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                Manajemen Laundry Modern, Cepat & Akurat
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Kelola Layanan, Transaksi & Laporan Keuangan Cucian Tanpa Ribet
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
                Aplikasi Sistem Informasi Manajemen WashFlow memudahkan pencatatan cucian masuk, monitoring proses hingga selesai, serta rekapitulasi omzet keuangan secara real-time.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all text-sm">
                        Masuk ke Dashboard Sistem
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all text-sm">
                        Masuk ke Sistem (Login)
                    </a>
                @endauth
                <a href="#daftar-layanan" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-100 text-slate-700 font-bold border border-slate-300 rounded-xl transition-all text-sm">
                    Lihat Daftar & Tarif Layanan
                </a>
            </div>

            <!-- Demo hint -->
            <div class="mt-6 text-xs text-slate-500">
                Akun Uji Coba: <strong class="text-slate-700">admin@laundry.com</strong> (Admin) &bull; <strong class="text-slate-700">kasir@laundry.com</strong> (Kasir) &bull; Password: <strong class="text-slate-700">password</strong>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="daftar-layanan" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                Pilihan Jenis Layanan Cuci
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Pilih paket cuci berkualitas sesuai kebutuhan waktu dan jenis pakaian Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($layananList as $layanan)
                <div class="bg-white rounded-2xl p-6 border {{ $layanan->nama_layanan === 'Superkilat' ? 'border-amber-300 ring-2 ring-amber-400/20 shadow-md' : 'border-slate-200 shadow-xs' }} flex flex-col justify-between hover:scale-[1.01] transition-transform">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md {{ $layanan->nama_layanan === 'Superkilat' ? 'bg-amber-100 text-amber-800' : ($layanan->nama_layanan === 'Ekspres' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                {{ $layanan->nama_layanan }}
                            </span>
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $layanan->durasi }}
                            </span>
                        </div>

                        <div class="my-4">
                            <span class="text-3xl font-black text-slate-900">{{ $layanan->harga_rupiah }}</span>
                            <span class="text-xs text-slate-500">/ kilogram</span>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed min-h-[48px]">
                            {{ $layanan->deskripsi ?? 'Layanan pencucian profesional, bersih, harum, dan higienis.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Pewangi + Setrika Uap</span>
                        <span class="text-blue-600 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tersedia
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    Belum ada jenis layanan terdaftar.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Features Overview -->
    <section class="py-12 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center sm:text-left">
                <div class="flex items-start gap-3">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Pencatatan Transaksi Cepat</h4>
                        <p class="text-xs text-slate-500 mt-1">Kalkulasi biaya otomatis berdasarkan timbangan kilogram dan jenis paket layanan.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Update Status Pengerjaan</h4>
                        <p class="text-xs text-slate-500 mt-1">Transisi status yang rapi: Masuk &rarr; Proses &rarr; Selesai, siap diambil oleh pelanggan.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Rekap & Laporan Keuangan</h4>
                        <p class="text-xs text-slate-500 mt-1">Filter omzet berdasarkan rentang tanggal dan layanan, lengkap dengan fitur cetak laporan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-slate-900 text-slate-400 text-xs text-center border-t border-slate-800">
        <p>&copy; {{ date('Y') }} WashFlow. Hak Cipta Dilindungi Undang-Undang.</p>
        <p class="mt-1 text-slate-500">Sistem Informasi Manajemen Laundry Berbasis Web - Laravel 12.</p>
    </footer>

</body>
</html>
