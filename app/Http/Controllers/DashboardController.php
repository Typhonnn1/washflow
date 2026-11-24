<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with stats, recent transactions, and services overview.
     */
    public function index(): View
    {
        $totalTransaksi = Transaksi::count();
        $transaksiMasuk = Transaksi::where('status', 'Masuk')->count();
        $transaksiProses = Transaksi::where('status', 'Proses')->count();
        $transaksiSelesai = Transaksi::where('status', 'Selesai')->count();

        $totalPendapatan = (float) Transaksi::where('status_pembayaran', 'Lunas')->sum('total_harga');
        $totalBerat = (float) Transaksi::sum('berat');

        // Recent 6 transactions with relations
        $transaksiTerbaru = Transaksi::with(['layanan', 'user'])
            ->latest('tgl_masuk')
            ->take(6)
            ->get();

        // Active services with transaction count and revenue
        $daftarLayanan = Layanan::withCount('transaksis')->get();

        return view('dashboard', compact(
            'totalTransaksi',
            'transaksiMasuk',
            'transaksiProses',
            'transaksiSelesai',
            'totalPendapatan',
            'totalBerat',
            'transaksiTerbaru',
            'daftarLayanan'
        ));
    }
}
