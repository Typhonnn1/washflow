<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private Layanan $layananReguler;
    private Layanan $layananEkspres;
    private User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->layananReguler = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $this->layananEkspres = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'durasi' => '1-2 hari',
        ]);

        $this->kasir = User::factory()->create(['role' => 'kasir']);
    }

    public function test_guests_cannot_view_or_create_transactions(): void
    {
        $response = $this->get('/transaksi');
        $response->assertRedirect('/login');

        $response = $this->get('/transaksi/create');
        $response->assertRedirect('/login');
    }

    public function test_kasir_can_create_new_transaction_with_auto_price_calculation(): void
    {
        $response = $this->actingAs($this->kasir)->post('/transaksi', [
            'nama_pelanggan' => 'Ibu Rahma',
            'no_hp' => '081298765432',
            'layanan_id' => $this->layananReguler->id,
            'berat' => 4.5,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
            'catatan' => 'Baju anak jangan pakai pemutih',
        ]);

        $transaksi = Transaksi::where('nama_pelanggan', 'Ibu Rahma')->first();
        $this->assertNotNull($transaksi);
        $response->assertRedirect(route('transaksi.show', $transaksi));

        // 4.5 kg * 7000 = 31500
        $this->assertEquals(31500, (float) $transaksi->total_harga);
        $this->assertEquals('Masuk', $transaksi->status);
        $this->assertEquals('Belum Lunas', $transaksi->status_pembayaran);
        $this->assertEquals($this->kasir->id, $transaksi->user_id);
        $this->assertStringStartsWith('TRX-', $transaksi->kode_transaksi);
    }

    public function test_can_create_transaction_with_two_decimal_place_weight(): void
    {
        $response = $this->actingAs($this->kasir)->post('/transaksi', [
            'nama_pelanggan' => 'Ibu Nita',
            'no_hp' => '081233445566',
            'layanan_id' => $this->layananEkspres->id,
            'berat' => 2.25,
            'status' => 'Masuk',
            'status_pembayaran' => 'Lunas',
        ]);

        $transaksi = Transaksi::where('nama_pelanggan', 'Ibu Nita')->first();
        $this->assertNotNull($transaksi);
        $response->assertRedirect(route('transaksi.show', $transaksi));

        // 2.25 kg * 12000 = 27000
        $this->assertEquals(2.25, (float) $transaksi->berat);
        $this->assertEquals(27000, (float) $transaksi->total_harga);
        $this->assertEquals('2,25 kg', $transaksi->berat_formatted);
    }

    public function test_status_transition_from_proses_to_selesai(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Pak Joko',
            'no_hp' => '081333444555',
            'layanan_id' => $this->layananEkspres->id,
            'berat' => 2.0,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        // 1. Advance to Proses
        $response = $this->actingAs($this->kasir)->patch("/transaksi/{$transaksi->id}/status", [
            'status' => 'Proses',
        ]);
        $response->assertRedirect();
        $transaksi->refresh();
        $this->assertEquals('Proses', $transaksi->status);
        $this->assertNull($transaksi->tgl_selesai);

        // 2. Advance to Selesai
        $response = $this->actingAs($this->kasir)->patch("/transaksi/{$transaksi->id}/status", [
            'status' => 'Selesai',
        ]);
        $response->assertRedirect();
        $transaksi->refresh();
        $this->assertEquals('Selesai', $transaksi->status);
        $this->assertNotNull($transaksi->tgl_selesai);
    }

    public function test_payment_status_update_to_lunas(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Dewi Sartika',
            'no_hp' => '081999888777',
            'layanan_id' => $this->layananReguler->id,
            'berat' => 3.0,
            'status' => 'Proses',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->patch("/transaksi/{$transaksi->id}/pembayaran", [
            'status_pembayaran' => 'Lunas',
        ]);

        $response->assertRedirect();
        $transaksi->refresh();
        $this->assertEquals('Lunas', $transaksi->status_pembayaran);
    }

    public function test_transaksi_detail_and_receipt_page(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Andi Wijaya',
            'no_hp' => '081234567890',
            'layanan_id' => $this->layananReguler->id,
            'berat' => 2.5,
            'status' => 'Proses',
            'status_pembayaran' => 'Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->get("/transaksi/{$transaksi->id}");
        $response->assertStatus(200);
        $response->assertSee($transaksi->kode_transaksi);
        $response->assertSee('Andi Wijaya');
        $response->assertSee('Reguler');
        $response->assertSee('Rp 17.500'); // 2.5 * 7000
    }

    public function test_transaksi_edit_and_update(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Bambang',
            'no_hp' => '08111222333',
            'layanan_id' => $this->layananReguler->id,
            'berat' => 2.0,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->get("/transaksi/{$transaksi->id}/edit");
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir)->put("/transaksi/{$transaksi->id}", [
            'nama_pelanggan' => 'Bambang Sudirman',
            'no_hp' => '08111222333',
            'layanan_id' => $this->layananEkspres->id, // switched to Ekspres (12000/kg)
            'berat' => 3.0,
            'status' => 'Proses',
            'status_pembayaran' => 'Lunas',
            'catatan' => 'Pewangi ekstra',
        ]);

        $response->assertRedirect(route('transaksi.show', $transaksi));
        $transaksi->refresh();

        $this->assertEquals('Bambang Sudirman', $transaksi->nama_pelanggan);
        $this->assertEquals($this->layananEkspres->id, $transaksi->layanan_id);
        $this->assertEquals(3.0, (float) $transaksi->berat);
        // 3.0 * 12000 = 36000
        $this->assertEquals(36000, (float) $transaksi->total_harga);
        $this->assertEquals('Proses', $transaksi->status);
        $this->assertEquals('Lunas', $transaksi->status_pembayaran);
    }

    public function test_transaksi_deletion(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Test Delete',
            'no_hp' => '081234567890',
            'layanan_id' => $this->layananReguler->id,
            'berat' => 1.0,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->delete("/transaksi/{$transaksi->id}");
        $response->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseMissing('transaksi', ['id' => $transaksi->id]);
    }
}
