<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Manajemen Laundry') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role_label }}).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('transaksi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    + Transaksi Baru
                </a>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('layanan.create') }}" class="inline-flex items-center px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-semibold rounded-lg transition-colors">
                        + Layanan Baru
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Success Message -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-xs flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- 6 Key Performance Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <!-- Total Transaksi -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-blue-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase">Total Pesanan</span>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-black text-gray-900">{{ number_format($totalTransaksi) }}</div>
                    <div class="text-[11px] text-gray-400 mt-1">Keseluruhan nota</div>
                </div>

                <!-- Transaksi Masuk -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-amber-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase text-amber-700">Masuk</span>
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-black text-amber-600">{{ number_format($transaksiMasuk) }}</div>
                    <div class="text-[11px] text-gray-400 mt-1">Menunggu dicuci</div>
                </div>

                <!-- Dalam Proses -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-blue-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase text-blue-700">Proses</span>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-black text-blue-600">{{ number_format($transaksiProses) }}</div>
                    <div class="text-[11px] text-gray-400 mt-1">Sedang dikerjakan</div>
                </div>

                <!-- Selesai -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-emerald-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase text-emerald-700">Selesai</span>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-black text-emerald-600">{{ number_format($transaksiSelesai) }}</div>
                    <div class="text-[11px] text-gray-400 mt-1">Siap diambil</div>
                </div>

                <!-- Total Pendapatan -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-emerald-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase text-emerald-700">Pendapatan</span>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-lg font-black text-emerald-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-gray-400 mt-1">Transaksi Lunas</div>
                </div>

                <!-- Total Berat -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-xs font-semibold uppercase text-indigo-700">Total Berat</span>
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-2xl font-black text-indigo-900">{{ number_format($totalBerat, 1, ',', '.') }} <span class="text-sm font-semibold text-gray-500">kg</span></div>
                    <div class="text-[11px] text-gray-400 mt-1">Cucian tertangani</div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Recent Transactions (2 cols) -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-gray-900">Transaksi Terbaru</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar cucian masuk terkini untuk dipantau statusnya.</p>
                        </div>
                        <a href="{{ route('transaksi.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 text-xs">
                            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Kode / Tgl</th>
                                    <th class="px-4 py-3 text-left">Pelanggan</th>
                                    <th class="px-4 py-3 text-left">Layanan</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($transaksiTerbaru as $trx)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('transaksi.show', $trx) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                                {{ $trx->kode_transaksi }}
                                            </a>
                                            <div class="text-[10px] text-gray-400">{{ $trx->tgl_masuk ? $trx->tgl_masuk->format('d/m H:i') : '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-semibold text-gray-900">{{ $trx->nama_pelanggan }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $trx->no_hp }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="font-medium text-gray-800">{{ $trx->layanan->nama_layanan ?? '-' }}</span>
                                            <div class="text-[10px] text-gray-400">{{ $trx->berat_formatted }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right font-bold text-gray-900">
                                            {{ $trx->harga_rupiah }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $trx->status_badge }}">
                                                {{ $trx->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <a href="{{ route('transaksi.show', $trx) }}" class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-[11px] font-semibold transition-colors">
                                                Nota
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                            Belum ada transaksi tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Services List & Quick Navigation (1 col) -->
                <div class="space-y-6">
                    <!-- Service List -->
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-base text-gray-900">Tarif Layanan</h3>
                            <a href="{{ route('layanan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                Kelola →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($daftarLayanan as $layanan)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                            <span>{{ $layanan->nama_layanan }}</span>
                                            <span class="text-[10px] text-gray-500 font-normal">({{ $layanan->durasi }})</span>
                                        </div>
                                        <div class="text-[11px] text-blue-600 font-semibold mt-0.5">
                                            {{ $layanan->harga_rupiah }} / kg
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-medium px-2 py-0.5 bg-white border border-gray-200 rounded-full text-gray-600">
                                        {{ $layanan->transaksis_count }} order
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Navigation Shortcuts -->
                    <div class="bg-blue-600 text-white p-5 rounded-xl shadow-xs">
                        <h4 class="font-bold text-sm mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Navigasi Cepat Sistem
                        </h4>
                        <p class="text-xs text-blue-100 mb-4">Akses modul utama sistem informasi laundry:</p>
                        <div class="space-y-2 text-xs">
                            <a href="{{ route('transaksi.index') }}" class="flex items-center justify-between p-2.5 bg-blue-700/60 hover:bg-blue-700 rounded-lg transition-colors font-medium">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Rekap Transaksi Laundry
                                </span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('layanan.index') }}" class="flex items-center justify-between p-2.5 bg-blue-700/60 hover:bg-blue-700 rounded-lg transition-colors font-medium">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Master Data Layanan
                                </span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('laporan.index') }}" class="flex items-center justify-between p-2.5 bg-blue-700/60 hover:bg-blue-700 rounded-lg transition-colors font-medium">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Laporan Keuangan & Omzet
                                </span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
