<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Display a listing of transactions with filtering and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $statusPembayaran = $request->query('status_pembayaran');
        $layananId = $request->query('layanan_id');
        $dariTanggal = $request->query('dari_tanggal');
        $sampaiTanggal = $request->query('sampai_tanggal');

        $query = Transaksi::with(['layanan', 'user'])->latest('tgl_masuk');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }

        if ($dariTanggal) {
            $query->whereDate('tgl_masuk', '>=', $dariTanggal);
        }

        if ($sampaiTanggal) {
            $query->whereDate('tgl_masuk', '<=', $sampaiTanggal);
        }

        $transaksiList = $query->paginate(10)->withQueryString();
        $daftarLayanan = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.index', compact(
            'transaksiList',
            'daftarLayanan',
            'search',
            'status',
            'statusPembayaran',
            'layananId',
            'dariTanggal',
            'sampaiTanggal'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(): View
    {
        $daftarLayanan = Layanan::orderBy('nama_layanan')->get();
        return view('transaksi.create', compact('daftarLayanan'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'layanan_id' => ['required', 'exists:layanan,id'],
            'berat' => ['required', 'numeric', 'gt:0', 'max:9999.99'],
            'status' => ['required', 'in:Masuk,Proses,Selesai'],
            'status_pembayaran' => ['required', 'in:Belum Lunas,Lunas'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required' => 'Nomor HP pelanggan wajib diisi.',
            'layanan_id.required' => 'Silakan pilih jenis layanan.',
            'layanan_id.exists' => 'Layanan yang dipilih tidak valid.',
            'berat.required' => 'Berat cucian wajib diisi.',
            'berat.numeric' => 'Berat cucian harus berupa angka.',
            'berat.gt' => 'Berat cucian harus lebih dari 0 kg.',
            'status.in' => 'Status pengerjaan tidak valid.',
            'status_pembayaran.in' => 'Status pembayaran tidak valid.',
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $totalHarga = round((float) $validated['berat'] * (float) $layanan->harga_per_kg, 2);

        $transaksi = new Transaksi();
        $transaksi->nama_pelanggan = $validated['nama_pelanggan'];
        $transaksi->no_hp = $validated['no_hp'];
        $transaksi->layanan_id = $validated['layanan_id'];
        $transaksi->berat = $validated['berat'];
        $transaksi->total_harga = $totalHarga;
        $transaksi->status = $validated['status'];
        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->catatan = $validated['catatan'] ?? null;
        $transaksi->user_id = $request->user()?->id;
        $transaksi->tgl_masuk = now();

        if ($validated['status'] === 'Selesai') {
            $transaksi->tgl_selesai = now();
        }

        $transaksi->save();

        return redirect()->route('transaksi.show', $transaksi)
            ->with('success', "Transaksi {$transaksi->kode_transaksi} berhasil dicatat!");
    }

    /**
     * Display the specified transaction detail / receipt.
     */
    public function show(Transaksi $transaksi): View
    {
        $transaksi->load(['layanan', 'user']);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaksi $transaksi): View
    {
        $daftarLayanan = Layanan::orderBy('nama_layanan')->get();
        return view('transaksi.edit', compact('transaksi', 'daftarLayanan'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'layanan_id' => ['required', 'exists:layanan,id'],
            'berat' => ['required', 'numeric', 'gt:0', 'max:9999.99'],
            'status' => ['required', 'in:Masuk,Proses,Selesai'],
            'status_pembayaran' => ['required', 'in:Belum Lunas,Lunas'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required' => 'Nomor HP pelanggan wajib diisi.',
            'layanan_id.required' => 'Silakan pilih jenis layanan.',
            'layanan_id.exists' => 'Layanan yang dipilih tidak valid.',
            'berat.required' => 'Berat cucian wajib diisi.',
            'berat.numeric' => 'Berat cucian harus berupa angka.',
            'berat.gt' => 'Berat cucian harus lebih dari 0 kg.',
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $totalHarga = round((float) $validated['berat'] * (float) $layanan->harga_per_kg, 2);

        $transaksi->nama_pelanggan = $validated['nama_pelanggan'];
        $transaksi->no_hp = $validated['no_hp'];
        $transaksi->layanan_id = $validated['layanan_id'];
        $transaksi->berat = $validated['berat'];
        $transaksi->total_harga = $totalHarga;
        $transaksi->status = $validated['status'];
        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->catatan = $validated['catatan'] ?? null;

        if ($validated['status'] === 'Selesai' && is_null($transaksi->tgl_selesai)) {
            $transaksi->tgl_selesai = now();
        } elseif ($validated['status'] !== 'Selesai') {
            $transaksi->tgl_selesai = null;
        }

        $transaksi->save();

        return redirect()->route('transaksi.show', $transaksi)
            ->with('success', "Transaksi {$transaksi->kode_transaksi} berhasil diperbarui.");
    }

    /**
     * Quick update of execution status (e.g., Proses -> Selesai).
     */
    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Masuk,Proses,Selesai'],
        ]);

        $newStatus = $validated['status'];
        $transaksi->status = $newStatus;

        if ($newStatus === 'Selesai') {
            $transaksi->tgl_selesai = now();
        } elseif ($newStatus !== 'Selesai') {
            $transaksi->tgl_selesai = null;
        }

        $transaksi->save();

        return back()->with('success', "Status transaksi {$transaksi->kode_transaksi} berhasil diubah menjadi: {$newStatus}");
    }

    /**
     * Quick update of payment status (e.g., Belum Lunas -> Lunas).
     */
    public function updatePembayaran(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'status_pembayaran' => ['required', 'in:Belum Lunas,Lunas'],
        ]);

        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->save();

        return back()->with('success', "Status pembayaran {$transaksi->kode_transaksi} berhasil diubah menjadi: {$validated['status_pembayaran']}");
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        $kode = $transaksi->kode_transaksi;
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', "Transaksi {$kode} berhasil dihapus.");
    }
}
