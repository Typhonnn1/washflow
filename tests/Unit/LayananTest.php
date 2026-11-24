<?php

namespace Tests\Unit;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_layanan_with_attributes(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
            'deskripsi' => 'Layanan reguler',
        ]);

        $this->assertDatabaseHas('layanan', [
            'id' => $layanan->id,
            'nama_layanan' => 'Reguler',
            'durasi' => '3-4 hari',
        ]);
        $this->assertEquals(7000.00, $layanan->harga_per_kg);
    }

    public function test_layanan_has_many_transaksis(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'durasi' => '1-2 hari',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Andi',
            'no_hp' => '08123456789',
            'layanan_id' => $layanan->id,
            'berat' => 3.0,
            'status' => 'Masuk',
        ]);

        $this->assertTrue($layanan->transaksis->contains($transaksi));
        $this->assertEquals(1, $layanan->transaksis->count());
    }

    public function test_deleting_layanan_cascades_to_transaksis(): void
    {
        $layanan = Layanan::create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => 'Budi',
            'no_hp' => '08123456789',
            'layanan_id' => $layanan->id,
            'berat' => 1.5,
        ]);

        $transaksiId = $transaksi->id;
        $layanan->delete();

        $this->assertDatabaseMissing('transaksi', ['id' => $transaksiId]);
    }
}
