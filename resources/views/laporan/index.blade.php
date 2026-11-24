<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Rekap Transaksi & Laporan Keuangan') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Rekap Transaksi Laundry: laporan omzet, status pengerjaan, dan rekapitulasi data transaksi berdasarkan relasi jenis layanan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan.cetak', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan / PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-200">
                <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm">
                    <!-- Dari Tanggal -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <!-- Sampai Tanggal -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <!-- Filter Layanan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Layanan</label>
                        <select name="layanan_id" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Layanan</option>
                            @foreach ($daftarLayanan as $layanan)
                                <option value="{{ $layanan->id }}" {{ $layananId == $layanan->id ? 'selected' : '' }}>
                                    {{ $layanan->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Pembayaran</option>
                            <option value="Lunas" {{ $statusPembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Belum Lunas" {{ $statusPembayaran === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            Filter Laporan
                        </button>
                        <a href="{{ route('laporan.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg text-center transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Financial Summary Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Transaksi -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                            <p class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalTransaksi) }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">Jumlah nota terfilter</div>
                </div>

                <!-- Total Berat -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Berat</p>
                            <p class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalBerat, 1, ',', '.') }} <span class="text-sm font-semibold text-gray-500">kg</span></p>
                        </div>
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">Volume cucian yang ditangani</div>
                </div>

                <!-- Total Pendapatan Lunas -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Pendapatan Lunas</p>
                            <p class="text-2xl font-black text-emerald-700 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">Uang tunai / lunas diterima</div>
                </div>

                <!-- Total Belum Lunas / Piutang -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Belum Lunas (Piutang)</p>
                            <p class="text-2xl font-black text-rose-700 mt-1">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">Menunggu pembayaran saat ambil</div>
                </div>
            </div>

            <!-- Breakdown per Jenis Layanan -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Rekapitulasi Berdasarkan Jenis Layanan
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase">
                            <tr>
                                <th class="py-3 px-4 text-left">Nama Layanan</th>
                                <th class="py-3 px-4 text-center">Jumlah Pesanan</th>
                                <th class="py-3 px-4 text-center">Total Berat Cucian</th>
                                <th class="py-3 px-4 text-right">Total Nilai Transaksi</th>
                                <th class="py-3 px-4 text-right">Pendapatan Lunas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($breakdownLayanan as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-bold text-gray-900">
                                        {{ $row['nama_layanan'] }}
                                    </td>
                                    <td class="py-3 px-4 text-center font-semibold text-gray-700">
                                        {{ $row['jumlah_transaksi'] }} transaksi
                                    </td>
                                    <td class="py-3 px-4 text-center font-medium text-gray-700">
                                        {{ number_format($row['total_berat'], 2, ',', '.') }} kg
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-800">
                                        Rp {{ number_format($row['total_omzet'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-emerald-700">
                                        Rp {{ number_format($row['total_lunas'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-400">Tidak ada data untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detailed Transactions Table -->
            <div class="bg-white overflow-hidden shadow-xs border border-gray-200 rounded-xl">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-sm text-gray-800">Daftar Rincian Transaksi</h3>
                    <span class="text-xs text-gray-500">Menampilkan {{ $transaksiList->count() }} dari {{ $transaksiList->total() }} data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-50 text-gray-600 uppercase font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">Kode & Tanggal</th>
                                <th class="px-4 py-3 text-left">Pelanggan</th>
                                <th class="px-4 py-3 text-left">Layanan</th>
                                <th class="px-4 py-3 text-center">Berat</th>
                                <th class="px-4 py-3 text-right">Total Biaya</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Pembayaran</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($transaksiList as $trx)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-mono font-bold text-blue-600">{{ $trx->kode_transaksi }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $trx->tgl_masuk ? $trx->tgl_masuk->format('d/m/Y H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $trx->nama_pelanggan }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $trx->no_hp }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="font-medium text-gray-800">{{ $trx->layanan->nama_layanan ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center font-medium">
                                        {{ $trx->berat_formatted }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right font-bold text-gray-900">
                                        {{ $trx->harga_rupiah }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $trx->status_badge }}">
                                            {{ $trx->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $trx->status_pembayaran_badge }}">
                                            {{ $trx->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <a href="{{ route('transaksi.show', $trx) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                            Lihat Nota →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada transaksi yang cocok dengan filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transaksiList->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $transaksiList->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
