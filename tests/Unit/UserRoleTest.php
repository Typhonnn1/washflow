<?php

namespace Tests\Unit;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles_and_helpers(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin_test@laundry.com',
        ]);
        $kasir = User::factory()->kasir()->create([
            'email' => 'kasir_test@laundry.com',
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isKasir());

        $this->assertTrue($kasir->isKasir());
        $this->assertFalse($kasir->isAdmin());
    }

    public function test_user_has_many_transaksis(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        $trx1 = Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 1',
            'no_hp' => '0811111111',
            'layanan_id' => $layanan->id,
            'user_id' => $kasir->id,
            'berat' => 2.0,
        ]);

        $trx2 = Transaksi::create([
            'nama_pelanggan' => 'Pelanggan 2',
            'no_hp' => '0822222222',
            'layanan_id' => $layanan->id,
            'user_id' => $kasir->id,
            'berat' => 3.0,
        ]);

        $this->assertEquals(2, $kasir->transaksis->count());
        $this->assertTrue($kasir->transaksis->contains($trx1));
        $this->assertTrue($kasir->transaksis->contains($trx2));
    }
}
