<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard_and_see_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        Transaksi::create([
            'nama_pelanggan' => 'Customer A',
            'no_hp' => '08123456789',
            'layanan_id' => $layanan->id,
            'berat' => 3.0,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        Transaksi::create([
            'nama_pelanggan' => 'Customer B',
            'no_hp' => '08123456780',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
            'status' => 'Selesai',
            'status_pembayaran' => 'Lunas',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Manajemen Laundry');
        $response->assertSee('Data Layanan');
        $response->assertSee('Transaksi Laundry');
        $response->assertSee('Rekap Transaksi Laundry');
        $response->assertSee('Customer A');
        $response->assertSee('Customer B');
        $response->assertSee('Reguler');
    }

    public function test_kasir_can_access_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Manajemen Laundry');
        $response->assertSee('Data Layanan');
        $response->assertSee('Transaksi Laundry');
        $response->assertSee('Rekap Transaksi Laundry');
        $response->assertSee('Kasir');
    }
}
