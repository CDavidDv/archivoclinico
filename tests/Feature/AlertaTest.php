<?php

namespace Tests\Feature;

use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertaTest extends TestCase
{
    use RefreshDatabase;

    public function test_alertas_muestra_medicamentos_bajo_minimo(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create(['stock_minimo' => 20]);

        LoteFarmacia::factory()
            ->for($medicamento)
            ->create(['cantidad_actual' => 5]);

        $response = $this->get(route('farmacia.alertas'));

        $response->assertOk();
        $response->assertViewIs('farmacia.alertas');
        $response->assertViewHas('bajoMinimo', function ($bajoMinimo) use ($medicamento) {
            return $bajoMinimo->contains('id', $medicamento->id);
        });
    }

    public function test_alertas_no_muestra_medicamentos_con_stock_suficiente(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create(['stock_minimo' => 5]);

        LoteFarmacia::factory()
            ->for($medicamento)
            ->create(['cantidad_actual' => 50]);

        $response = $this->get(route('farmacia.alertas'));

        $response->assertOk();
        $response->assertViewHas('bajoMinimo', function ($bajoMinimo) use ($medicamento) {
            return !$bajoMinimo->contains('id', $medicamento->id);
        });
    }

    public function test_alertas_muestra_lotes_por_caducar(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        $lotePorCaducar = LoteFarmacia::factory()
            ->for($medicamento)
            ->porCaducar(15)
            ->create(['cantidad_actual' => 20]);

        $response = $this->get(route('farmacia.alertas'));

        $response->assertOk();
        $response->assertViewHas('porCaducar', function ($porCaducar) use ($lotePorCaducar) {
            return $porCaducar->contains('id', $lotePorCaducar->id);
        });
    }

    public function test_alertas_muestra_lotes_caducados(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        $loteCaducado = LoteFarmacia::factory()
            ->for($medicamento)
            ->caducado()
            ->create(['cantidad_actual' => 10]);

        $response = $this->get(route('farmacia.alertas'));

        $response->assertOk();
        $response->assertViewHas('caducados', function ($caducados) use ($loteCaducado) {
            return $caducados->contains('id', $loteCaducado->id);
        });
    }

    public function test_rol_no_farmacia_no_accede_alertas(): void
    {
        $this->actingAsRol(Usuario::ROL_ARCHIVO);

        $response = $this->get(route('farmacia.alertas'));

        $response->assertForbidden();
    }
}
