<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_required_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Verify users
        $admin = User::where('email', 'admin@laundry.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->isAdmin());

        $kasir = User::where('email', 'kasir@laundry.com')->first();
        $this->assertNotNull($kasir);
        $this->assertTrue($kasir->isKasir());

        // Verify services
        $reguler = Layanan::where('nama_layanan', 'Reguler')->first();
        $this->assertNotNull($reguler);
        $this->assertEquals(7000.00, $reguler->harga_per_kg);
        $this->assertEquals('3-4 hari', $reguler->durasi);

        $ekspres = Layanan::where('nama_layanan', 'Ekspres')->first();
        $this->assertNotNull($ekspres);
        $this->assertEquals(12000.00, $ekspres->harga_per_kg);
        $this->assertEquals('1-2 hari', $ekspres->durasi);

        $superkilat = Layanan::where('nama_layanan', 'Superkilat')->first();
        $this->assertNotNull($superkilat);
        $this->assertEquals(25000.00, $superkilat->harga_per_kg);
        $this->assertEquals('5 jam selesai', $superkilat->durasi);

        // Verify transactions
        $this->assertEquals(3, Transaksi::count());
        $transaksi = Transaksi::with(['layanan', 'user'])->first();
        $this->assertNotNull($transaksi->layanan);
        $this->assertNotNull($transaksi->user);
    }
}
