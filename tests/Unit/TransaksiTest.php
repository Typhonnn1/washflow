<?php

namespace Tests\Unit;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaksi_belongs_to_layanan_and_user(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Citra',
            'no_hp' => '089988776655',
            'layanan_id' => $layanan->id,
            'user_id' => $user->id,
            'berat' => 2.0,
            'status' => 'Proses',
        ]);

        $this->assertEquals($layanan->id, $transaksi->layanan->id);
        $this->assertEquals($user->id, $transaksi->user->id);
        $this->assertEquals('Superkilat', $transaksi->layanan->nama_layanan);
    }

    public function test_transaksi_auto_generates_kode_and_calculates_total_harga(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Dewi',
            'no_hp' => '081122334455',
            'layanan_id' => $layanan->id,
            'berat' => 2.5,
        ]);

        $this->assertNotEmpty($transaksi->kode_transaksi);
        $this->assertStringStartsWith('TRX-', $transaksi->kode_transaksi);
        $this->assertEquals(17500.00, (float) $transaksi->total_harga);
        $this->assertEquals('Masuk', $transaksi->status);
    }

    public function test_transaksi_sequential_code_generation(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $trx1 = Transaksi::create([
            'nama_pelanggan' => 'User 1',
            'no_hp' => '0811',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
        ]);

        $trx2 = Transaksi::create([
            'nama_pelanggan' => 'User 2',
            'no_hp' => '0822',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
        ]);

        $expectedPrefix = 'TRX-' . date('Ymd') . '-';
        $this->assertEquals($expectedPrefix . '0001', $trx1->kode_transaksi);
        $this->assertEquals($expectedPrefix . '0002', $trx2->kode_transaksi);
    }

    public function test_transaksi_preserves_custom_kode_and_custom_total_harga(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'durasi' => '1-2 hari',
        ]);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'CUSTOM-CODE-999',
            'nama_pelanggan' => 'Fajar',
            'no_hp' => '0833',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
            'total_harga' => 20000.00, // Diskon khusus
        ]);

        $this->assertEquals('CUSTOM-CODE-999', $transaksi->kode_transaksi);
        $this->assertEquals(20000.00, (float) $transaksi->total_harga);
    }

    public function test_transaksi_preserves_zero_total_harga(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Promo User',
            'no_hp' => '0812345678',
            'layanan_id' => $layanan->id,
            'berat' => 3.0,
            'total_harga' => 0,
        ]);

        $this->assertEquals(0.00, (float) $transaksi->fresh()->total_harga);
    }

    public function test_generate_kode_transaksi_with_non_numeric_code_present(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $trx1 = Transaksi::create([
            'nama_pelanggan' => 'User 1',
            'no_hp' => '0811',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
        ]);

        // Insert non-numeric suffix
        $todayPrefix = 'TRX-' . date('Ymd') . '-';
        Transaksi::create([
            'kode_transaksi' => $todayPrefix . 'VIP',
            'nama_pelanggan' => 'VIP User',
            'no_hp' => '0822',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
        ]);

        $trx3 = Transaksi::create([
            'nama_pelanggan' => 'User 3',
            'no_hp' => '0833',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
        ]);

        $this->assertEquals($todayPrefix . '0001', $trx1->kode_transaksi);
        $this->assertEquals($todayPrefix . '0002', $trx3->kode_transaksi);
    }

    public function test_transaksi_handles_decimal_weight_correctly(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Gita',
            'no_hp' => '0844',
            'layanan_id' => $layanan->id,
            'berat' => 0.75,
        ]);

        // 0.75 * 25000 = 18750
        $this->assertEquals(18750.00, (float) $transaksi->total_harga);
    }

    public function test_transaksi_status_transitions(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'durasi' => '1-2 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Eko',
            'no_hp' => '087788990011',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
            'status' => 'Proses',
        ]);

        $this->assertEquals('Proses', $transaksi->status);
        $this->assertNull($transaksi->tgl_selesai);

        // Update to Selesai automatically populates tgl_selesai
        $transaksi->update(['status' => 'Selesai']);

        $this->assertEquals('Selesai', $transaksi->fresh()->status);
        $this->assertNotNull($transaksi->fresh()->tgl_selesai);

        // Reverting to Proses clears tgl_selesai
        $transaksi->update(['status' => 'Proses']);
        $this->assertEquals('Proses', $transaksi->fresh()->status);
        $this->assertNull($transaksi->fresh()->tgl_selesai);
    }

    public function test_transaksi_convenience_methods(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Hani',
            'no_hp' => '0855',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
        ]);

        $this->assertEquals('Masuk', $transaksi->status);
        $this->assertEquals('Belum Lunas', $transaksi->status_pembayaran);

        $transaksi->markAsProses();
        $this->assertEquals('Proses', $transaksi->fresh()->status);

        $transaksi->markAsSelesai();
        $this->assertEquals('Selesai', $transaksi->fresh()->status);
        $this->assertNotNull($transaksi->fresh()->tgl_selesai);

        $transaksi->markAsLunas();
        $this->assertEquals('Lunas', $transaksi->fresh()->status_pembayaran);
    }

    public function test_transaksi_recalculates_price_on_weight_update(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Irfan',
            'no_hp' => '0866',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
        ]);

        $this->assertEquals(14000.00, (float) $transaksi->total_harga);

        // Update weight from 2kg to 3.5kg without specifying total_harga
        $transaksi->update(['berat' => 3.5]);

        $this->assertEquals(24500.00, (float) $transaksi->fresh()->total_harga);
    }

    public function test_transaksi_preserves_custom_price_on_weight_update(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Joko',
            'no_hp' => '0877',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
        ]);

        // Explicit price override
        $transaksi->update([
            'berat' => 4.0,
            'total_harga' => 20000.00,
        ]);

        $this->assertEquals(20000.00, (float) $transaksi->fresh()->total_harga);
    }

    public function test_transaksi_formatted_accessors_and_badges(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Karina',
            'no_hp' => '0888',
            'layanan_id' => $layanan->id,
            'berat' => 2.5,
            'status' => 'Proses',
            'status_pembayaran' => 'Lunas',
        ]);

        $this->assertEquals('Rp 62.500', $transaksi->harga_rupiah);
        $this->assertEquals('2,50 kg', $transaksi->berat_formatted);
        $this->assertStringContainsString('bg-blue-100', $transaksi->status_badge);
        $this->assertStringContainsString('bg-emerald-100', $transaksi->status_pembayaran_badge);
    }

    public function test_transaksi_query_scopes(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        Transaksi::create([
            'nama_pelanggan' => 'A',
            'no_hp' => '1',
            'layanan_id' => $layanan->id,
            'berat' => 1.0,
            'status' => 'Masuk',
            'status_pembayaran' => 'Belum Lunas',
        ]);

        Transaksi::create([
            'nama_pelanggan' => 'B',
            'no_hp' => '2',
            'layanan_id' => $layanan->id,
            'berat' => 2.0,
            'status' => 'Selesai',
            'status_pembayaran' => 'Lunas',
        ]);

        $this->assertEquals(1, Transaksi::status('Masuk')->count());
        $this->assertEquals(1, Transaksi::selesai()->count());
        $this->assertEquals(1, Transaksi::lunas()->count());
        $this->assertEquals(1, Transaksi::statusPembayaran('Belum Lunas')->count());
        $this->assertEquals(2, Transaksi::filterTanggal(now()->toDateString(), now()->toDateString())->count());
    }
}
