<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Display a listing of the laundry services.
     */
    public function index(Request $request): View
    {
        $keyword = $request->query('cari');

        $layananList = Layanan::withCount('transaksis')
            ->cari($keyword)
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('layanan.index', compact('layananList', 'keyword'));
    }

    /**
     * Show the form for creating a new laundry service (Admin only).
     */
    public function create(): View
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created laundry service in storage (Admin only).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'durasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg tidak boleh bernilai negatif.',
            'durasi.required' => 'Durasi pengerjaan wajib diisi.',
        ]);

        $layanan = Layanan::create($validated);

        return redirect()->route('layanan.index')
            ->with('success', "Layanan '{$layanan->nama_layanan}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified laundry service (Admin only).
     */
    public function edit(Layanan $layanan): View
    {
        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified laundry service in storage (Admin only).
     */
    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'durasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg tidak boleh bernilai negatif.',
            'durasi.required' => 'Durasi pengerjaan wajib diisi.',
        ]);

        $layanan->update($validated);

        return redirect()->route('layanan.index')
            ->with('success', "Layanan '{$layanan->nama_layanan}' berhasil diperbarui.");
    }

    /**
     * Remove the specified laundry service from storage (Admin only).
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        $nama = $layanan->nama_layanan;
        $transaksiCount = $layanan->transaksis()->count();

        $layanan->delete();

        $message = "Layanan '{$nama}' berhasil dihapus.";
        if ($transaksiCount > 0) {
            $message .= " ({$transaksiCount} transaksi terkait juga telah dibersihkan).";
        }

        return redirect()->route('layanan.index')->with('success', $message);
    }
}
