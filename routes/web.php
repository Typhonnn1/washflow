<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Models\Layanan;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    $layananList = \Illuminate\Support\Facades\Schema::hasTable('layanan')
        ? Layanan::orderBy('harga_per_kg', 'asc')->get()
        : collect();
    return view('welcome', compact('layananList'));
})->name('home');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS & Sistem Operasional Antrean (CleanCloud + Shopify Admin Standard)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/antrean', [PosController::class, 'index'])->name('pos.antrean');
    Route::get('/pos/kasir', [PosController::class, 'index'])->name('pos.kasir');
    Route::post('/pos/order', [PosController::class, 'storeOrder'])->name('pos.order.store');
    Route::post('/pos/transaksi/{transaksi}/advance', [PosController::class, 'advanceStatus'])->name('pos.transaksi.advance');
    Route::patch('/pos/transaksi/{transaksi}/rak', [PosController::class, 'updateRak'])->name('pos.transaksi.rak');
    Route::patch('/pos/transaksi/{transaksi}/pembayaran', [PosController::class, 'updatePembayaran'])->name('pos.transaksi.pembayaran');
    Route::get('/pos/transaksi/{transaksi}/struk', [PosController::class, 'getStruk'])->name('pos.transaksi.struk');
    Route::get('/pos/pelanggan', [PosController::class, 'getPelanggan'])->name('pos.pelanggan');

    // Data Layanan (Viewable by both Admin and Kasir)
    Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');

    // Layanan Management (Restricted to Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/layanan/create', [LayananController::class, 'create'])->name('layanan.create');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
        Route::match(['put', 'patch'], '/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
    });

    // Transaksi Laundry (Accessible by Admin and Kasir)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::match(['put', 'patch'], '/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::patch('/transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.status');
    Route::patch('/transaksi/{transaksi}/pembayaran', [TransaksiController::class, 'updatePembayaran'])->name('transaksi.pembayaran');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // Rekap Transaksi Laundry & Laporan Keuangan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
