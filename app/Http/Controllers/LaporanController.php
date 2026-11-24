<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Display the financial recap and transaction report with filters and metrics.
     */
    public function index(Request $request): View
    {
        $dariTanggal = $request->query('dari_tanggal');
        $sampaiTanggal = $request->query('sampai_tanggal');
        $layananId = $request->query('layanan_id');
        $status = $request->query('status');
        $statusPembayaran = $request->query('status_pembayaran');

        $query = Transaksi::with(['layanan', 'user'])->latest('tgl_masuk');

        if ($dariTanggal) {
            $query->whereDate('tgl_masuk', '>=', $dariTanggal);
        }
        if ($sampaiTanggal) {
            $query->whereDate('tgl_masuk', '<=', $sampaiTanggal);
        }
        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Clone query for metrics calculations before pagination
        $transaksiSemua = (clone $query)->get();

        $totalTransaksi = $transaksiSemua->count();
        $totalBerat = (float) $transaksiSemua->sum('berat');
        $totalPendapatan = (float) $transaksiSemua->where('status_pembayaran', 'Lunas')->sum('total_harga');
        $totalBelumLunas = (float) $transaksiSemua->where('status_pembayaran', 'Belum Lunas')->sum('total_harga');

        // Breakdown per layanan
        $breakdownLayanan = $transaksiSemua->groupBy('layanan_id')->map(function ($items) {
            $first = $items->first();
            $layananNama = $first && $first->layanan ? $first->layanan->nama_layanan : 'Lainnya';
            return [
                'nama_layanan' => $layananNama,
                'jumlah_transaksi' => $items->count(),
                'total_berat' => (float) $items->sum('berat'),
                'total_omzet' => (float) $items->sum('total_harga'),
                'total_lunas' => (float) $items->where('status_pembayaran', 'Lunas')->sum('total_harga'),
            ];
        });

        // Transactions list for table
        $transaksiList = $query->paginate(15)->withQueryString();
        $daftarLayanan = Layanan::orderBy('nama_layanan')->get();

        return view('laporan.index', compact(
            'transaksiList',
            'daftarLayanan',
            'dariTanggal',
            'sampaiTanggal',
            'layananId',
            'status',
            'statusPembayaran',
            'totalTransaksi',
            'totalBerat',
            'totalPendapatan',
            'totalBelumLunas',
            'breakdownLayanan'
        ));
    }

    /**
     * Printable view of the report.
     */
    public function cetak(Request $request): View
    {
        $dariTanggal = $request->query('dari_tanggal');
        $sampaiTanggal = $request->query('sampai_tanggal');
        $layananId = $request->query('layanan_id');
        $status = $request->query('status');
        $statusPembayaran = $request->query('status_pembayaran');

        $query = Transaksi::with(['layanan', 'user'])->latest('tgl_masuk');

        if ($dariTanggal) {
            $query->whereDate('tgl_masuk', '>=', $dariTanggal);
        }
        if ($sampaiTanggal) {
            $query->whereDate('tgl_masuk', '<=', $sampaiTanggal);
        }
        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        $transaksis = $query->get();

        $totalTransaksi = $transaksis->count();
        $totalBerat = (float) $transaksis->sum('berat');
        $totalPendapatan = (float) $transaksis->where('status_pembayaran', 'Lunas')->sum('total_harga');
        $totalBelumLunas = (float) $transaksis->where('status_pembayaran', 'Belum Lunas')->sum('total_harga');

        $layananFilter = $layananId ? Layanan::find($layananId) : null;

        return view('laporan.cetak', compact(
            'transaksis',
            'dariTanggal',
            'sampaiTanggal',
            'layananFilter',
            'status',
            'statusPembayaran',
            'totalTransaksi',
            'totalBerat',
            'totalPendapatan',
            'totalBelumLunas'
        ));
    }
}
