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
        $admin = User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@laundry.com'],
            [
                'name' => 'Kasir WashFlow',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );

        // 2. Data Layanan
        $reguler = Layanan::firstOrCreate(
            ['nama_layanan' => 'Reguler'],
            [
                'kategori' => 'Kiloan',
                'harga_per_kg' => 7000,
                'satuan' => 'kg',
                'durasi' => '3-4 hari',
                'is_express' => false,
                'deskripsi' => 'Layanan cuci reguler hemat 7.000/kg (3-4 hari)',
            ]
        );

        $ekspres = Layanan::firstOrCreate(
            ['nama_layanan' => 'Ekspres'],
            [
                'kategori' => 'Express',
                'harga_per_kg' => 12000,
                'satuan' => 'kg',
                'durasi' => '1-2 hari',
                'is_express' => true,
                'deskripsi' => 'Layanan cuci kilat 12.000/kg (1-2 hari)',
            ]
        );

        $superkilat = Layanan::firstOrCreate(
            ['nama_layanan' => 'Superkilat'],
            [
                'kategori' => 'Express',
                'harga_per_kg' => 25000,
                'satuan' => 'kg',
                'durasi' => '5 jam selesai',
                'is_express' => true,
                'deskripsi' => 'Layanan cuci superkilat 25.000/kg (5 jam selesai)',
            ]
        );

        // Layanan Tambahan Realistis Indonesia
        Layanan::firstOrCreate(
            ['nama_layanan' => 'Cuci Komplit Reguler'],
            [
                'kategori' => 'Kiloan',
                'harga_per_kg' => 7000,
                'satuan' => 'kg',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Cuci, kering, dan setrika rapi wangi tahan lama',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Cuci Kering Lipat'],
            [
                'kategori' => 'Kiloan',
                'harga_per_kg' => 5000,
                'satuan' => 'kg',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Cuci bersih, kering pengering mesin, dan dilipat rapi',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Setrika Saja'],
            [
                'kategori' => 'Kiloan',
                'harga_per_kg' => 4500,
                'satuan' => 'kg',
                'durasi' => '1 Hari',
                'is_express' => false,
                'deskripsi' => 'Setrika uap profesional rapi dan wangi',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Express 6 Jam'],
            [
                'kategori' => 'Express',
                'harga_per_kg' => 14000,
                'satuan' => 'kg',
                'durasi' => '6 Jam',
                'is_express' => true,
                'deskripsi' => 'Layanan prioritas cepat selesai dalam 6 jam',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Bedcover Besar'],
            [
                'kategori' => 'Bedcover',
                'harga_per_kg' => 35000,
                'satuan' => 'pcs',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Bedcover King / Queen size cuci bersih higienis',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Bedcover Sedang'],
            [
                'kategori' => 'Bedcover',
                'harga_per_kg' => 25000,
                'satuan' => 'pcs',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Bedcover Single size cuci wangi dan bersih',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Selimut Tebal'],
            [
                'kategori' => 'Bedcover',
                'harga_per_kg' => 20000,
                'satuan' => 'pcs',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Selimut bulu atau bahan tebal cuci bersih lembut',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Jas / Blazer'],
            [
                'kategori' => 'Satuan',
                'harga_per_kg' => 30000,
                'satuan' => 'pcs',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Dry clean & steaming jas atau blazer formal',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Gamis / Gaun Pesta'],
            [
                'kategori' => 'Satuan',
                'harga_per_kg' => 35000,
                'satuan' => 'pcs',
                'durasi' => '2 Hari',
                'is_express' => false,
                'deskripsi' => 'Perawatan khusus pakaian pesta berpayet dan halus',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Kemeja Premium'],
            [
                'kategori' => 'Satuan',
                'harga_per_kg' => 15000,
                'satuan' => 'pcs',
                'durasi' => '1 Hari',
                'is_express' => false,
                'deskripsi' => 'Cuci dan setrika kemeja kerja detail kerah & manset',
            ]
        );

        Layanan::firstOrCreate(
            ['nama_layanan' => 'Sepatu Canvas / Sneakers'],
            [
                'kategori' => 'Satuan',
                'harga_per_kg' => 30000,
                'satuan' => 'pcs',
                'durasi' => '3 Hari',
                'is_express' => false,
                'deskripsi' => 'Deep cleaning sepatu sneakers dan canvas luar-dalam',
            ]
        );

        // 3. Sampel Transaksi Menyeluruh untuk Pipeline POS Operasional
        Transaksi::firstOrCreate(
            ['kode_transaksi' => 'TRX-' . date('Ymd') . '-0001'],
            [
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'layanan_id' => $reguler->id,
                'is_express' => false,
                'berat' => 3.5,
                'total_harga' => 3.5 * 7000,
                'status' => 'Masuk',
                'status_pembayaran' => 'Belum Lunas',
                'metode_pembayaran' => 'Tunai',
                'catatan' => 'Baju kemeja putih jangan dicampur',
                'kondisi_baju' => 'Tanpa Pewangi',
                'lokasi_rak' => null,
                'deadline' => now()->addDays(2),
                'items_detail' => [
                    [
                        'layanan_id' => $reguler->id,
                        'nama_layanan' => 'Cuci Komplit Reguler',
                        'kategori' => 'Kiloan',
                        'satuan' => 'kg',
                        'qty' => 3.5,
                        'harga' => 7000,
                        'subtotal' => 24500,
                    ]
                ],
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
                'is_express' => true,
                'berat' => 2.0,
                'total_harga' => 2.0 * 12000,
                'status' => 'Proses',
                'status_pembayaran' => 'Lunas',
                'metode_pembayaran' => 'QRIS',
                'catatan' => 'Pewangi lavender',
                'kondisi_baju' => 'Mudah Luntur',
                'lokasi_rak' => null,
                'deadline' => now()->addHours(2),
                'items_detail' => [
                    [
                        'layanan_id' => $ekspres->id,
                        'nama_layanan' => 'Express 6 Jam',
                        'kategori' => 'Express',
                        'satuan' => 'kg',
                        'qty' => 2.0,
                        'harga' => 12000,
                        'subtotal' => 24000,
                    ]
                ],
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
                'is_express' => true,
                'berat' => 1.5,
                'total_harga' => 1.5 * 25000,
                'status' => 'Selesai',
                'status_pembayaran' => 'Lunas',
                'metode_pembayaran' => 'Tunai',
                'catatan' => 'Jas dan celana bahan',
                'kondisi_baju' => 'Kancing Lepas',
                'lokasi_rak' => 'Rak B-2',
                'deadline' => now()->subHour(),
                'items_detail' => [
                    [
                        'layanan_id' => $superkilat->id,
                        'nama_layanan' => 'Express Superkilat',
                        'kategori' => 'Express',
                        'satuan' => 'kg',
                        'qty' => 1.5,
                        'harga' => 25000,
                        'subtotal' => 37500,
                    ]
                ],
                'user_id' => $admin->id,
                'tgl_masuk' => now()->subHours(6),
                'tgl_selesai' => now()->subHour(),
            ]
        );
    }
}
