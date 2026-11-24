<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Transaksi Laundry') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola transaksi penerimaan cucian, pembaruan status pengerjaan, dan status pembayaran.</p>
            </div>
            <a href="{{ route('transaksi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                + Transaksi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            <!-- Search and Filter Form -->
            <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-200">
                <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-sm">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Pelanggan / Kode</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Kode / Nama / No HP..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>

                    <!-- Status Pengerjaan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status Pengerjaan</label>
                        <select name="status" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Masuk" {{ $status === 'Masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="Proses" {{ $status === 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Status Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Pembayaran</option>
                            <option value="Belum Lunas" {{ $statusPembayaran === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="Lunas" {{ $statusPembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    <!-- Layanan Filter -->
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

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg text-center transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-xs border border-gray-200 rounded-xl">
                <!-- Desktop Table (hidden on mobile, visible md+) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 text-left">Kode & Tanggal</th>
                                <th scope="col" class="px-4 py-3.5 text-left">Pelanggan</th>
                                <th scope="col" class="px-4 py-3.5 text-left">Layanan</th>
                                <th scope="col" class="px-4 py-3.5 text-center">Berat</th>
                                <th scope="col" class="px-4 py-3.5 text-right">Total Tagihan</th>
                                <th scope="col" class="px-4 py-3.5 text-center">Status Pengerjaan</th>
                                <th scope="col" class="px-4 py-3.5 text-center">Status Pembayaran</th>
                                <th scope="col" class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($transaksiList as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Kode & Tanggal -->
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('transaksi.show', $item) }}" class="font-mono font-bold text-blue-600 hover:text-blue-800 text-xs">
                                            {{ $item->kode_transaksi }}
                                        </a>
                                        <div class="text-[11px] text-gray-500">
                                            {{ $item->tgl_masuk ? $item->tgl_masuk->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Pelanggan -->
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 text-xs">{{ $item->nama_pelanggan }}</div>
                                        <div class="text-[11px] text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $item->no_hp }}
                                        </div>
                                    </td>

                                    <!-- Layanan -->
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200">
                                            {{ $item->layanan->nama_layanan ?? '-' }}
                                        </span>
                                        <div class="text-[11px] text-gray-400 mt-0.5">
                                            {{ $item->layanan ? $item->layanan->durasi : '' }}
                                        </div>
                                    </td>

                                    <!-- Berat -->
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-xs font-semibold text-gray-800">
                                        {{ $item->berat_formatted }}
                                    </td>

                                    <!-- Total Harga -->
                                    <td class="px-4 py-4 whitespace-nowrap text-right">
                                        <div class="font-bold text-gray-900 text-xs">
                                            {{ $item->harga_rupiah }}
                                        </div>
                                    </td>

                                    <!-- Status Pengerjaan & Alur Update -->
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $item->status_badge }}">
                                                @if ($item->status === 'Masuk')
                                                    <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Masuk
                                                @elseif ($item->status === 'Proses')
                                                    <svg class="w-3 h-3 text-blue-700 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Proses
                                                @else
                                                    <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Selesai
                                                @endif
                                            </span>

                                            <!-- Tombol Alur Cepat Status -->
                                            @if ($item->status === 'Masuk')
                                                <form method="POST" action="{{ route('transaksi.status', $item) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Proses">
                                                    <button type="submit" class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded font-medium transition-colors" title="Ubah menjadi Proses">
                                                        <span>Mulai Proses</span>
                                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @elseif ($item->status === 'Proses')
                                                <form method="POST" action="{{ route('transaksi.status', $item) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Selesai">
                                                    <button type="submit" class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 rounded font-medium transition-colors" title="Ubah menjadi Selesai">
                                                        <svg class="w-2.5 h-2.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <span>Tandai Selesai</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status Pembayaran -->
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $item->status_pembayaran_badge }}">
                                                {{ $item->status_pembayaran }}
                                            </span>

                                            @if ($item->status_pembayaran === 'Belum Lunas')
                                                <form method="POST" action="{{ route('transaksi.pembayaran', $item) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_pembayaran" value="Lunas">
                                                    <button type="submit" class="text-[10px] px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded font-medium transition-colors shadow-2xs">
                                                        Bayar Lunas
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Lihat Struk / Detail -->
                                            <a href="{{ route('transaksi.show', $item) }}" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors" title="Lihat Struk / Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('transaksi.edit', $item) }}" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-md transition-colors" title="Edit Transaksi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <!-- Hapus -->
                                            <form method="POST" action="{{ route('transaksi.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi {{ $item->kode_transaksi }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-md transition-colors" title="Hapus Transaksi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Belum ada transaksi yang sesuai kriteria pencarian.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (block md:hidden) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse ($transaksiList as $item)
                        <div class="p-4 space-y-3 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <a href="{{ route('transaksi.show', $item) }}" class="font-mono font-bold text-blue-600 hover:text-blue-800 text-sm">
                                        {{ $item->kode_transaksi }}
                                    </a>
                                    <div class="text-[11px] text-gray-500">
                                        {{ $item->tgl_masuk ? $item->tgl_masuk->format('d M Y, H:i') : '-' }}
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $item->status_pembayaran_badge }}">
                                    {{ $item->status_pembayaran }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-gray-900">{{ $item->nama_pelanggan }}</span>
                                    <span class="text-gray-500 ms-1 tabular-nums">({{ $item->no_hp }})</span>
                                </div>
                                <div class="font-bold text-blue-700 text-sm tabular-nums">
                                    {{ $item->harga_rupiah }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-medium text-gray-800">{{ $item->layanan->nama_layanan ?? '-' }}</span>
                                    <span class="text-gray-500 tabular-nums">({{ $item->berat_formatted }})</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $item->status_badge }}">
                                    {{ $item->status }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-2">
                                    @if ($item->status === 'Masuk')
                                        <form method="POST" action="{{ route('transaksi.status', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Proses">
                                            <button type="submit" class="text-xs px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md font-medium">Mulai Proses</button>
                                        </form>
                                    @elseif ($item->status === 'Proses')
                                        <form method="POST" action="{{ route('transaksi.status', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" class="text-xs px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md font-medium">Tandai Selesai</button>
                                        </form>
                                    @endif

                                    @if ($item->status_pembayaran === 'Belum Lunas')
                                        <form method="POST" action="{{ route('transaksi.pembayaran', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pembayaran" value="Lunas">
                                            <button type="submit" class="text-xs px-2.5 py-1 bg-emerald-600 text-white rounded-md font-medium">Bayar Lunas</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('transaksi.show', $item) }}" class="p-1.5 bg-gray-100 text-gray-700 rounded-md text-xs font-semibold">Nota</a>
                                    <a href="{{ route('transaksi.edit', $item) }}" class="p-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-xs font-semibold">Edit</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-400 text-xs">Belum ada transaksi tercatat.</div>
                    @endforelse
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
