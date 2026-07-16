<?php

namespace Tests\Feature;

use App\Models\DerechoHabiente;
use App\Models\Medicamento;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FiltroTablasTest extends TestCase
{
    use RefreshDatabase;

    public function test_medicamentos_filtra_por_clave(): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        Medicamento::factory()->create(['clave' => 'PARA-500', 'nombre' => 'Paracetamol']);
        Medicamento::factory()->create(['clave' => 'IBU-400', 'nombre' => 'Ibuprofeno']);

        $this->get(route('medicamentos.index', ['clave' => 'PARA']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Medicamentos/Index')
                ->where('filtros.clave', 'PARA')
                ->has('medicamentos.data', 1)
                ->where('medicamentos.data.0.nombre', 'Paracetamol'));
    }

    public function test_derechohabientes_filtra_por_nombre(): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        DerechoHabiente::factory()->create(['nombre' => 'Juan', 'apellido_paterno' => 'Pérez']);
        DerechoHabiente::factory()->create(['nombre' => 'María', 'apellido_paterno' => 'López']);

        $this->get(route('derecho_habientes.index', ['nombre' => 'Juan']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('DerechoHabientes/Index')
                ->has('derechoHabientes.data', 1)
                ->where('derechoHabientes.data.0.nombre', 'Juan'));
    }

    public function test_medicamentos_pagina_20_por_seccion(): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        Medicamento::factory()->count(25)->create();

        $this->get(route('medicamentos.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('medicamentos.data', 20)
                ->where('medicamentos.per_page', 20));
    }
}
