<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin & Kasir
        $admin = User::firstOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $kasir = User::firstOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir Kobay',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );

        // 2. Data Layanan
        $reguler = Layanan::firstOrCreate(
            ['nama_layanan' => 'Reguler'],
            [
                'harga_per_kg' => 7000,
                'durasi' => '3-4 hari',
                'deskripsi' => 'Layanan cuci reguler hemat 7.000/kg (3-4 hari)',
            ]
        );

        $ekspres = Layanan::firstOrCreate(
            ['nama_layanan' => 'Ekspres'],
            [
                'harga_per_kg' => 12000,
                'durasi' => '1-2 hari',
                'deskripsi' => 'Layanan cuci kilat 12.000/kg (1-2 hari)',
            ]
        );

        $superkilat = Layanan::firstOrCreate(
            ['nama_layanan' => 'Superkilat'],
            [
                'harga_per_kg' => 25000,
                'durasi' => '5 jam selesai',
                'deskripsi' => 'Layanan cuci superkilat 25.000/kg (5 jam selesai)',
            ]
        );

        // 3. Sampel Transaksi
        Transaksi::firstOrCreate(
            ['kode_transaksi' => 'TRX-' . date('Ymd') . '-0001'],
            [
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'layanan_id' => $reguler->id,
                'berat' => 3.5,
                'total_harga' => 3.5 * 7000,
                'status' => 'Masuk',
                'status_pembayaran' => 'Belum Lunas',
                'catatan' => 'Baju kemeja putih jangan dicampur',
                'user_id' => $kasir->id,
                'tgl_masuk' => now()->subHours(2),
                'tgl_selesai' => null,
            ]
        );

        Transaksi::firstOrCreate(
            ['kode_transaksi' => 'TRX-' . date('Ymd') . '-0002'],
            [
                'nama_pelanggan' => 'Siti Nurhaliza',
                'no_hp' => '085678901234',
                'layanan_id' => $ekspres->id,
                'berat' => 2.0,
                'total_harga' => 2.0 * 12000,
                'status' => 'Proses',
                'status_pembayaran' => 'Lunas',
                'catatan' => 'Pewangi lavender',
                'user_id' => $kasir->id,
                'tgl_masuk' => now()->subHours(5),
                'tgl_selesai' => null,
            ]
        );

        Transaksi::firstOrCreate(
            ['kode_transaksi' => 'TRX-' . date('Ymd') . '-0003'],
            [
                'nama_pelanggan' => 'Ahmad Fadillah',
                'no_hp' => '087812345678',
                'layanan_id' => $superkilat->id,
                'berat' => 1.5,
                'total_harga' => 1.5 * 25000,
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'catatan' => 'Jas dan celana bahan',
                'user_id' => $admin->id,
                'tgl_masuk' => now()->subHours(6),
                'tgl_selesai' => now()->subHour(),
            ]
        );
    }
}
