<?php

namespace Tests\Feature\Auth;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_is_displayed(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $usuario = Usuario::factory()->create([
            'nombre_usuario' => 'testuser',
            'password'       => 'password',
        ]);

        $response = $this->post(route('login.store'), [
            'nombre_usuario' => 'testuser',
            'password'       => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.store'), [
            'nombre_usuario' => 'nonexistent',
            'password'       => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nombre_usuario');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $usuario = Usuario::factory()->create();

        $response = $this->actingAs($usuario)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $usuario = Usuario::factory()->create();

        $response = $this->actingAs($usuario)->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
