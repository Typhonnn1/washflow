<x-pos-layout>
<div x-data="posSystem()" x-init="init()" class="h-screen w-screen flex overflow-hidden bg-slate-50 text-slate-900 font-sans">

    <!-- ==================== SIDEBAR (RAMPING / COLLAPSIBLE) ==================== -->
    <aside 
        class="bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 transition-all duration-200 z-30 select-none"
        :class="sidebarCollapsed ? 'w-16' : 'w-56'"
    >
        <!-- Top: Brand & Menu -->
        <div class="flex flex-col">
            <!-- Brand -->
            <div class="h-14 border-b border-slate-200 flex items-center px-4 justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 overflow-hidden text-left group" title="Ke Dashboard Utama">
                    <svg class="w-8 h-8 rounded-lg shadow-xs shrink-0 group-hover:scale-105 transition-transform" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="pos-wf-bg" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#2563EB" />
                                <stop offset="100%" stop-color="#0E7490" />
                            </linearGradient>
                        </defs>
                        <rect width="32" height="32" rx="8" fill="url(#pos-wf-bg)" />
                        <rect x="0.5" y="0.5" width="31" height="31" rx="7.5" stroke="#FFFFFF" stroke-opacity="0.25" stroke-width="1" />
                        <!-- Sleek WF Vector Monogram -->
                        <path d="M 6 10.5 L 8.8 21.5 L 11.5 13 L 14.2 21.5 L 17 10.5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 19.5 10.5 L 25 10.5 M 19.5 15.5 L 24 15.5 M 19.5 10.5 L 19.5 21.5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="25.5" cy="20.5" r="1.25" fill="#38BDF8" />
                    </svg>
                    <div x-show="!sidebarCollapsed" class="flex flex-col truncate">
                        <span class="font-bold text-sm leading-tight text-slate-900 truncate group-hover:text-blue-600 transition-colors">WashFlow</span>
                        <span class="text-[11px] text-slate-500 leading-tight">POS Operasional</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-2 space-y-1">
                <!-- 1. Antrean Cucian -->
                <button 
                    type="button" 
                    @click="switchView('antrean')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-left"
                    :class="currentView === 'antrean' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    title="Antrean Cucian"
                >
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate flex-1">Antrean Cucian</span>
                    <span 
                        x-show="!sidebarCollapsed" 
                        class="tabular-nums text-[11px] font-semibold px-1.5 py-0.5 rounded bg-slate-200 text-slate-700"
                        x-text="counts.antrean + counts.dicuci + counts.setrika + counts.siap_ambil"
                    ></span>
                </button>

                <!-- 2. Kasir / Order Baru -->
                <button 
                    type="button" 
                    @click="switchView('kasir')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-left"
                    :class="currentView === 'kasir' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    title="Kasir / Order Baru"
                >
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate flex-1">Kasir / Order Baru</span>
                </button>

                <!-- 3. Pelanggan -->
                <button 
                    type="button" 
                    @click="switchView('pelanggan')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-left"
                    :class="currentView === 'pelanggan' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    title="Pelanggan"
                >
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate flex-1">Pelanggan</span>
                </button>

                <!-- 4. Layanan & Harga -->
                <button 
                    type="button" 
                    @click="switchView('layanan')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-left"
                    :class="currentView === 'layanan' ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    title="Layanan & Harga"
                >
                    <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate flex-1">Layanan & Harga</span>
                </button>
            </nav>
        </div>

        <!-- Bottom: User & Collapse Toggle -->
        <div class="p-2 border-t border-slate-200 space-y-1">
            <div x-show="!sidebarCollapsed" class="px-3 py-2 flex items-center justify-between text-xs text-slate-500">
                <span class="truncate font-medium text-slate-800">{{ Auth::user()->name }}</span>
                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200">
                    {{ Auth::user()->role }}
                </span>
            </div>

            <!-- Dashboard Link -->
            <a 
                href="{{ route('dashboard') }}" 
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                title="Keluar ke Dashboard Admin"
            >
                <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Menu Admin</span>
            </a>

            <!-- Collapse Toggle Button -->
            <button 
                type="button" 
                @click="sidebarCollapsed = !sidebarCollapsed" 
                class="w-full flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                :title="sidebarCollapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'"
            >
                <svg x-show="!sidebarCollapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <svg x-show="sidebarCollapsed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">

        <!-- ======================================================== -->
        <!-- VIEW A: TAMPILAN UTAMA — ANTREAN & PIPELINE CUCIAN        -->
        <!-- ======================================================== -->
        <div x-show="currentView === 'antrean'" class="h-full flex flex-col overflow-hidden">
            <!-- Header Ringkas Operasional -->
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex flex-wrap items-center justify-between gap-4 shrink-0">
                <!-- Title & Inline Counters -->
                <div class="flex items-center gap-3">
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Antrean Cucian</h1>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs text-slate-500 font-medium tabular-nums">
                        Total <span class="font-semibold text-slate-800" x-text="counts.antrean + counts.dicuci + counts.setrika + counts.siap_ambil"></span> order aktif
                        &bull;
                        <span class="text-amber-700 font-semibold" x-text="counts.express"></span> Express
                    </span>
                </div>

                <!-- Controls: Search, Express Filter, & Primary CTA -->
                <div class="flex items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative w-64">
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            @input.debounce.300ms="filterOrders()"
                            placeholder="Cari nota, nama, no. WA..."
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400 focus:bg-white"
                        >
                        <button 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; filterOrders()" 
                            class="absolute right-2 top-1.5 text-slate-400 hover:text-slate-600 text-xs"
                        >&times;</button>
                    </div>

                    <!-- Filter Semua / Express (Secondary style) -->
                    <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-0.5 text-xs">
                        <button 
                            type="button" 
                            @click="filterExpress = 'all'; filterOrders()"
                            class="px-2.5 py-1 rounded-md transition-colors"
                            :class="filterExpress === 'all' ? 'bg-white text-slate-900 font-medium shadow-none border border-slate-200' : 'text-slate-600 hover:text-slate-900'"
                        >
                            Semua
                        </button>
                        <button 
                            type="button" 
                            @click="filterExpress = 'express'; filterOrders()"
                            class="px-2.5 py-1 rounded-md transition-colors"
                            :class="filterExpress === 'express' ? 'bg-white text-amber-800 font-semibold shadow-none border border-amber-300' : 'text-slate-600 hover:text-slate-900'"
                        >
                            Express
                        </button>
                    </div>

                    <!-- 1 PRIMARY CTA PER LAYAR: + Order Baru -->
                    <button 
                        type="button" 
                        @click="switchView('kasir')"
                        class="bg-blue-700 hover:bg-blue-800 text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Order Baru</span>
                    </button>
                </div>
            </header>

            <!-- Pipeline Status Tabs dengan Counter Angka -->
            <div class="bg-white border-b border-slate-200 px-6 shrink-0 flex items-center gap-1 overflow-x-auto text-xs">
                <template x-for="tab in pipelineTabs" :key="tab.id">
                    <button 
                        type="button" 
                        @click="pipelineTab = tab.id; filterOrders()"
                        class="py-2.5 px-3 border-b-2 font-medium flex items-center gap-2 transition-colors whitespace-nowrap"
                        :class="pipelineTab === tab.id ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    >
                        <span x-text="tab.label"></span>
                        <span 
                            class="tabular-nums text-[10px] font-semibold px-1.5 py-0.2 rounded"
                            :class="pipelineTab === tab.id ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
                            x-text="getTabCount(tab.id)"
                        ></span>
                    </button>
                </template>
            </div>

            <!-- Tabel Operasional Padat (Shopify Admin + Linear standard) -->
            <div class="flex-1 overflow-auto bg-slate-50 p-4">
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-500 text-[11px] font-semibold uppercase tracking-wider">
                                <th class="py-2.5 px-3">No. Nota & Waktu</th>
                                <th class="py-2.5 px-3">Pelanggan</th>
                                <th class="py-2.5 px-3">Layanan & Berat</th>
                                <th class="py-2.5 px-3">Deadline / Lokasi Rak</th>
                                <th class="py-2.5 px-3">Status Bayar</th>
                                <th class="py-2.5 px-3">Status Cucian</th>
                                <th class="py-2.5 px-3 text-right">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Empty State Tabel -->
                            <tr x-show="filteredOrders.length === 0">
                                <td colspan="7" class="py-12 text-center text-slate-500">
                                    <div class="max-w-xs mx-auto space-y-1">
                                        <p class="font-medium text-slate-800 text-sm">Tidak ada antrean cucian</p>
                                        <p class="text-xs text-slate-400">Tidak ada order yang cocok dengan filter atau kata kunci saat ini.</p>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row Item Antrean -->
                            <template x-for="order in filteredOrders" :key="order.id">
                                <tr 
                                    @click="openOrderDrawer(order)"
                                    class="hover:bg-slate-50/80 cursor-pointer transition-colors group"
                                    :class="order.is_express ? 'bg-amber-50/20' : ''"
                                >
                                    <!-- No. Nota & Waktu (Aksen garis kiri tipis jika Express) -->
                                    <td class="py-2.5 px-3 align-middle" :class="order.is_express ? 'border-l-2 border-l-amber-500' : 'border-l-2 border-l-transparent'">
                                        <div class="flex flex-col">
                                            <span class="font-mono font-semibold text-slate-900 tabular-nums" x-text="order.nota_display || ('#' + order.kode_transaksi)"></span>
                                            <span class="text-[11px] text-slate-500 tabular-nums" x-text="formatDate(order.tgl_masuk)"></span>
                                        </div>
                                    </td>

                                    <!-- Pelanggan -->
                                    <td class="py-2.5 px-3 align-middle">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-slate-900" x-text="order.nama_pelanggan"></span>
                                            <span class="text-[11px] text-slate-500 tabular-nums" x-text="order.no_hp"></span>
                                        </div>
                                    </td>

                                    <!-- Layanan & Berat -->
                                    <td class="py-2.5 px-3 align-middle">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-medium text-slate-800" x-text="order.layanan ? order.layanan.nama_layanan : 'Layanan Laundry'"></span>
                                                <span x-show="order.is_express" class="text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-1 py-0.2 rounded">
                                                    EXP
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-slate-500 tabular-nums" x-text="order.berat + ' kg &bull; ' + formatRupiah(order.total_harga)"></span>
                                        </div>
                                    </td>

                                    <!-- Deadline / Lokasi Rak -->
                                    <td class="py-2.5 px-3 align-middle">
                                        <div class="flex flex-col gap-0.5">
                                            <!-- Sisa waktu express -->
                                            <template x-if="order.is_express">
                                                <span class="text-xs font-semibold text-amber-700 tabular-nums" x-text="formatSisaWaktu(order)"></span>
                                            </template>
                                            <template x-if="!order.is_express">
                                                <span class="text-[11px] text-slate-500 tabular-nums" x-text="formatDeadline(order.deadline)"></span>
                                            </template>

                                            <!-- Lokasi Rak untuk Siap Ambil / Semua -->
                                            <template x-if="order.lokasi_rak">
                                                <span class="inline-flex items-center text-[10px] font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200 px-1.5 py-0.2 rounded w-fit">
                                                    <span x-text="order.lokasi_rak"></span>
                                                </span>
                                            </template>
                                            <template x-if="!order.lokasi_rak && order.status === 'Siap Ambil'">
                                                <span class="inline-flex items-center text-[10px] font-medium text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.2 rounded w-fit">
                                                    Belum di Rak
                                                </span>
                                            </template>
                                        </div>
                                    </td>

                                    <!-- Status Bayar (Terpisah) -->
                                    <td class="py-2.5 px-3 align-middle">
                                        <template x-if="order.status_pembayaran === 'Lunas'">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Lunas
                                            </span>
                                        </template>
                                        <template x-if="order.status_pembayaran !== 'Lunas'">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                Belum Lunas
                                            </span>
                                        </template>
                                    </td>

                                    <!-- Status Cucian (Dot kecil + teks ringkas) -->
                                    <td class="py-2.5 px-3 align-middle">
                                        <div class="flex items-center gap-1.5">
                                            <span 
                                                class="w-2 h-2 rounded-full shrink-0"
                                                :class="getStatusDotClass(order.status)"
                                            ></span>
                                            <span class="font-medium text-slate-800" x-text="normalizeStatus(order.status)"></span>
                                        </div>
                                    </td>

                                    <!-- Aksi Cepat (1 tombol aksi lanjutan langsung + tombol WA) -->
                                    <td class="py-2.5 px-3 align-middle text-right" @click.stop>
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <!-- Tombol Aksi Lanjutan Langsung -->
                                            <template x-if="['Antrean', 'Masuk'].includes(order.status)">
                                                <button 
                                                    type="button" 
                                                    @click="advanceStatus(order, 'Dicuci')"
                                                    class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                                >
                                                    Mulai Cuci
                                                </button>
                                            </template>

                                            <template x-if="['Dicuci', 'Proses'].includes(order.status)">
                                                <button 
                                                    type="button" 
                                                    @click="advanceStatus(order, 'Setrika')"
                                                    class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                                >
                                                    Mulai Setrika
                                                </button>
                                            </template>

                                            <template x-if="order.status === 'Setrika'">
                                                <button 
                                                    type="button" 
                                                    @click="advanceStatus(order, 'Siap Ambil')"
                                                    class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                                >
                                                    Tandai Siap Ambil
                                                </button>
                                            </template>

                                            <template x-if="order.status === 'Siap Ambil'">
                                                <button 
                                                    type="button" 
                                                    @click="advanceStatus(order, 'Selesai')"
                                                    class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                                >
                                                    Serahkan
                                                </button>
                                            </template>

                                            <template x-if="order.status === 'Selesai'">
                                                <span class="text-[11px] text-slate-400 font-medium px-2 py-0.5">Selesai</span>
                                            </template>

                                            <!-- Tombol WhatsApp Notifikasi -->
                                            <button 
                                                type="button" 
                                                @click="sendWhatsApp(order)"
                                                title="Kirim Nota via WhatsApp"
                                                class="p-1 rounded border border-slate-200 bg-white text-slate-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 transition-colors"
                                            >
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.528 1.841.809 2.796.809 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.514-4.486 10-10 10-1.748 0-3.411-.453-4.872-1.282l-5.128 1.344 1.37-4.996c-.927-1.534-1.37-3.238-1.37-5.066 0-5.514 4.486-10 10-10s10 4.486 10 10z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- VIEW B: TAMPILAN KASIR / ORDER BARU (SPLIT SCREEN 65/35)  -->
        <!-- ======================================================== -->
        <div x-show="currentView === 'kasir'" class="h-full flex overflow-hidden">
            <!-- ---------------------------------------------------- -->
            <!-- KOLOM KIRI (65%): KATALOG LAYANAN                    -->
            <!-- ---------------------------------------------------- -->
            <div class="w-[65%] h-full flex flex-col bg-slate-100 overflow-hidden">
                <!-- Top Bar Area Kiri: Header & Tab Kategori Menyatu Solid -->
                <div class="bg-white border-b border-slate-200 shrink-0">
                    <!-- Header Kolom Kiri -->
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="switchView('antrean')"
                                class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 font-medium transition-colors"
                            >
                                &larr; Antrean
                            </button>
                            <span class="text-slate-300">|</span>
                            <h2 class="text-sm font-bold text-slate-900 tracking-tight">Katalog Layanan</h2>
                        </div>

                        <!-- Search Layanan Input -->
                        <div class="w-64">
                            <input 
                                type="text" 
                                x-model="serviceSearch" 
                                placeholder="Cari layanan laundry..."
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400 focus:bg-white transition-colors"
                            >
                        </div>
                    </div>

                    <!-- Tab Kategori Layanan -->
                    <div class="px-4 flex items-center gap-1.5 overflow-x-auto text-xs bg-white">
                        <template x-for="cat in serviceCategories" :key="cat">
                            <button 
                                type="button" 
                                @click="selectedCategory = cat"
                                class="py-2.5 px-3 border-b-2 font-medium transition-colors whitespace-nowrap"
                                :class="selectedCategory === cat ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                                x-text="cat"
                            ></button>
                        </template>
                    </div>
                </div>

                <!-- Grid Kartu Layanan Scrollable Canvas -->
                <div class="flex-1 overflow-y-auto p-4 bg-slate-100">
                    <!-- Empty state pencarian layanan -->
                    <div x-show="filteredServices.length === 0" class="py-12 text-center text-slate-500">
                        <p class="font-medium text-slate-800 text-sm">Layanan tidak ditemukan</p>
                        <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain atau pilih tab kategori 'Semua'.</p>
                    </div>

                    <!-- Grid Kartu Layanan (4 Kolom Desktop) -->
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5">
                        <template x-for="service in filteredServices" :key="service.id">
                            <div 
                                @click="addToCart(service)"
                                class="bg-white border rounded-lg p-2.5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] cursor-pointer select-none relative flex flex-col justify-between hover:border-blue-500 hover:bg-blue-50/20 active:scale-[0.99] transition duration-150 min-h-[96px]"
                                :class="[
                                    getCategoryAccentClass(service),
                                    getCartItem(service.id) ? 'border-blue-600 ring-1 ring-blue-600 bg-blue-50/15' : 'border-slate-200/80'
                                ]"
                            >
                                <!-- Badge Angka Qty/Berat di Pojok Kanan Atas Kartu jika Item di Nota -->
                                <template x-if="getCartItem(service.id)">
                                    <div class="absolute -top-1.5 -right-1.5 bg-blue-600 text-white font-bold text-[10px] px-1.5 py-0.5 rounded-full shadow-xs tabular-nums flex items-center gap-0.5 z-10">
                                        <span x-text="getCartItem(service.id).qty"></span>
                                        <span class="text-[9px] font-normal" x-text="service.satuan || 'kg'"></span>
                                    </div>
                                </template>

                                <!-- Bagian Atas: Badge Estimasi & Pill Kategori Lembut -->
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <span 
                                        class="text-[10px] font-semibold px-1.5 py-0.5 rounded tracking-wide uppercase inline-flex items-center"
                                        :class="getCategoryPillClass(service)"
                                        x-text="service.kategori"
                                    ></span>
                                    <span 
                                        class="text-[10px] text-slate-500 font-medium tabular-nums"
                                        x-text="service.durasi"
                                    ></span>
                                </div>

                                <!-- Bagian Tengah: Nama Layanan (bold, 1-2 baris) -->
                                <div class="my-auto py-0.5">
                                    <span class="font-bold text-xs text-slate-900 leading-snug line-clamp-2" x-text="service.nama_layanan"></span>
                                </div>

                                <!-- Bagian Bawah: Harga Besar tabular-nums + Satuan -->
                                <div class="mt-2 pt-1.5 border-t border-slate-100 flex items-baseline">
                                    <span class="font-bold text-sm text-slate-900 tabular-nums">
                                        Rp <span x-text="formatNumber(service.harga_per_kg)"></span>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal ml-0.5">/<span x-text="service.satuan || 'kg'"></span></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------- -->
            <!-- KOLOM KANAN (35%, STICKY): PELANGGAN, CART, CTA      -->
            <!-- ---------------------------------------------------- -->
            <div class="w-[35%] h-full flex flex-col bg-white border-l border-slate-200 overflow-hidden">
                <!-- Header Nota -->
                <div class="p-4 border-b border-slate-200 flex items-center justify-between shrink-0 bg-slate-50/50">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Nota Transaksi</span>
                    <span class="text-xs text-slate-500 font-mono tabular-nums">
                        <span x-text="cart.length"></span> item
                    </span>
                </div>

                <!-- Bagian Pelanggan (Nama + No. WA Sejajar 2 Kolom) -->
                <div class="p-3 border-b border-slate-200 bg-white shrink-0">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="relative">
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Nama Pelanggan</label>
                            <input 
                                type="text" 
                                x-model="customerName" 
                                @focus="customerDropdown = true"
                                placeholder="Contoh: Ibu Rahma"
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-400 focus:bg-white transition-colors"
                            >
                            <!-- Dropdown Sugesti Pelanggan Terdaftar -->
                            <div 
                                x-show="customerDropdown && customerSuggestions.length > 0" 
                                @click.outside="customerDropdown = false"
                                class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-lg shadow-md z-30 max-h-40 overflow-y-auto divide-y divide-slate-100"
                            >
                                <template x-for="c in customerSuggestions" :key="c.no_hp">
                                    <div 
                                        @click="selectCustomer(c)"
                                        class="p-2 hover:bg-slate-50 cursor-pointer text-xs flex justify-between items-center"
                                    >
                                        <span class="font-medium text-slate-800 truncate" x-text="c.nama_pelanggan"></span>
                                        <span class="text-slate-500 tabular-nums text-[11px] shrink-0 ml-1" x-text="c.no_hp"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Nomor WhatsApp</label>
                            <input 
                                type="text" 
                                x-model="customerPhone" 
                                placeholder="081234567890"
                                class="w-full text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-900 tabular-nums focus:outline-none focus:ring-1 focus:ring-slate-400 focus:bg-white transition-colors"
                            >
                        </div>
                    </div>
                </div>

                <!-- Daftar Item Nota (Cart Line Items) -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <!-- Empty State Nota Kosong -->
                    <div x-show="cart.length === 0" class="py-12 text-center text-slate-400 space-y-1">
                        <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="font-medium text-slate-700 text-xs">Nota Masih Kosong</p>
                        <p class="text-[11px] text-slate-400 max-w-[200px] mx-auto">Klik layanan di katalog sebelah kiri untuk menambahkan item ke nota.</p>
                    </div>

                    <!-- Cart Items -->
                    <template x-for="(item, idx) in cart" :key="item.layanan_id">
                        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50/50 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-900 truncate" x-text="item.nama_layanan"></p>
                                    <p class="text-[11px] text-slate-500 tabular-nums">
                                        Rp <span x-text="formatNumber(item.harga)"></span> / <span x-text="item.satuan"></span>
                                    </p>
                                </div>

                                <!-- Controls +/- dan Qty Desimal -->
                                <div class="flex items-center gap-1 shrink-0">
                                    <button 
                                        type="button" 
                                        @click="adjustCartQty(idx, -1)"
                                        class="w-6 h-6 rounded border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 flex items-center justify-center font-bold text-xs transition-colors"
                                    >-</button>
                                    <div class="relative flex items-center">
                                        <input 
                                            type="number" 
                                            step="0.05" 
                                            min="0.1" 
                                            x-model.number="item.qty" 
                                            @focus="selectCartItem(idx)" 
                                            @input="syncCartItemQty(idx)"
                                            class="w-14 text-center font-mono font-bold text-xs bg-white border border-slate-200 rounded px-1 py-0.5 tabular-nums focus:outline-none focus:ring-1 focus:ring-slate-400"
                                        >
                                        <span class="text-[10px] text-slate-400 ml-1 font-mono" x-text="item.satuan"></span>
                                    </div>
                                    <button 
                                        type="button" 
                                        @click="adjustCartQty(idx, 1)"
                                        class="w-6 h-6 rounded border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 flex items-center justify-center font-bold text-xs transition-colors"
                                    >+</button>
                                </div>

                                <!-- Subtotal Rata Kanan -->
                                <div class="w-20 text-right shrink-0">
                                    <span class="text-xs font-bold font-mono text-slate-900 tabular-nums">
                                        Rp <span x-text="formatNumber((parseFloat(item.qty) || 0) * (parseFloat(item.harga) || 0))"></span>
                                    </span>
                                </div>

                                <!-- Hapus Item -->
                                <button 
                                    type="button" 
                                    @click="removeCartItem(idx)"
                                    class="text-slate-400 hover:text-rose-600 p-1 text-xs transition-colors"
                                    title="Hapus item"
                                >&times;</button>
                            </div>

                            <!-- Shortcut berat (3 kg, 5 kg, 7 kg) langsung di dalam baris item kiloan -->
                            <template x-if="item.satuan === 'kg'">
                                <div class="flex items-center gap-1.5 pt-1.5 border-t border-slate-200/60">
                                    <span class="text-[10px] text-slate-400 font-medium">Shortcut:</span>
                                    <div class="flex items-center gap-1">
                                        <button 
                                            type="button" 
                                            @click="setItemWeight(idx, 3)"
                                            class="px-2 py-0.5 text-[10px] rounded border transition-colors font-medium tabular-nums"
                                            :class="parseFloat(item.qty) === 3 ? 'border-blue-600 bg-blue-50 text-blue-700 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'"
                                        >3 kg</button>
                                        <button 
                                            type="button" 
                                            @click="setItemWeight(idx, 5)"
                                            class="px-2 py-0.5 text-[10px] rounded border transition-colors font-medium tabular-nums"
                                            :class="parseFloat(item.qty) === 5 ? 'border-blue-600 bg-blue-50 text-blue-700 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'"
                                        >5 kg</button>
                                        <button 
                                            type="button" 
                                            @click="setItemWeight(idx, 7)"
                                            class="px-2 py-0.5 text-[10px] rounded border transition-colors font-medium tabular-nums"
                                            :class="parseFloat(item.qty) === 7 ? 'border-blue-600 bg-blue-50 text-blue-700 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'"
                                        >7 kg</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Footer Kasir: Estimasi Selesai, Segmented Controls Bayar, Catatan, Total, & Primary CTA -->
                <div class="p-3.5 border-t border-slate-200 bg-slate-50/50 space-y-2.5 shrink-0">
                    <!-- Ringkasan Estimasi Selesai Otomatis Berdasarkan Item Dipilih -->
                    <div class="p-2.5 rounded-lg border flex items-center justify-between transition-colors"
                        :class="isExpress ? 'bg-amber-50/70 border-amber-200' : 'bg-white border-slate-200'"
                    >
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded flex items-center justify-center shrink-0"
                                :class="isExpress ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500'"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold text-slate-500 block leading-tight">Estimasi Selesai:</span>
                                <span class="text-xs font-bold tabular-nums leading-tight" :class="isExpress ? 'text-amber-800' : 'text-slate-800'" x-text="estimasiSelesai"></span>
                            </div>
                        </div>
                        <template x-if="isExpress">
                            <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">
                                Express
                            </span>
                        </template>
                    </div>

                    <!-- Pilihan Status Bayar & Metode: Segmented Control Seragam -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-semibold text-slate-700 block">Status Pembayaran</label>
                        <!-- Segmented Control Status Bayar -->
                        <div class="bg-slate-100 p-1 rounded-lg grid grid-cols-2 gap-1 text-xs">
                            <button 
                                type="button" 
                                @click="paymentStatus = 'Lunas'"
                                class="py-1.5 px-3 rounded-md transition-all text-center select-none"
                                :class="paymentStatus === 'Lunas' 
                                    ? 'bg-white text-slate-900 font-medium shadow-xs' 
                                    : 'text-slate-600 hover:text-slate-900 font-normal'"
                            >
                                Lunas Langsung
                            </button>
                            <button 
                                type="button" 
                                @click="paymentStatus = 'Belum Lunas'"
                                class="py-1.5 px-3 rounded-md transition-all text-center select-none"
                                :class="paymentStatus === 'Belum Lunas' 
                                    ? 'bg-white text-slate-900 font-medium shadow-xs' 
                                    : 'text-slate-600 hover:text-slate-900 font-normal'"
                            >
                                Bayar Nanti
                            </button>
                        </div>

                        <!-- Sub-pilihan Metode Bayar jika Lunas (Segmented Control Seragam) -->
                        <div x-show="paymentStatus === 'Lunas'" class="bg-slate-100 p-1 rounded-lg flex items-center gap-1 text-xs">
                            <template x-for="metode in ['Tunai', 'QRIS', 'Transfer']" :key="metode">
                                <button 
                                    type="button" 
                                    @click="paymentMethod = metode"
                                    class="flex-1 py-1 rounded-md transition-all text-center select-none"
                                    :class="paymentMethod === metode 
                                        ? 'bg-white text-slate-900 font-medium shadow-xs' 
                                        : 'text-slate-600 hover:text-slate-900 font-normal'"
                                    x-text="metode"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <!-- Catatan Kondisi Baju (Chip Klik Kecil) & Input Catatan Tambahan -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-semibold text-slate-700">Kondisi Pakaian & Catatan</label>
                            <span x-show="selectedConditions.length > 0" class="text-[10px] text-slate-500 font-medium tabular-nums">
                                <span x-text="selectedConditions.length"></span> dipilih
                            </span>
                        </div>
                        <!-- Chip Klik Kecil Langsung di Atas Input Catatan -->
                        <div class="flex flex-wrap gap-1">
                            <template x-for="chip in conditionOptions" :key="chip">
                                <button 
                                    type="button" 
                                    @click="toggleCondition(chip)"
                                    class="px-2 py-0.5 rounded text-[10px] font-medium border transition-colors select-none"
                                    :class="selectedConditions.includes(chip) 
                                        ? 'bg-slate-900 text-white border-slate-900 font-semibold' 
                                        : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'"
                                    x-text="chip"
                                ></button>
                            </template>
                        </div>
                        <!-- Input Catatan Tambahan -->
                        <input 
                            type="text" 
                            x-model="orderNotes" 
                            placeholder="Catatan tambahan (misal: jangan pakai softener)..."
                            class="w-full text-xs bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400"
                        >
                    </div>

                    <!-- Ringkasan Total Harga (Tabular Nums) -->
                    <div class="pt-2 border-t border-slate-200 flex items-baseline justify-between">
                        <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Total Biaya</span>
                        <div class="text-right">
                            <span class="text-lg font-bold font-mono text-slate-900 tabular-nums">
                                Rp <span x-text="formatNumber(cartTotal())"></span>
                            </span>
                        </div>
                    </div>

                    <!-- 1 PRIMARY CTA PER LAYAR: Simpan & Cetak Nota (Biru Konsisten) -->
                    <button 
                        type="button" 
                        @click="submitOrder()"
                        :disabled="cart.length === 0 || !customerName.trim() || !customerPhone.trim() || isSubmitting"
                        class="w-full py-2.5 px-4 rounded-lg bg-blue-700 hover:bg-blue-800 disabled:bg-blue-700/40 disabled:hover:bg-blue-700/40 disabled:cursor-not-allowed text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-xs"
                    >
                        <span x-show="!isSubmitting">Simpan & Cetak Nota</span>
                        <span x-show="isSubmitting">Menyimpan Order...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- VIEW C: PELANGGAN (DATABASE & RIWAYAT)                    -->
        <!-- ======================================================== -->
        <div x-show="currentView === 'pelanggan'" class="h-full flex flex-col overflow-hidden bg-slate-50">
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Database Pelanggan</h1>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs text-slate-500 font-medium tabular-nums">
                        <span class="font-semibold text-slate-800" x-text="customers.length"></span> pelanggan terdaftar
                    </span>
                </div>
                <button 
                    type="button" 
                    @click="switchView('kasir')"
                    class="bg-blue-700 hover:bg-blue-800 text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors"
                >
                    + Order Baru
                </button>
            </header>
            <div class="flex-1 overflow-auto p-6">
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden max-w-4xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 text-[11px] font-semibold uppercase">
                                <th class="py-2.5 px-4">Nama Pelanggan</th>
                                <th class="py-2.5 px-4">No. WhatsApp</th>
                                <th class="py-2.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="c in customers" :key="c.no_hp">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 px-4 font-semibold text-slate-900" x-text="c.nama_pelanggan"></td>
                                    <td class="py-2.5 px-4 font-mono tabular-nums text-slate-600" x-text="c.no_hp"></td>
                                    <td class="py-2.5 px-4 text-right">
                                        <button 
                                            type="button" 
                                            @click="orderForCustomer(c)"
                                            class="px-2.5 py-1 text-xs rounded border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-medium"
                                        >
                                            Buat Order
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- VIEW D: LAYANAN & HARGA (KATALOG OPERASIONAL)             -->
        <!-- ======================================================== -->
        <div x-show="currentView === 'layanan'" class="h-full flex flex-col overflow-hidden bg-slate-50">
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <h1 class="text-base font-bold text-slate-900 tracking-tight">Katalog Layanan & Tarif</h1>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs text-slate-500 font-medium tabular-nums">
                        <span class="font-semibold text-slate-800" x-text="services.length"></span> jenis layanan
                    </span>
                </div>
                <a 
                    href="{{ route('layanan.index') }}" 
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-medium transition-colors"
                >
                    Kelola Tarif (Master Data) &rarr;
                </a>
            </header>
            <div class="flex-1 overflow-auto p-6">
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden max-w-4xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 text-[11px] font-semibold uppercase">
                                <th class="py-2.5 px-4">Nama Layanan</th>
                                <th class="py-2.5 px-4">Kategori</th>
                                <th class="py-2.5 px-4">Durasi Selesai</th>
                                <th class="py-2.5 px-4 text-right">Tarif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="s in services" :key="s.id">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 px-4">
                                        <p class="font-semibold text-slate-900" x-text="s.nama_layanan"></p>
                                        <p class="text-[11px] text-slate-500" x-text="s.deskripsi"></p>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700" x-text="s.kategori"></span>
                                    </td>
                                    <td class="py-2.5 px-4 text-slate-700" x-text="s.durasi"></td>
                                    <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900 tabular-nums">
                                        Rp <span x-text="formatNumber(s.harga_per_kg)"></span> / <span x-text="s.satuan || 'kg'"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- ======================================================== -->
    <!-- SLIDE-OVER DRAWER (DETAIL ORDER & PREVIEW STRUK 58MM)     -->
    <!-- ======================================================== -->
    <div 
        x-show="drawerOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-hidden" 
        @keydown.window.escape="drawerOpen = false"
    >
        <!-- Overlay -->
        <div 
            x-show="drawerOpen" 
            x-transition:enter="transition-opacity ease-linear duration-200" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            @click="drawerOpen = false" 
            class="fixed inset-0 bg-slate-900/30"
        ></div>

        <!-- Drawer Content (Right Slide-over) -->
        <div class="fixed inset-y-0 right-0 max-w-full flex">
            <div 
                x-show="drawerOpen" 
                x-transition:enter="transform transition ease-in-out duration-200" 
                x-transition:enter-start="translate-x-full" 
                x-transition:enter-end="translate-x-0" 
                x-transition:leave="transform transition ease-in-out duration-200" 
                x-transition:leave-start="translate-x-0" 
                x-transition:leave-end="translate-x-full" 
                class="w-screen max-w-md bg-white border-l border-slate-200 flex flex-col shadow-none"
            >
                <template x-if="selectedOrder">
                    <div class="h-full flex flex-col">
                        <!-- Drawer Header -->
                        <div class="p-4 border-b border-slate-200 flex items-center justify-between shrink-0 bg-slate-50/50">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold font-mono text-slate-900 tabular-nums" x-text="selectedOrder.nota_display || ('#' + selectedOrder.kode_transaksi)"></span>
                                    <span 
                                        class="w-2 h-2 rounded-full shrink-0" 
                                        :class="getStatusDotClass(selectedOrder.status)"
                                    ></span>
                                    <span class="text-xs font-semibold text-slate-700" x-text="normalizeStatus(selectedOrder.status)"></span>
                                    <template x-if="selectedOrder.is_express">
                                        <span class="text-[10px] font-semibold text-amber-700 bg-amber-100 border border-amber-200 px-1.5 py-0.2 rounded tabular-nums" x-text="formatSisaWaktu(selectedOrder)"></span>
                                    </template>
                                </div>
                                <span class="text-[11px] text-slate-500 tabular-nums" x-text="formatDate(selectedOrder.tgl_masuk)"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Quick Status Advance Button in Drawer Header -->
                                <template x-if="['Antrean', 'Masuk'].includes(selectedOrder.status)">
                                    <button 
                                        type="button" 
                                        @click="advanceStatus(selectedOrder, 'Dicuci')"
                                        class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                    >
                                        Mulai Cuci &rarr;
                                    </button>
                                </template>
                                <template x-if="['Dicuci', 'Proses'].includes(selectedOrder.status)">
                                    <button 
                                        type="button" 
                                        @click="advanceStatus(selectedOrder, 'Setrika')"
                                        class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                    >
                                        Mulai Setrika &rarr;
                                    </button>
                                </template>
                                <template x-if="selectedOrder.status === 'Setrika'">
                                    <button 
                                        type="button" 
                                        @click="advanceStatus(selectedOrder, 'Siap Ambil')"
                                        class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                    >
                                        Tandai Siap Ambil &rarr;
                                    </button>
                                </template>
                                <template x-if="selectedOrder.status === 'Siap Ambil'">
                                    <button 
                                        type="button" 
                                        @click="advanceStatus(selectedOrder, 'Selesai')"
                                        class="px-2.5 py-1 text-xs font-medium rounded border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                                    >
                                        Serahkan &rarr;
                                    </button>
                                </template>
                                <button 
                                    type="button" 
                                    @click="drawerOpen = false" 
                                    class="text-slate-400 hover:text-slate-700 p-1 text-base font-bold ml-1"
                                >&times;</button>
                            </div>
                        </div>

                        <!-- Drawer Scrollable Body -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4">
                            <!-- Section Pelanggan -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900" x-text="selectedOrder.nama_pelanggan"></span>
                                    <button 
                                        type="button" 
                                        @click="sendWhatsApp(selectedOrder)"
                                        class="text-[11px] text-emerald-700 font-semibold hover:underline flex items-center gap-1"
                                    >
                                        WhatsApp Chat &rarr;
                                    </button>
                                </div>
                                <p class="text-xs font-mono text-slate-600 tabular-nums" x-text="selectedOrder.no_hp"></p>
                            </div>

                            <!-- Section Detail Item Layanan (Rincian Item Operasional) -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-slate-800">Detail Item Layanan</span>
                                    <span class="text-[11px] text-slate-500 tabular-nums" x-text="getOrderItems(selectedOrder).length + ' item'"></span>
                                </div>
                                <div class="space-y-1.5 divide-y divide-slate-100">
                                    <template x-for="(item, i) in getOrderItems(selectedOrder)" :key="i">
                                        <div class="pt-1.5 first:pt-0 flex items-start justify-between text-xs">
                                            <div>
                                                <span class="font-medium text-slate-900" x-text="item.nama_layanan"></span>
                                                <p class="text-[11px] text-slate-500 tabular-nums">
                                                    <span x-text="item.qty"></span> <span x-text="item.satuan"></span> &times; Rp <span x-text="formatNumber(item.harga)"></span>
                                                </p>
                                            </div>
                                            <span class="font-mono font-semibold text-slate-900 tabular-nums">
                                                Rp <span x-text="formatNumber(item.subtotal || (item.qty * item.harga))"></span>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                                <div class="pt-2 border-t border-slate-200 flex items-baseline justify-between text-xs font-semibold">
                                    <span class="text-slate-600">Total Tagihan</span>
                                    <span class="font-mono text-sm font-bold text-slate-900 tabular-nums">
                                        Rp <span x-text="formatNumber(selectedOrder.total_harga)"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Section Operasional: Status Bayar & Input Nomor Rak -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-600 font-medium">Status Pembayaran:</span>
                                    <div class="flex items-center gap-2">
                                        <template x-if="selectedOrder.status_pembayaran === 'Lunas'">
                                            <span class="px-2 py-0.5 rounded font-semibold bg-emerald-100 text-emerald-800 text-[11px]">
                                                Lunas (<span x-text="selectedOrder.metode_pembayaran || 'Tunai'"></span>)
                                            </span>
                                        </template>
                                        <template x-if="selectedOrder.status_pembayaran !== 'Lunas'">
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-2 py-0.5 rounded font-semibold bg-rose-100 text-rose-800 text-[11px]">
                                                    Belum Lunas
                                                </span>
                                                <button 
                                                    type="button" 
                                                    @click="markOrderPaid(selectedOrder)"
                                                    class="px-2 py-0.5 text-[11px] rounded bg-emerald-700 text-white font-medium hover:bg-emerald-800"
                                                >
                                                    Set Lunas
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Input Nomor Rak -->
                                <div>
                                    <label class="text-[11px] font-semibold text-slate-700 block mb-1">Nomor / Posisi Rak Penyimpanan</label>
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="text" 
                                            x-model="rackInput" 
                                            placeholder="Contoh: Rak B-2"
                                            class="flex-1 text-xs font-mono bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-400"
                                        >
                                        <button 
                                            type="button" 
                                            @click="saveRackLocation(selectedOrder)"
                                            class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 font-medium"
                                        >
                                            Simpan Rak
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Kondisi Baju & Pakaian -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg space-y-1.5 text-xs">
                                <span class="font-semibold text-slate-800 block">Kondisi Pakaian & Catatan Khusus</span>
                                <template x-if="selectedOrder.kondisi_baju">
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200" x-text="selectedOrder.kondisi_baju"></span>
                                    </div>
                                </template>
                                <p class="text-slate-600 text-[11px]" x-text="selectedOrder.catatan || 'Tidak ada catatan tambahan.'"></p>
                            </div>

                            <!-- Preview Cetak Struk Thermal 58mm (Monospaced) -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-800">Preview Cetak Struk (58mm)</span>
                                    <button 
                                        type="button" 
                                        @click="triggerPrint(selectedOrder)"
                                        class="text-xs text-blue-700 font-semibold hover:underline flex items-center gap-1"
                                    >
                                        Cetak Struk Sekarang &rarr;
                                    </button>
                                </div>

                                <!-- Box Struk Monospace 58mm Authentic Standard -->
                                <div class="w-[280px] mx-auto bg-white border border-dashed border-slate-300 p-3 font-mono text-[11px] leading-tight text-slate-900 shadow-none">
                                    <div class="text-center font-bold text-xs">WASHFLOW</div>
                                    <div class="text-center text-[9px] text-slate-500">Jl. Operasional No. 88</div>
                                    <div class="text-center text-[9px] text-slate-500">WA: 0812-3456-7890</div>
                                    
                                    <div class="border-t border-dashed border-slate-300 my-2"></div>
                                    
                                    <div class="flex justify-between tabular-nums">
                                        <span>Nota:</span>
                                        <span class="font-bold" x-text="selectedOrder.nota_display || ('#' + selectedOrder.kode_transaksi)"></span>
                                    </div>
                                    <div class="flex justify-between tabular-nums">
                                        <span>Tgl:</span>
                                        <span x-text="formatDate(selectedOrder.tgl_masuk)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Plg:</span>
                                        <span class="font-medium truncate max-w-[150px]" x-text="selectedOrder.nama_pelanggan"></span>
                                    </div>

                                    <div class="border-t border-dashed border-slate-300 my-2"></div>

                                    <!-- Items Multi-Item -->
                                    <div class="space-y-1">
                                        <template x-for="(item, i) in getOrderItems(selectedOrder)" :key="i">
                                            <div>
                                                <div class="font-semibold" x-text="item.nama_layanan"></div>
                                                <div class="flex justify-between text-[10px] text-slate-600 tabular-nums">
                                                    <span><span x-text="item.qty"></span> <span x-text="item.satuan"></span> x Rp <span x-text="formatNumber(item.harga)"></span></span>
                                                    <span class="font-bold text-slate-900">Rp <span x-text="formatNumber(item.subtotal || (item.qty * item.harga))"></span></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="border-t border-dashed border-slate-300 my-2"></div>

                                    <div class="flex justify-between text-xs font-bold tabular-nums">
                                        <span>TOTAL:</span>
                                        <span>Rp <span x-text="formatNumber(selectedOrder.total_harga)"></span></span>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-slate-600">
                                        <span>STATUS:</span>
                                        <span class="font-bold uppercase" x-text="selectedOrder.status_pembayaran"></span>
                                    </div>

                                    <template x-if="selectedOrder.lokasi_rak">
                                        <div class="flex justify-between text-[10px] font-bold text-slate-800 mt-1">
                                            <span>RAK:</span>
                                            <span x-text="selectedOrder.lokasi_rak"></span>
                                        </div>
                                    </template>

                                    <div class="border-t border-dashed border-slate-300 my-2"></div>

                                    <div class="text-center text-[18px] tracking-widest text-slate-400 py-1 font-sans">
                                        ||| | |||| || |||
                                    </div>
                                    <div class="text-center text-[9px] text-slate-500">
                                        Simpan nota ini saat pengambilan.<br>
                                        Terima kasih!
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Drawer Footer -->
                        <div class="p-3 border-t border-slate-200 bg-white flex items-center justify-between gap-2 shrink-0">
                            <button 
                                type="button" 
                                @click="drawerOpen = false"
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 hover:bg-slate-50 font-medium"
                            >
                                Tutup
                            </button>
                            <button 
                                type="button" 
                                @click="triggerPrint(selectedOrder)"
                                class="px-4 py-1.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-xs font-semibold"
                            >
                                Cetak Struk 58mm
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- HIDDEN THERMAL PRINT AREA (FOR WINDOW.PRINT())            -->
    <!-- ======================================================== -->
    <div id="thermal-receipt-print-area" class="hidden print:block">
        <template x-if="printOrder">
            <div style="font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 11px; line-height: 1.25; width: 58mm; color: black; background: white; padding: 2mm;">
                <div style="text-align: center; font-weight: bold; font-size: 13px;">WASHFLOW</div>
                <div style="text-align: center; font-size: 9px;">Jl. Operasional No. 88, Jakarta</div>
                <div style="text-align: center; font-size: 9px;">Telp/WA: 0812-3456-7890</div>
                <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Nota:</span>
                    <span style="font-weight: bold;" x-text="printOrder.nota_display || ('#' + printOrder.kode_transaksi)"></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Tgl:</span>
                    <span x-text="formatDate(printOrder.tgl_masuk)"></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Pelanggan:</span>
                    <span style="font-weight: bold;" x-text="printOrder.nama_pelanggan"></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>No. HP:</span>
                    <span x-text="printOrder.no_hp"></span>
                </div>
                <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                
                <!-- Items Detail Multi-Item -->
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <template x-for="(item, i) in getOrderItems(printOrder)" :key="i">
                        <div>
                            <div style="font-weight: bold;" x-text="item.nama_layanan"></div>
                            <div style="display: flex; justify-content: space-between; font-size: 10px;">
                                <span><span x-text="item.qty"></span> <span x-text="item.satuan"></span> x Rp <span x-text="formatNumber(item.harga)"></span></span>
                                <span style="font-weight: bold;">Rp <span x-text="formatNumber(item.subtotal || (item.qty * item.harga))"></span></span>
                            </div>
                        </div>
                    </template>
                </div>

                <template x-if="printOrder.kondisi_baju">
                    <div style="font-size: 9px; margin-top: 4px;">[Kondisi: <span x-text="printOrder.kondisi_baju"></span>]</div>
                </template>
                <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 12px;">
                    <span>TOTAL:</span>
                    <span>Rp <span x-text="formatNumber(printOrder.total_harga)"></span></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 10px;">
                    <span>BAYAR:</span>
                    <span style="font-weight: bold;" x-text="printOrder.status_pembayaran + (printOrder.metode_pembayaran ? ' (' + printOrder.metode_pembayaran + ')' : '')"></span>
                </div>
                <template x-if="printOrder.lokasi_rak">
                    <div style="display: flex; justify-content: space-between; font-size: 10px;">
                        <span>RAK:</span>
                        <span style="font-weight: bold;" x-text="printOrder.lokasi_rak"></span>
                    </div>
                </template>
                <div style="border-top: 1px dashed black; margin: 4px 0;"></div>
                <div style="text-align: center; letter-spacing: 2px; font-size: 14px; margin: 4px 0;">
                    ||| | |||| || |||
                </div>
                <div style="text-align: center; font-size: 9px; margin-top: 2px;">
                    Simpan nota ini saat pengambilan.<br>Terima kasih!
                </div>
            </div>
        </template>
    </div>

</div>

<!-- Alpine.js POS Architecture Logic -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('posSystem', () => ({
        // Navigation & Layout State
        currentView: '{{ request()->query("tab") === "kasir" ? "kasir" : "antrean" }}',
        sidebarCollapsed: false,
        pipelineTab: '{{ $pipelineTab ?? "Semua" }}',
        filterExpress: '{{ $filterExpress ?? "all" }}',
        searchQuery: '{{ $search ?? "" }}',

        // Pipeline Counts
        counts: {
            semua: {{ $semuaCount ?? 0 }},
            antrean: {{ $antreanCount ?? 0 }},
            dicuci: {{ $dicuciCount ?? 0 }},
            setrika: {{ $setrikaCount ?? 0 }},
            siap_ambil: {{ $siapAmbilCount ?? 0 }},
            selesai: {{ $selesaiCount ?? 0 }},
            express: {{ $expressCount ?? 0 }},
        },

        // Tabs config
        pipelineTabs: [
            { id: 'Semua', label: 'Semua' },
            { id: 'Antrean', label: 'Antrean' },
            { id: 'Dicuci', label: 'Dicuci' },
            { id: 'Setrika', label: 'Setrika' },
            { id: 'Siap Ambil', label: 'Siap Ambil' },
            { id: 'Selesai', label: 'Selesai' },
        ],

        // Master Data
        allOrders: @json($transaksiList),
        filteredOrders: [],
        services: @json($daftarLayanan),
        customers: @json($pelangganList),

        // Slide-over Drawer
        selectedOrder: null,
        drawerOpen: false,
        rackInput: '',
        printOrder: null,

        // Kasir POS State
        selectedCategory: 'Semua',
        serviceCategories: ['Semua', 'Kiloan', 'Satuan', 'Bedcover', 'Express'],
        serviceSearch: '',
        activeWeightInput: 3.00,
        activeCartIndex: null,
        conditionOptions: [
            'Noda Membandel',
            'Mudah Luntur',
            'Kancing Lepas',
            'Tanpa Pewangi',
            'Pewangi Ekstra',
            'Bahan Sutra / Tipis'
        ],
        selectedConditions: [],
        customerName: '',
        customerPhone: '',
        customerDropdown: false,
        cart: [],
        isExpress: false,
        paymentStatus: 'Lunas',
        paymentMethod: 'Tunai',
        orderNotes: '',
        isSubmitting: false,

        init() {
            this.filterOrders();
        },

        switchView(view) {
            this.currentView = view;
            if (view === 'antrean') {
                this.filterOrders();
            }
        },

        getTabCount(tabId) {
            switch(tabId) {
                case 'Semua': return this.counts.semua;
                case 'Antrean': return this.counts.antrean;
                case 'Dicuci': return this.counts.dicuci;
                case 'Setrika': return this.counts.setrika;
                case 'Siap Ambil': return this.counts.siap_ambil;
                case 'Selesai': return this.counts.selesai;
                default: return 0;
            }
        },

        filterOrders() {
            let list = [...this.allOrders];

            // Filter Pipeline Tab
            if (this.pipelineTab !== 'Semua') {
                if (this.pipelineTab === 'Antrean') {
                    list = list.filter(o => ['Antrean', 'Masuk'].includes(o.status));
                } else if (this.pipelineTab === 'Dicuci') {
                    list = list.filter(o => ['Dicuci', 'Proses'].includes(o.status));
                } else {
                    list = list.filter(o => o.status === this.pipelineTab);
                }
            }

            // Filter Express
            if (this.filterExpress === 'express') {
                list = list.filter(o => o.is_express === true || (o.layanan && o.layanan.is_express));
            }

            // Filter Search Query
            if (this.searchQuery.trim().length > 0) {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(o => 
                    (o.kode_transaksi && o.kode_transaksi.toLowerCase().includes(q)) ||
                    (o.nama_pelanggan && o.nama_pelanggan.toLowerCase().includes(q)) ||
                    (o.no_hp && o.no_hp.toLowerCase().includes(q))
                );
            }

            this.filteredOrders = list;
        },

        get filteredServices() {
            let list = [...this.services];

            if (this.selectedCategory !== 'Semua') {
                list = list.filter(s => s.kategori === this.selectedCategory);
            }

            if (this.serviceSearch.trim().length > 0) {
                const q = this.serviceSearch.toLowerCase();
                list = list.filter(s => 
                    s.nama_layanan.toLowerCase().includes(q) ||
                    (s.deskripsi && s.deskripsi.toLowerCase().includes(q))
                );
            }

            return list;
        },

        get customerSuggestions() {
            if (!this.customerName.trim()) return this.customers.slice(0, 5);
            const q = this.customerName.toLowerCase();
            return this.customers.filter(c => 
                c.nama_pelanggan.toLowerCase().includes(q) ||
                c.no_hp.toLowerCase().includes(q)
            ).slice(0, 6);
        },

        selectCustomer(c) {
            this.customerName = c.nama_pelanggan;
            this.customerPhone = c.no_hp;
            this.customerDropdown = false;
        },

        orderForCustomer(c) {
            this.selectCustomer(c);
            this.switchView('kasir');
        },

        addToCart(service) {
            const existingIdx = this.cart.findIndex(i => i.layanan_id === service.id);
            const isKiloan = (service.satuan || 'kg') === 'kg';
            const defaultQty = isKiloan ? parseFloat(this.activeWeightInput || 3) : 1;
            const kat = (service.kategori || '').toLowerCase();
            const isExpressService = !!(service.is_express || kat.includes('express') || kat.includes('kilat') || kat.includes('ekspres') || (service.nama_layanan && (service.nama_layanan.toLowerCase().includes('express') || service.nama_layanan.toLowerCase().includes('kilat') || service.nama_layanan.toLowerCase().includes('ekspres'))));

            if (existingIdx > -1) {
                this.cart[existingIdx].qty = parseFloat((this.cart[existingIdx].qty + 1).toFixed(2));
                this.activeCartIndex = existingIdx;
            } else {
                this.cart.push({
                    layanan_id: service.id,
                    nama_layanan: service.nama_layanan,
                    kategori: service.kategori,
                    satuan: service.satuan || 'kg',
                    qty: defaultQty,
                    harga: parseFloat(service.harga_per_kg),
                    is_express: isExpressService,
                    durasi: service.durasi || '2 Hari',
                });
                this.activeCartIndex = this.cart.length - 1;
            }

            if (isKiloan) {
                this.activeWeightInput = this.cart[this.activeCartIndex].qty;
            }

            this.syncExpressState();
        },

        syncExpressState() {
            this.isExpress = this.cart.some(i => i.is_express);
        },

        get estimasiSelesai() {
            if (this.cart.length === 0) {
                return 'Pilih layanan di kiri';
            }
            if (this.isExpress) {
                return 'Hari ini (Express ~6 Jam)';
            }
            if (this.cart.length === 1 && this.cart[0].durasi) {
                return `${this.cart[0].durasi} (Reguler)`;
            }
            const durations = this.cart.map(i => (i.durasi || '').toLowerCase());
            if (durations.some(d => d.includes('3') || d.includes('4'))) {
                return '3-4 Hari (Reguler)';
            }
            if (durations.some(d => d.includes('1') && !d.includes('jam'))) {
                return '1 Hari (Besok)';
            }
            return '2 Hari (Reguler)';
        },

        getCartItem(serviceId) {
            return this.cart.find(i => i.layanan_id === serviceId);
        },

        getCategoryAccentClass(service) {
            if (this.getCartItem(service.id)) {
                return 'border-l-[3px] border-l-blue-600';
            }
            const kat = (service.kategori || '').toLowerCase();
            const isExp = !!(service.is_express || kat.includes('express') || kat.includes('kilat') || kat.includes('ekspres') || (service.nama_layanan && (service.nama_layanan.toLowerCase().includes('express') || service.nama_layanan.toLowerCase().includes('kilat') || service.nama_layanan.toLowerCase().includes('ekspres'))));
            if (isExp) return 'border-l-[3px] border-l-amber-500';
            if (kat.includes('kiloan')) return 'border-l-[3px] border-l-blue-500';
            if (kat.includes('bedcover')) return 'border-l-[3px] border-l-teal-500';
            return 'border-l-[3px] border-l-slate-400';
        },

        getCategoryPillClass(service) {
            const kat = (service.kategori || '').toLowerCase();
            const isExp = !!(service.is_express || kat.includes('express') || kat.includes('kilat') || kat.includes('ekspres') || (service.nama_layanan && (service.nama_layanan.toLowerCase().includes('express') || service.nama_layanan.toLowerCase().includes('kilat') || service.nama_layanan.toLowerCase().includes('ekspres'))));
            if (isExp) return 'bg-amber-50 text-amber-700 border border-amber-200/80';
            if (kat.includes('kiloan')) return 'bg-blue-50 text-blue-700 border border-blue-200/80';
            if (kat.includes('bedcover')) return 'bg-teal-50 text-teal-700 border border-teal-200/80';
            return 'bg-slate-100 text-slate-700 border border-slate-200/80';
        },

        setItemWeight(idx, weight) {
            const w = parseFloat(weight);
            if (isNaN(w) || w <= 0) return;
            if (this.cart[idx]) {
                this.cart[idx].qty = w;
                this.activeCartIndex = idx;
                this.activeWeightInput = w;
            }
        },

        selectCartItem(index) {
            this.activeCartIndex = index;
            const item = this.cart[index];
            if (item && item.satuan === 'kg') {
                this.activeWeightInput = item.qty;
            }
        },

        syncCartItemQty(index) {
            const item = this.cart[index];
            if (item && item.satuan === 'kg' && this.activeCartIndex === index) {
                this.activeWeightInput = item.qty;
            }
        },

        adjustCartQty(index, delta) {
            const item = this.cart[index];
            const isKiloan = item.satuan === 'kg';
            const step = isKiloan ? 0.5 : 1;
            const newQty = parseFloat((item.qty + (delta * step)).toFixed(2));

            if (newQty <= 0) {
                this.cart.splice(index, 1);
                if (this.activeCartIndex === index) this.activeCartIndex = null;
            } else {
                item.qty = newQty;
                if (isKiloan) {
                    this.activeWeightInput = newQty;
                }
            }
            this.syncExpressState();
        },

        removeCartItem(index) {
            this.cart.splice(index, 1);
            if (this.activeCartIndex === index) this.activeCartIndex = null;
            this.syncExpressState();
        },

        setWeight(weight) {
            this.activeWeightInput = parseFloat(weight);
            this.updateActiveKiloanWeight();
        },

        updateActiveKiloanWeight() {
            const w = parseFloat(this.activeWeightInput);
            if (isNaN(w) || w <= 0) return;
            if (this.activeCartIndex !== null && this.cart[this.activeCartIndex] && this.cart[this.activeCartIndex].satuan === 'kg') {
                this.cart[this.activeCartIndex].qty = w;
                return;
            }
            const kiloanItem = this.cart.find(i => i.satuan === 'kg');
            if (kiloanItem) {
                kiloanItem.qty = w;
            }
        },

        toggleCondition(condition) {
            const idx = this.selectedConditions.indexOf(condition);
            if (idx > -1) {
                this.selectedConditions.splice(idx, 1);
            } else {
                this.selectedConditions.push(condition);
            }
        },

        cartTotal() {
            return this.cart.reduce((sum, item) => sum + ((parseFloat(item.qty) || 0) * (parseFloat(item.harga) || 0)), 0);
        },

        async submitOrder() {
            if (this.cart.length === 0 || !this.customerName.trim() || !this.customerPhone.trim()) {
                alert('Silakan lengkapi nama pelanggan, nomor WhatsApp, dan minimal 1 layanan.');
                return;
            }

            this.isSubmitting = true;

            const payload = {
                nama_pelanggan: this.customerName,
                no_hp: this.customerPhone,
                items: this.cart,
                is_express: this.isExpress,
                status_pembayaran: this.paymentStatus,
                metode_pembayaran: this.paymentStatus === 'Lunas' ? this.paymentMethod : null,
                catatan: this.orderNotes,
                kondisi_baju: this.selectedConditions.join(', '),
            };

            try {
                const response = await fetch('{{ route("pos.order.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    // Update client state
                    this.allOrders.unshift(data.transaksi);
                    this.refreshCounts();
                    this.filterOrders();

                    // Reset cart
                    this.cart = [];
                    this.activeCartIndex = null;
                    this.customerName = '';
                    this.customerPhone = '';
                    this.orderNotes = '';
                    this.selectedConditions = [];
                    this.isExpress = false;

                    // Open Slide-over Drawer with print preview
                    this.openOrderDrawer(data.transaksi);
                    this.switchView('antrean');

                    // Trigger 58mm thermal print dialog automatically
                    this.triggerPrint(data.transaksi);
                } else {
                    alert('Gagal menyimpan order. Periksa data kembali.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.isSubmitting = false;
            }
        },

        async advanceStatus(order, nextStatus) {
            try {
                const response = await fetch(`/pos/transaksi/${order.id}/advance`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ 
                        status: nextStatus,
                        lokasi_rak: this.rackInput.trim() || undefined 
                    })
                });

                const data = await response.json();
                if (data.success) {
                    order.status = data.status;
                    if (data.lokasi_rak) {
                        order.lokasi_rak = data.lokasi_rak;
                        this.rackInput = data.lokasi_rak;
                    }
                    if (this.selectedOrder && this.selectedOrder.id === order.id) {
                        this.selectedOrder.status = data.status;
                        if (data.lokasi_rak) this.selectedOrder.lokasi_rak = data.lokasi_rak;
                    }
                    this.refreshCounts();
                    this.filterOrders();
                }
            } catch (err) {
                console.error(err);
            }
        },

        async saveRackLocation(order) {
            if (!this.rackInput.trim()) return;

            try {
                const response = await fetch(`/pos/transaksi/${order.id}/rak`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ lokasi_rak: this.rackInput })
                });

                const data = await response.json();
                if (data.success) {
                    order.lokasi_rak = data.lokasi_rak;
                    if (this.selectedOrder) {
                        this.selectedOrder.lokasi_rak = data.lokasi_rak;
                    }
                    this.filterOrders();
                }
            } catch (err) {
                console.error(err);
            }
        },

        async markOrderPaid(order) {
            try {
                const response = await fetch(`/pos/transaksi/${order.id}/pembayaran`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ metode_pembayaran: 'Tunai' })
                });

                const data = await response.json();
                if (data.success) {
                    order.status_pembayaran = 'Lunas';
                    order.metode_pembayaran = data.metode_pembayaran;
                    if (this.selectedOrder) {
                        this.selectedOrder.status_pembayaran = 'Lunas';
                        this.selectedOrder.metode_pembayaran = data.metode_pembayaran;
                    }
                }
            } catch (err) {
                console.error(err);
            }
        },

        refreshCounts() {
            this.counts.antrean = this.allOrders.filter(o => ['Antrean', 'Masuk'].includes(o.status)).length;
            this.counts.dicuci = this.allOrders.filter(o => ['Dicuci', 'Proses'].includes(o.status)).length;
            this.counts.setrika = this.allOrders.filter(o => o.status === 'Setrika').length;
            this.counts.siap_ambil = this.allOrders.filter(o => o.status === 'Siap Ambil').length;
            this.counts.selesai = this.allOrders.filter(o => o.status === 'Selesai').length;
            this.counts.express = this.allOrders.filter(o => (o.is_express || (o.layanan && o.layanan.is_express)) && o.status !== 'Selesai').length;
            this.counts.semua = this.allOrders.length;
        },

        openOrderDrawer(order) {
            this.selectedOrder = order;
            this.rackInput = order.lokasi_rak || '';
            this.drawerOpen = true;
        },

        triggerPrint(order) {
            this.printOrder = order;
            this.$nextTick(() => {
                window.print();
            });
        },

        getOrderItems(order) {
            if (!order) return [];
            if (order.items_detail && Array.isArray(order.items_detail) && order.items_detail.length > 0) {
                return order.items_detail;
            }
            return [{
                layanan_id: order.layanan_id,
                nama_layanan: order.layanan ? order.layanan.nama_layanan : 'Layanan Laundry',
                satuan: order.layanan ? (order.layanan.satuan || 'kg') : 'kg',
                qty: parseFloat(order.berat) || 1,
                harga: parseFloat(order.layanan ? order.layanan.harga_per_kg : 0),
                subtotal: parseFloat(order.total_harga) || 0,
            }];
        },

        formatSisaWaktu(order) {
            if (!order) return '';
            if (order.sisa_waktu) return order.sisa_waktu;
            if (!order.deadline) return order.is_express ? 'Express' : 'Reguler';
            const now = new Date();
            const deadline = new Date(order.deadline);
            const diffMs = deadline - now;
            if (diffMs <= 0) return 'Lewat deadline';
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            if (diffHours < 1) return `Sisa ${diffMins} mnt`;
            return `Sisa ${diffHours} jam`;
        },

        sendWhatsApp(order) {
            let phone = (order.no_hp || '').replace(/\D/g, '');
            if (phone.startsWith('0')) {
                phone = '62' + phone.substring(1);
            } else if (phone.startsWith('8')) {
                phone = '62' + phone;
            }
            const nota = order.nota_display || ('#' + order.kode_transaksi);
            const status = this.normalizeStatus(order.status);
            const total = this.formatRupiah(order.total_harga);

            const msg = `Halo Kak *${order.nama_pelanggan}*, cucian Anda di WashFlow dengan nota *${nota}* statusnya saat ini: *${status}*. Total tagihan: *${total}* (${order.status_pembayaran}). Terima kasih telah mencuci di WashFlow!`;
            const url = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;
            window.open(url, '_blank');
        },

        getStatusDotClass(status) {
            switch(status) {
                case 'Antrean':
                case 'Masuk': return 'bg-slate-400';
                case 'Dicuci':
                case 'Proses': return 'bg-blue-600';
                case 'Setrika': return 'bg-amber-500';
                case 'Siap Ambil': return 'bg-emerald-600';
                case 'Selesai': return 'bg-slate-600';
                default: return 'bg-slate-400';
            }
        },

        normalizeStatus(status) {
            if (status === 'Masuk') return 'Antrean';
            if (status === 'Proses') return 'Dicuci';
            return status;
        },

        formatNumber(val) {
            const num = parseFloat(val) || 0;
            return new Intl.NumberFormat('id-ID').format(num);
        },

        formatRupiah(val) {
            return 'Rp ' + this.formatNumber(val);
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
        },

        formatDeadline(dateStr) {
            if (!dateStr) return 'Reguler';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        }
    }));
});
</script>
</x-pos-layout>
