<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosOperationalTest extends TestCase
{
    use RefreshDatabase;

    private User $kasir;
    private User $admin;
    private Layanan $reguler;
    private Layanan $ekspres;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kasir = User::factory()->create(['role' => 'kasir']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->reguler = Layanan::create([
            'nama_layanan' => 'Cuci Komplit Reguler',
            'kategori' => 'Kiloan',
            'harga_per_kg' => 7000,
            'satuan' => 'kg',
            'durasi' => '2 Hari',
            'is_express' => false,
            'deskripsi' => 'Cuci, kering, dan setrika',
        ]);

        $this->ekspres = Layanan::create([
            'nama_layanan' => 'Express 6 Jam',
            'kategori' => 'Express',
            'harga_per_kg' => 14000,
            'satuan' => 'kg',
            'durasi' => '6 Jam',
            'is_express' => true,
            'deskripsi' => 'Layanan kilat 6 jam selesai',
        ]);
    }

    public function test_guests_are_redirected_to_login_from_pos(): void
    {
        $response = $this->get('/pos');
        $response->assertRedirect('/login');

        $response = $this->get('/pos/antrean');
        $response->assertRedirect('/login');

        $response = $this->get('/pos/kasir');
        $response->assertRedirect('/login');
    }

    public function test_kasir_and_admin_can_access_pos(): void
    {
        // Kasir access
        $response = $this->actingAs($this->kasir)->get('/pos');
        $response->assertStatus(200);
        $response->assertSee('Antrean Cucian');
        $response->assertSee('+ Order Baru');
        $response->assertSee('Cuci Komplit Reguler');

        // Admin access
        $response = $this->actingAs($this->admin)->get('/pos');
        $response->assertStatus(200);
        $response->assertSee('Antrean Cucian');
    }

    public function test_pos_kasir_view_ui_revamp_structure(): void
    {
        $response = $this->actingAs($this->kasir)->get('/pos?tab=kasir');
        $response->assertStatus(200);

        // 1. Surface contrast
        $response->assertSee('bg-slate-100');
        $response->assertSee('border-l border-slate-200');

        // 2. Service card & grid
        $response->assertSee('xl:grid-cols-4');
        $response->assertSee('lg:grid-cols-3');
        $response->assertSee('hover:border-blue-500');
        $response->assertSee('hover:bg-blue-50/20');
        $response->assertSee('active:scale-[0.99]');

        // 3. Compact customer inputs & shortcuts
        $response->assertSee('grid grid-cols-2 gap-2', false);
        $response->assertSee('3 kg');
        $response->assertSee('5 kg');
        $response->assertSee('7 kg');
        $response->assertSee('Estimasi Selesai:');

        // 4. Interaction styles & segmented controls
        $response->assertSee('bg-slate-100 p-1 rounded-lg', false);
        $response->assertSee('Lunas Langsung');
        $response->assertSee('Bayar Nanti');
        $response->assertSee('bg-blue-700');
        $response->assertSee('disabled:bg-blue-700/40');
    }

    public function test_pos_shows_accurate_counters_and_json_response(): void
    {
        Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 1',
            'no_hp' => '0811111111',
            'layanan_id' => $this->reguler->id,
            'berat' => 3.0,
            'status' => 'Antrean',
            'status_pembayaran' => 'Belum Lunas',
            'is_express' => false,
        ]);

        Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 2 Express',
            'no_hp' => '0822222222',
            'layanan_id' => $this->ekspres->id,
            'berat' => 2.0,
            'status' => 'Dicuci',
            'status_pembayaran' => 'Lunas',
            'is_express' => true,
            'deadline' => now()->addHours(2),
        ]);

        $response = $this->actingAs($this->kasir)->getJson('/pos');
        $response->assertStatus(200);
        $response->assertJsonPath('counts.semua', 2);
        $response->assertJsonPath('counts.antrean', 1);
        $response->assertJsonPath('counts.dicuci', 1);
        $response->assertJsonPath('counts.express', 1);

        // Verify JSON appends attributes exist on each serialized transaction
        $data = $response->json('transaksi');
        $this->assertNotEmpty($data);
        $expressOrder = collect($data)->firstWhere('nama_pelanggan', 'Pelanggan 2 Express');
        $this->assertNotNull($expressOrder);
        $this->assertArrayHasKey('nota_display', $expressOrder);
        $this->assertArrayHasKey('sisa_waktu', $expressOrder);
        $this->assertStringContainsString('Sisa', $expressOrder['sisa_waktu']);
        $this->assertStringStartsWith('#LND-', $expressOrder['nota_display']);
    }

    public function test_kasir_can_create_new_order_from_pos_json(): void
    {
        $response = $this->actingAs($this->kasir)->postJson('/pos/order', [
            'nama_pelanggan' => 'Hendra Wijaya',
            'no_hp' => '081299887766',
            'items' => [
                [
                    'layanan_id' => $this->reguler->id,
                    'qty' => 4.5,
                    'harga' => 7000,
                ]
            ],
            'is_express' => true,
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => 'QRIS',
            'kondisi_baju' => 'Noda Membandel, Kancing Lepas',
            'catatan' => 'Cuci bersih bagian kerah kemeja',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('transaksi', [
            'nama_pelanggan' => 'Hendra Wijaya',
            'no_hp' => '081299887766',
            'is_express' => true,
            'status' => 'Antrean',
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => 'QRIS',
            'total_harga' => 31500,
            'kondisi_baju' => 'Noda Membandel, Kancing Lepas',
        ]);

        $transaksi = Transaksi::where('nama_pelanggan', 'Hendra Wijaya')->first();
        $this->assertNotNull($transaksi->deadline);
        $this->assertTrue($transaksi->deadline->greaterThan(now()));
    }

    public function test_pos_order_validation_rules(): void
    {
        $response = $this->actingAs($this->kasir)->postJson('/pos/order', [
            'nama_pelanggan' => '',
            'no_hp' => '',
            'items' => [],
            'status_pembayaran' => 'Lunas',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nama_pelanggan', 'no_hp', 'items']);
    }

    public function test_pos_pipeline_status_progression(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Sari Indah',
            'no_hp' => '0812344321',
            'layanan_id' => $this->reguler->id,
            'berat' => 2.0,
            'status' => 'Antrean',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        // 1. Advance from Antrean -> Dicuci
        $response = $this->actingAs($this->kasir)->postJson("/pos/transaksi/{$transaksi->id}/advance");
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'Dicuci');
        $this->assertEquals('Dicuci', $transaksi->fresh()->status);

        // 2. Advance from Dicuci -> Setrika
        $response = $this->actingAs($this->kasir)->postJson("/pos/transaksi/{$transaksi->id}/advance");
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'Setrika');
        $this->assertEquals('Setrika', $transaksi->fresh()->status);

        // 3. Advance from Setrika -> Siap Ambil (with rack assignment)
        $response = $this->actingAs($this->kasir)->postJson("/pos/transaksi/{$transaksi->id}/advance", [
            'lokasi_rak' => 'Rak B-2'
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'Siap Ambil');
        $this->assertEquals('Siap Ambil', $transaksi->fresh()->status);
        $this->assertEquals('Rak B-2', $transaksi->fresh()->lokasi_rak);

        // 4. Advance from Siap Ambil -> Selesai
        $response = $this->actingAs($this->kasir)->postJson("/pos/transaksi/{$transaksi->id}/advance");
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'Selesai');
        $this->assertEquals('Selesai', $transaksi->fresh()->status);
        $this->assertNotNull($transaksi->fresh()->tgl_selesai);
    }

    public function test_pos_update_rack_location(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Doni Prasetyo',
            'no_hp' => '081999888777',
            'layanan_id' => $this->reguler->id,
            'berat' => 1.5,
            'status' => 'Siap Ambil',
            'status_pembayaran' => 'Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->patchJson("/pos/transaksi/{$transaksi->id}/rak", [
            'lokasi_rak' => 'Rak C-5'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('lokasi_rak', 'Rak C-5');
        $this->assertEquals('Rak C-5', $transaksi->fresh()->lokasi_rak);
    }

    public function test_pos_mark_payment_paid(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Lina Marlina',
            'no_hp' => '087711223344',
            'layanan_id' => $this->reguler->id,
            'berat' => 2.0,
            'status' => 'Antrean',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        $response = $this->actingAs($this->kasir)->patchJson("/pos/transaksi/{$transaksi->id}/pembayaran", [
            'metode_pembayaran' => 'QRIS'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status_pembayaran', 'Lunas');
        $response->assertJsonPath('metode_pembayaran', 'QRIS');
        $this->assertEquals('Lunas', $transaksi->fresh()->status_pembayaran);
        $this->assertEquals('QRIS', $transaksi->fresh()->metode_pembayaran);
    }

    public function test_pos_thermal_receipt_view_and_json(): void
    {
        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Pak Bambang',
            'no_hp' => '081344556677',
            'layanan_id' => $this->reguler->id,
            'berat' => 3.5,
            'total_harga' => 24500,
            'status' => 'Siap Ambil',
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => 'Tunai',
            'lokasi_rak' => 'Rak A-1',
        ]);

        // HTML 58mm view
        $response = $this->actingAs($this->kasir)->get("/pos/transaksi/{$transaksi->id}/struk");
        $response->assertStatus(200);
        $response->assertSee('WASHFLOW');
        $response->assertSee('Pak Bambang');
        $response->assertSee('Rak A-1');

        // JSON struk data
        $responseJson = $this->actingAs($this->kasir)->getJson("/pos/transaksi/{$transaksi->id}/struk");
        $responseJson->assertStatus(200);
        $responseJson->assertJsonPath('transaksi.nama_pelanggan', 'Pak Bambang');
    }

    public function test_pos_pelanggan_autocomplete(): void
    {
        Transaksi::create([
            'nama_pelanggan' => 'Raden Mas Kusumo',
            'no_hp' => '081299990000',
            'layanan_id' => $this->reguler->id,
            'berat' => 1.0,
        ]);

        $response = $this->actingAs($this->kasir)->getJson('/pos/pelanggan?q=Raden');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nama_pelanggan' => 'Raden Mas Kusumo',
            'no_hp' => '081299990000',
        ]);
    }

    public function test_pos_multi_item_order_creation_and_struk_breakdown(): void
    {
        $bedcover = Layanan::create([
            'nama_layanan' => 'Bedcover Besar',
            'kategori' => 'Bedcover',
            'harga_per_kg' => 35000,
            'satuan' => 'pcs',
            'durasi' => '2 Hari',
            'is_express' => false,
        ]);

        $response = $this->actingAs($this->kasir)->postJson('/pos/order', [
            'nama_pelanggan' => 'Ibu Ratna',
            'no_hp' => '081555666777',
            'items' => [
                [
                    'layanan_id' => $this->reguler->id,
                    'qty' => 3.0,
                    'harga' => 7000,
                ],
                [
                    'layanan_id' => $bedcover->id,
                    'qty' => 1.0,
                    'harga' => 35000,
                ],
            ],
            'is_express' => false,
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => 'Transfer',
        ]);

        $response->assertStatus(200);
        $transaksiId = $response->json('transaksi.id');
        $this->assertNotNull($transaksiId);

        $transaksi = Transaksi::find($transaksiId);
        $this->assertEquals(56000, $transaksi->total_harga);
        $this->assertIsArray($transaksi->items_detail);
        $this->assertCount(2, $transaksi->items_detail);

        // Test struk JSON endpoint returns all items
        $strukResponse = $this->actingAs($this->kasir)->getJson("/pos/transaksi/{$transaksi->id}/struk");
        $strukResponse->assertStatus(200);
        $strukResponse->assertJsonCount(2, 'items');
        $strukResponse->assertJsonPath('items.0.nama_layanan', 'Cuci Komplit Reguler');
        $strukResponse->assertJsonPath('items.1.nama_layanan', 'Bedcover Besar');

        // Test struk HTML contains both items
        $htmlResponse = $this->actingAs($this->kasir)->get("/pos/transaksi/{$transaksi->id}/struk");
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('Cuci Komplit Reguler');
        $htmlResponse->assertSee('Bedcover Besar');
        $htmlResponse->assertSee('56.000');
    }
}
