<?php

namespace Tests;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Crea y autentica un usuario con el rol especificado.
     */
    protected function actingAsRol(string $rol): Usuario
    {
        $usuario = Usuario::factory()->rol($rol)->create();
        $this->actingAs($usuario);

        return $usuario;
    }
}
