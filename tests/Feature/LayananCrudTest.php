<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_or_manage_layanan(): void
    {
        $response = $this->get('/layanan');
        $response->assertRedirect('/login');

        $response = $this->get('/layanan/create');
        $response->assertRedirect('/login');
    }

    public function test_kasir_can_view_layanan_list_but_cannot_create_or_edit(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $layanan = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'durasi' => '3-4 hari',
        ]);

        // Kasir can view index
        $response = $this->actingAs($kasir)->get('/layanan');
        $response->assertStatus(200);
        $response->assertSee('Reguler');

        // Kasir cannot view create page (403)
        $response = $this->actingAs($kasir)->get('/layanan/create');
        $response->assertStatus(403);

        // Kasir cannot store new service (403)
        $response = $this->actingAs($kasir)->post('/layanan', [
            'nama_layanan' => 'Hacker Service',
            'harga_per_kg' => 1000,
            'durasi' => '1 hari',
        ]);
        $response->assertStatus(403);

        // Kasir cannot edit (403)
        $response = $this->actingAs($kasir)->get("/layanan/{$layanan->id}/edit");
        $response->assertStatus(403);

        // Kasir cannot delete (403)
        $response = $this->actingAs($kasir)->delete("/layanan/{$layanan->id}");
        $response->assertStatus(403);
    }

    public function test_admin_can_perform_full_crud_on_layanan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Create page
        $response = $this->actingAs($admin)->get('/layanan/create');
        $response->assertStatus(200);

        // 2. Store new service
        $response = $this->actingAs($admin)->post('/layanan', [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
            'deskripsi' => 'Layanan super cepat',
        ]);
        $response->assertRedirect('/layanan');
        $this->assertDatabaseHas('layanan', [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'durasi' => '5 jam selesai',
        ]);

        $layanan = Layanan::where('nama_layanan', 'Superkilat')->first();

        // 3. Edit page
        $response = $this->actingAs($admin)->get("/layanan/{$layanan->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Superkilat');

        // 4. Update service
        $response = $this->actingAs($admin)->put("/layanan/{$layanan->id}", [
            'nama_layanan' => 'Superkilat Plus',
            'harga_per_kg' => 28000,
            'durasi' => '3 jam selesai',
            'deskripsi' => 'Layanan super kilat plus pewangi premium',
        ]);
        $response->assertRedirect('/layanan');
        $this->assertDatabaseHas('layanan', [
            'id' => $layanan->id,
            'nama_layanan' => 'Superkilat Plus',
            'harga_per_kg' => 28000,
        ]);

        // 5. Delete service
        $response = $this->actingAs($admin)->delete("/layanan/{$layanan->id}");
        $response->assertRedirect('/layanan');
        $this->assertDatabaseMissing('layanan', ['id' => $layanan->id]);
    }

    public function test_layanan_validation_rules(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/layanan', [
            'nama_layanan' => '',
            'harga_per_kg' => -500,
            'durasi' => '',
        ]);

        $response->assertSessionHasErrors(['nama_layanan', 'harga_per_kg', 'durasi']);
    }
}
