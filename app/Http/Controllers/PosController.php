<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display the POS operational dashboard (Antrean Cucian & Kasir POS).
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $pipelineTab = $request->query('status', 'Semua');
        $filterExpress = $request->query('express', 'all'); // 'all' or 'express'

        // Base query for all transactions
        $query = Transaksi::with(['layanan', 'user'])->latest('tgl_masuk');

        // Search by nota code, customer name, or phone number
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Express filter
        if ($filterExpress === 'express') {
            $query->where(function ($q) {
                $q->where('is_express', true)
                  ->orWhereHas('layanan', function ($lq) {
                      $lq->where('nama_layanan', 'like', '%express%')
                         ->orWhere('nama_layanan', 'like', '%ekspres%')
                         ->orWhere('nama_layanan', 'like', '%superkilat%')
                         ->orWhere('is_express', true);
                  });
            });
        }

        // Pipeline Tab status filter
        match ($pipelineTab) {
            'Antrean' => $query->whereIn('status', ['Antrean', 'Masuk']),
            'Dicuci' => $query->whereIn('status', ['Dicuci', 'Proses']),
            'Setrika' => $query->where('status', 'Setrika'),
            'Siap Ambil' => $query->where('status', 'Siap Ambil'),
            'Selesai' => $query->where('status', 'Selesai'),
            default => null, // 'Semua' - no status filter
        };

        $transaksiList = $query->get();

        // Calculate pipeline counters
        $semuaCount = Transaksi::count();
        $antreanCount = Transaksi::whereIn('status', ['Antrean', 'Masuk'])->count();
        $dicuciCount = Transaksi::whereIn('status', ['Dicuci', 'Proses'])->count();
        $setrikaCount = Transaksi::where('status', 'Setrika')->count();
        $siapAmbilCount = Transaksi::where('status', 'Siap Ambil')->count();
        $selesaiCount = Transaksi::where('status', 'Selesai')->count();
        $expressCount = Transaksi::where(function ($q) {
            $q->where('is_express', true)
              ->orWhereHas('layanan', function ($lq) {
                  $lq->where('nama_layanan', 'like', '%express%')
                     ->orWhere('nama_layanan', 'like', '%ekspres%')
                     ->orWhere('nama_layanan', 'like', '%superkilat%')
                     ->orWhere('is_express', true);
              });
        })->whereNotIn('status', ['Selesai'])->count();

        // Catalog of services for POS screen
        $daftarLayanan = Layanan::orderBy('kategori')->orderBy('nama_layanan')->get();

        // Distinct customer list for quick autocomplete
        $pelangganList = Transaksi::latest('id')
            ->select('nama_pelanggan', 'no_hp')
            ->get()
            ->makeHidden(['nota_display', 'sisa_waktu', 'status_dot_class', 'normalized_status', 'harga_rupiah', 'berat_formatted'])
            ->unique('no_hp')
            ->values()
            ->take(20);

        if ($request->wantsJson()) {
            return response()->json([
                'transaksi' => $transaksiList,
                'counts' => [
                    'semua' => $semuaCount,
                    'antrean' => $antreanCount,
                    'dicuci' => $dicuciCount,
                    'setrika' => $setrikaCount,
                    'siap_ambil' => $siapAmbilCount,
                    'selesai' => $selesaiCount,
                    'express' => $expressCount,
                ],
                'layanan' => $daftarLayanan,
                'pelanggan' => $pelangganList,
            ]);
        }

        return view('pos.index', compact(
            'transaksiList',
            'semuaCount',
            'antreanCount',
            'dicuciCount',
            'setrikaCount',
            'siapAmbilCount',
            'selesaiCount',
            'expressCount',
            'daftarLayanan',
            'pelangganList',
            'search',
            'pipelineTab',
            'filterExpress'
        ));
    }

    /**
     * Store a newly created order from the POS screen.
     */
    public function storeOrder(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:25'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.layanan_id' => ['required', 'exists:layanan,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'items.*.harga' => ['required', 'numeric', 'gte:0'],
            'is_express' => ['nullable', 'boolean'],
            'status_pembayaran' => ['required', 'in:Lunas,Belum Lunas'],
            'metode_pembayaran' => ['nullable', 'string', 'in:Tunai,QRIS,Transfer'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'kondisi_baju' => ['nullable', 'string', 'max:255'],
        ]);

        $isExpress = (bool) ($validated['is_express'] ?? false);
        $primaryLayananId = $validated['items'][0]['layanan_id'];
        $primaryLayanan = Layanan::find($primaryLayananId);

        // Calculate total weight and total price
        $totalHarga = 0;
        $totalBerat = 0;
        $itemsDetail = [];

        foreach ($validated['items'] as $item) {
            $layanan = Layanan::find($item['layanan_id']);
            $qty = (float) $item['qty'];
            $harga = (float) $item['harga'];
            $subtotal = round($qty * $harga, 2);
            $totalHarga += $subtotal;

            if ($layanan && ($layanan->satuan ?? 'kg') === 'kg') {
                $totalBerat += $qty;
            } elseif ($layanan && ($layanan->satuan ?? 'kg') === 'pcs') {
                $totalBerat += ($qty * 0.5); // estimated equivalent weight
            }

            $itemsDetail[] = [
                'layanan_id' => $layanan ? $layanan->id : null,
                'nama_layanan' => $layanan ? $layanan->nama_layanan : 'Layanan Laundry',
                'kategori' => $layanan ? $layanan->kategori : 'Kiloan',
                'satuan' => $layanan ? ($layanan->satuan ?? 'kg') : 'kg',
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        // If express, calculate 6 hours deadline, else 2 days
        $deadline = $isExpress ? now()->addHours(6) : now()->addDays(2);

        $transaksi = Transaksi::create([
            'kode_transaksi' => Transaksi::generateKodeTransaksi(),
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'no_hp' => $validated['no_hp'],
            'layanan_id' => $primaryLayananId,
            'is_express' => $isExpress,
            'berat' => max(round($totalBerat, 2), 0.5),
            'total_harga' => $totalHarga,
            'status' => 'Antrean',
            'status_pembayaran' => $validated['status_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'] ?? ($validated['status_pembayaran'] === 'Lunas' ? 'Tunai' : null),
            'catatan' => $validated['catatan'] ?? null,
            'kondisi_baju' => $validated['kondisi_baju'] ?? null,
            'lokasi_rak' => null,
            'deadline' => $deadline,
            'items_detail' => $itemsDetail,
            'user_id' => Auth::id(),
            'tgl_masuk' => now(),
        ]);

        $transaksi->load(['layanan', 'user']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat dan masuk ke antrean!',
                'transaksi' => $transaksi,
                'nota' => $transaksi->nota_display,
            ]);
        }

        return redirect()->route('pos.index', ['tab' => 'antrean'])
            ->with('success', "Order {$transaksi->nota_display} berhasil dibuat!");
    }

    /**
     * Advance pipeline status to the next step.
     */
    public function advanceStatus(Request $request, Transaksi $transaksi): JsonResponse|RedirectResponse
    {
        $current = $transaksi->status;
        $nextStatus = match ($current) {
            'Antrean', 'Masuk' => 'Dicuci',
            'Dicuci', 'Proses' => 'Setrika',
            'Setrika' => 'Siap Ambil',
            'Siap Ambil' => 'Selesai',
            default => 'Selesai',
        };

        // If explicit status requested in body, use that
        if ($request->has('status') && in_array($request->status, ['Antrean', 'Dicuci', 'Setrika', 'Siap Ambil', 'Selesai', 'Masuk', 'Proses'])) {
            $nextStatus = $request->status;
        }

        $updateData = ['status' => $nextStatus];

        // If advancing to Siap Ambil and no rack yet, auto-assign rack if provided or default
        if ($nextStatus === 'Siap Ambil' && empty($transaksi->lokasi_rak)) {
            $updateData['lokasi_rak'] = $request->input('lokasi_rak', 'Rak A-' . rand(1, 8));
        }

        if ($nextStatus === 'Selesai') {
            $updateData['tgl_selesai'] = now();
        }

        $transaksi->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $transaksi->status,
                'lokasi_rak' => $transaksi->lokasi_rak,
                'message' => "Status order {$transaksi->nota_display} diperbarui menjadi {$nextStatus}!",
            ]);
        }

        return redirect()->back()->with('success', "Status order {$transaksi->nota_display} diperbarui!");
    }

    /**
     * Update rack location (Lokasi Rak) for an order.
     */
    public function updateRak(Request $request, Transaksi $transaksi): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'lokasi_rak' => ['required', 'string', 'max:50'],
        ]);

        $transaksi->update([
            'lokasi_rak' => $validated['lokasi_rak'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'lokasi_rak' => $transaksi->lokasi_rak,
                'message' => "Lokasi rak {$transaksi->nota_display} disimpan: {$transaksi->lokasi_rak}!",
            ]);
        }

        return redirect()->back()->with('success', "Lokasi rak berhasil diperbarui!");
    }

    /**
     * Update payment status to Lunas.
     */
    public function updatePembayaran(Request $request, Transaksi $transaksi): JsonResponse|RedirectResponse
    {
        $metode = $request->input('metode_pembayaran', 'Tunai');

        $transaksi->update([
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => $metode,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status_pembayaran' => 'Lunas',
                'metode_pembayaran' => $metode,
                'message' => "Order {$transaksi->nota_display} telah dilunasi!",
            ]);
        }

        return redirect()->back()->with('success', "Pembayaran berhasil dicatat Lunas!");
    }

    /**
     * Get thermal receipt 58mm data and HTML snippet.
     */
    public function getStruk(Transaksi $transaksi): JsonResponse|View
    {
        $transaksi->load(['layanan', 'user']);

        if (request()->wantsJson()) {
            return response()->json([
                'transaksi' => $transaksi,
                'nota' => $transaksi->nota_display,
                'items' => $transaksi->items_detail ?? [
                    [
                        'nama_layanan' => $transaksi->layanan ? $transaksi->layanan->nama_layanan : 'Layanan Laundry',
                        'qty' => (float) $transaksi->berat,
                        'satuan' => 'kg',
                        'harga' => (float) ($transaksi->layanan ? $transaksi->layanan->harga_per_kg : 0),
                        'subtotal' => (float) $transaksi->total_harga,
                    ]
                ],
            ]);
        }

        return view('pos.struk-58mm', compact('transaksi'));
    }

    /**
     * List customers for quick autocomplete.
     */
    public function getPelanggan(Request $request): JsonResponse
    {
        $q = $request->query('q');

        $query = Transaksi::latest('id')->select('nama_pelanggan', 'no_hp');

        if ($q) {
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_pelanggan', 'like', "%{$q}%")
                   ->orWhere('no_hp', 'like', "%{$q}%");
            });
        }

        $pelanggan = $query->get()
            ->makeHidden(['nota_display', 'sisa_waktu', 'status_dot_class', 'normalized_status', 'harga_rupiah', 'berat_formatted'])
            ->unique('no_hp')
            ->values()
            ->take(15);

        return response()->json($pelanggan);
    }
}
