<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Detail Transaksi & Struk Nota') }} : {{ $transaksi->kode_transaksi }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Nota resmi penerimaan dan pengambilan cucian WashFlow.</p>
            </div>
            <div class="flex items-center gap-2 print:hidden">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Nota / Invoice
                </button>
                <a href="{{ route('transaksi.edit', $transaksi) }}" class="inline-flex items-center px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 text-xs font-semibold rounded-lg transition-colors">
                    Ubah Data
                </a>
                <a href="{{ route('transaksi.index') }}" class="inline-flex items-center px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Success Message -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-xs flex items-center justify-between print:hidden">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Quick Status Workflow Bar -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status Saat Ini:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $transaksi->status_badge }}">
                        {{ $transaksi->status }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $transaksi->status_pembayaran_badge }}">
                        {{ $transaksi->status_pembayaran }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if ($transaksi->status === 'Masuk')
                        <form method="POST" action="{{ route('transaksi.status', $transaksi) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Proses">
                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                Mulai Pengerjaan (Proses) →
                            </button>
                        </form>
                    @elseif ($transaksi->status === 'Proses')
                        <form method="POST" action="{{ route('transaksi.status', $transaksi) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Selesai">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Tandai Selesai (Siap Diambil)</span>
                            </button>
                        </form>
                    @endif

                    @if ($transaksi->status_pembayaran === 'Belum Lunas')
                        <form method="POST" action="{{ route('transaksi.pembayaran', $transaksi) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pembayaran" value="Lunas">
                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                Pelunasan Pembayaran (Lunas)
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Receipt Card (Printable Area) -->
            <div id="printableReceipt" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                <!-- Laundry Header -->
                <div class="text-center pb-6 border-b-2 border-dashed border-gray-200">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <x-application-logo class="w-8 h-8" />
                        <h1 class="text-2xl font-black tracking-tight text-gray-900">WASHFLOW</h1>
                    </div>
                    <p class="text-xs text-gray-500">Layanan Cuci Bersih, Wangi, Rapi & Terpercaya</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Jl. Raya Kampus No. 123 | WhatsApp: 0812-3456-7890</p>
                </div>

                <!-- Transaction Meta Info -->
                <div class="py-4 border-b border-gray-100 grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <div class="text-gray-500">Kode Transaksi:</div>
                        <div class="font-mono font-bold text-gray-900 text-sm mt-0.5">{{ $transaksi->kode_transaksi }}</div>
                        <div class="text-gray-500 mt-2">Tanggal Masuk:</div>
                        <div class="font-semibold text-gray-800">{{ $transaksi->tgl_masuk ? $transaksi->tgl_masuk->format('d/m/Y H:i') : '-' }}</div>
                        @if ($transaksi->tgl_selesai)
                            <div class="text-gray-500 mt-1">Tanggal Selesai:</div>
                            <div class="font-semibold text-emerald-700">{{ $transaksi->tgl_selesai->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-gray-500">Pelanggan:</div>
                        <div class="font-bold text-gray-900 text-sm mt-0.5">{{ $transaksi->nama_pelanggan }}</div>
                        <div class="text-gray-500 mt-1">No. HP / WA:</div>
                        <div class="font-semibold text-gray-800">{{ $transaksi->no_hp }}</div>
                        <div class="text-gray-500 mt-2">Kasir:</div>
                        <div class="font-semibold text-gray-700">{{ $transaksi->user?->name ?? 'Kasir WashFlow' }}</div>
                    </div>
                </div>

                <!-- Order Item Table -->
                <div class="py-4 border-b border-gray-100">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-200">
                                <th class="text-left py-2">Jenis Layanan</th>
                                <th class="text-center py-2">Tarif / Kg</th>
                                <th class="text-center py-2">Berat</th>
                                <th class="text-right py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3">
                                    <div class="font-bold text-gray-900">{{ $transaksi->layanan?->nama_layanan ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-500">Estimasi: {{ $transaksi->layanan?->durasi ?? '-' }}</div>
                                    @if ($transaksi->catatan)
                                        <div class="text-[10px] text-amber-700 bg-amber-50 rounded px-1.5 py-0.5 mt-1 inline-block">
                                            Catatan: {{ $transaksi->catatan }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center py-3 text-gray-700">
                                    {{ $transaksi->layanan?->harga_rupiah ?? '-' }}
                                </td>
                                <td class="text-center py-3 font-semibold text-gray-800">
                                    {{ $transaksi->berat_formatted }}
                                </td>
                                <td class="text-right py-3 font-bold text-gray-900">
                                    {{ $transaksi->harga_rupiah }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total & Payment Details -->
                <div class="py-4 space-y-2 border-b-2 border-dashed border-gray-200 text-xs">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-gray-800">TOTAL PEMBAYARAN:</span>
                        <span class="font-extrabold text-blue-700 text-lg">{{ $transaksi->harga_rupiah }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Status Pembayaran:</span>
                        <span class="font-bold {{ $transaksi->status_pembayaran === 'Lunas' ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ strtoupper($transaksi->status_pembayaran) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Status Pengerjaan Cucian:</span>
                        <span class="font-bold text-gray-800">
                            {{ strtoupper($transaksi->status) }}
                        </span>
                    </div>
                </div>

                <!-- Footer Terms -->
                <div class="pt-6 text-center text-[11px] text-gray-500 space-y-1">
                    <p class="font-semibold text-gray-700">Terima kasih atas kepercayaan Anda di WashFlow!</p>
                    <p>Barang yang tidak diambil lebih dari 30 hari di luar tanggung jawab laundry.</p>
                    <p>Harap bawa struk ini saat mengambil pakaian Anda.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
