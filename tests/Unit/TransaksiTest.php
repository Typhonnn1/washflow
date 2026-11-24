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

        $transaksi->update([
            'status' => 'Selesai',
            'tgl_selesai' => now(),
        ]);

        $this->assertEquals('Selesai', $transaksi->fresh()->status);
        $this->assertNotNull($transaksi->fresh()->tgl_selesai);
    }
}
