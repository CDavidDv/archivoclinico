<?php

namespace Tests;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    /**
     * Crea y autentica un usuario con el rol especificado.
     * Sincroniza también el rol Spatie (guard web) que usa el middleware.
     */
    protected function actingAsRol(string $rol): Usuario
    {
        Role::findOrCreate($rol, 'web');

        $usuario = Usuario::factory()->rol($rol)->create();
        $usuario->syncRoles([$rol]);

        $this->actingAs($usuario);

        return $usuario;
    }
}
