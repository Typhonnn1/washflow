<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_admin_user_can_authenticate_and_has_admin_role(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin_test@example.com',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin_test@example.com',
            'password' => 'admin123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(auth()->user()->isAdmin());
        $this->assertFalse(auth()->user()->isKasir());
        $this->assertEquals('Administrator', auth()->user()->role_label);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_kasir_user_can_authenticate_and_has_kasir_role(): void
    {
        $kasir = User::factory()->kasir()->create([
            'email' => 'kasir_test@example.com',
            'password' => bcrypt('kasir123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'kasir_test@example.com',
            'password' => 'kasir123',
        ]);

        $this->assertAuthenticatedAs($kasir);
        $this->assertTrue(auth()->user()->isKasir());
        $this->assertFalse(auth()->user()->isAdmin());
        $this->assertEquals('Kasir', auth()->user()->role_label);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_seeded_admin_and_kasir_credentials(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Admin login
        $responseAdmin = $this->post('/login', [
            'email' => 'admin@laundry.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());
        $responseAdmin->assertRedirect(route('dashboard', absolute: false));

        // Logout
        $this->post('/logout');
        $this->assertGuest();

        // Kasir login
        $responseKasir = $this->post('/login', [
            'email' => 'kasir@laundry.com',
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isKasir());
        $responseKasir->assertRedirect(route('dashboard', absolute: false));
    }
}
