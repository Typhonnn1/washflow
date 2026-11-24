<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanKeuanganTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $reguler;
    private Layanan $ekspres;
    private Layanan $superkilat;
    private User $admin;
    private User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->kasir = User::factory()->create(['role' => 'kasir']);

        $this->reguler = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $this->ekspres = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'durasi' => '1-2 hari',
        ]);

        $this->superkilat = Layanan::create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        // Trx 1: Reguler, 3.0 kg, total 21.000, Lunas
        Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 1',
            'no_hp' => '0811111111',
            'layanan_id' => $this->reguler->id,
            'berat' => 3.0,
            'status' => 'Selesai',
            'status_pembayaran' => 'Lunas',
            'tgl_masuk' => now()->subDays(2),
        ]);

        // Trx 2: Ekspres, 2.0 kg, total 24.000, Belum Lunas
        Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 2',
            'no_hp' => '0822222222',
            'layanan_id' => $this->ekspres->id,
            'berat' => 2.0,
            'status' => 'Proses',
            'status_pembayaran' => 'Belum Lunas',
            'tgl_masuk' => now()->subDay(),
        ]);

        // Trx 3: Superkilat, 1.0 kg, total 25.000, Lunas
        Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 3',
            'no_hp' => '0833333333',
            'layanan_id' => $this->superkilat->id,
            'berat' => 1.0,
            'status' => 'Selesai',
            'status_pembayaran' => 'Lunas',
            'tgl_masuk' => now(),
        ]);
    }

    public function test_guests_cannot_view_laporan(): void
    {
        $response = $this->get('/laporan');
        $response->assertRedirect('/login');

        $response = $this->get('/laporan/cetak');
        $response->assertRedirect('/login');
    }

    public function test_laporan_displays_accurate_summary_and_service_breakdown(): void
    {
        $response = $this->actingAs($this->admin)->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Rekap Transaksi & Laporan Keuangan');
        $response->assertSee('Rekap Transaksi Laundry');

        // Total 3 transaksi
        $response->assertSee('Pelanggan 1');
        $response->assertSee('Pelanggan 2');
        $response->assertSee('Pelanggan 3');

        // Total weight: 3 + 2 + 1 = 6.0 kg
        $response->assertSee('6,0');

        // Pendapatan lunas: 21.000 + 25.000 = 46.000
        $response->assertSee('46.000');

        // Belum lunas: 24.000
        $response->assertSee('24.000');

        // Services in breakdown
        $response->assertSee('Reguler');
        $response->assertSee('Ekspres');
        $response->assertSee('Superkilat');
    }

    public function test_laporan_filter_by_layanan(): void
    {
        // Filter only Reguler
        $response = $this->actingAs($this->kasir)->get('/laporan?layanan_id=' . $this->reguler->id);

        $response->assertStatus(200);
        $response->assertSee('Pelanggan 1');
        $response->assertDontSee('Pelanggan 2');
        $response->assertDontSee('Pelanggan 3');
    }

    public function test_laporan_filter_by_payment_status(): void
    {
        // Filter only Lunas
        $response = $this->actingAs($this->admin)->get('/laporan?status_pembayaran=Lunas');

        $response->assertStatus(200);
        $response->assertSee('Pelanggan 1');
        $response->assertSee('Pelanggan 3');
        $response->assertDontSee('Pelanggan 2');
    }

    public function test_laporan_filter_by_date_range(): void
    {
        $today = now()->toDateString();

        // Filter only today
        $response = $this->actingAs($this->admin)->get("/laporan?dari_tanggal={$today}&sampai_tanggal={$today}");

        $response->assertStatus(200);
        $response->assertSee('Pelanggan 3');
        $response->assertDontSee('Pelanggan 1'); // 2 days ago
    }

    public function test_printable_report_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get('/laporan/cetak');

        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI TRANSAKSI DAN LAPORAN KEUANGAN');
        $response->assertSee('Pelanggan 1');
        $response->assertSee('Pelanggan 2');
        $response->assertSee('Pelanggan 3');
    }
}
