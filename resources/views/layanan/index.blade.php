<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Data Jenis Layanan Cuci') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola jenis layanan, tarif per kilogram, dan estimasi waktu pengerjaan laundry.</p>
            </div>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('layanan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Layanan Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
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

            <!-- Info Bar for Kasir -->
            @if (!auth()->user()->isAdmin())
                <div class="p-3.5 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-xs flex items-center">
                    <svg class="w-4 h-4 text-blue-500 me-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Anda login sebagai <strong>Kasir</strong>. Anda dapat melihat daftar tarif & durasi layanan untuk melayani pelanggan. Pengubahan layanan hanya dapat dilakukan oleh Administrator.</span>
                </div>
            @endif

            <!-- Search and Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-xs border border-gray-200 flex flex-col sm:flex-row gap-4 items-center justify-between">
                <form method="GET" action="{{ route('layanan.index') }}" class="w-full sm:w-80 flex items-center gap-2">
                    <div class="relative w-full">
                        <input type="text" name="cari" value="{{ $keyword }}" placeholder="Cari nama layanan atau deskripsi..." class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 ps-9" />
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                        Cari
                    </button>
                    @if ($keyword)
                        <a href="{{ route('layanan.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
                    @endif
                </form>

                <div class="text-xs text-gray-500">
                    Total: <strong>{{ $layananList->total() }}</strong> jenis layanan terdaftar
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-xs border border-gray-200 rounded-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left">No</th>
                                <th scope="col" class="px-6 py-3.5 text-left">Nama Layanan</th>
                                <th scope="col" class="px-6 py-3.5 text-left">Tarif / Kg</th>
                                <th scope="col" class="px-6 py-3.5 text-left">Durasi Estimasi</th>
                                <th scope="col" class="px-6 py-3.5 text-left">Deskripsi Layanan</th>
                                <th scope="col" class="px-6 py-3.5 text-center">Total Pesanan</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($layananList as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                        {{ $layananList->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 flex items-center gap-2">
                                            @if ($item->nama_layanan === 'Superkilat')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                    <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    Prioritas
                                                </span>
                                            @elseif ($item->nama_layanan === 'Ekspres')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                                    <svg class="w-3 h-3 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                    </svg>
                                                    Cepat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-800 border border-gray-300">
                                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Standar
                                                </span>
                                            @endif
                                            <span>{{ $item->nama_layanan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-blue-700 text-base">
                                            {{ $item->harga_rupiah }}
                                        </span>
                                        <span class="text-xs text-gray-500">/kg</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            <svg class="w-3.5 h-3.5 text-gray-500 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $item->durasi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs text-xs">
                                        {{ $item->deskripsi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                            {{ $item->transaksis_count }} pesanan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        @if (auth()->user()->isAdmin())
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('layanan.edit', $item) }}" class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-md transition-colors" title="Ubah Layanan">
                                                    <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Ubah
                                                </a>
                                                <form method="POST" action="{{ route('layanan.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan \'{{ $item->nama_layanan }}\'?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-md transition-colors" title="Hapus Layanan">
                                                        <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Lihat Saja</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data layanan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($layananList->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $layananList->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
