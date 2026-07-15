<?php

namespace Tests\Feature;

use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FefoTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_disponibles_fefo_excluye_caducados(): void
    {
        $medicamento = Medicamento::factory()->create();

        $loteValido = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 50,
            ]);

        $loteCaducado = LoteFarmacia::factory()
            ->for($medicamento)
            ->caducado()
            ->create(['cantidad_actual' => 30]);

        $disponibles = LoteFarmacia::disponiblesFefo()->get();

        $this->assertCount(1, $disponibles);
        $this->assertTrue($disponibles->first()->id === $loteValido->id);
    }

    public function test_scope_disponibles_fefo_excluye_cero_stock(): void
    {
        $medicamento = Medicamento::factory()->create();

        LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 0,
            ]);

        $disponibles = LoteFarmacia::disponiblesFefo()->get();

        $this->assertCount(0, $disponibles);
    }

    public function test_scope_disponibles_fefo_ordena_por_caducidad(): void
    {
        $medicamento = Medicamento::factory()->create();

        $loteLejano = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addYear(),
                'cantidad_actual' => 50,
            ]);

        $loteProximo = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 50,
            ]);

        $disponibles = LoteFarmacia::disponiblesFefo()->get();

        $this->assertEquals($loteProximo->id, $disponibles->first()->id);
        $this->assertEquals($loteLejano->id, $disponibles->last()->id);
    }

    public function test_scope_por_caducar_devuelve_lotes_proximos(): void
    {
        $medicamento = Medicamento::factory()->create();

        $lotePorCaducar = LoteFarmacia::factory()
            ->for($medicamento)
            ->porCaducar(15)
            ->create(['cantidad_actual' => 20]);

        $loteLejano = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addYear(),
                'cantidad_actual' => 20,
            ]);

        $porCaducar = LoteFarmacia::porCaducar(30)->get();

        $this->assertTrue($porCaducar->contains($lotePorCaducar));
        $this->assertFalse($porCaducar->contains($loteLejano));
    }

    public function test_scope_caducados_devuelve_lotes_vencidos(): void
    {
        $medicamento = Medicamento::factory()->create();

        $caducado = LoteFarmacia::factory()
            ->for($medicamento)
            ->caducado()
            ->create(['cantidad_actual' => 10]);

        $vigente = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 10,
            ]);

        $caducados = LoteFarmacia::caducados()->get();

        $this->assertTrue($caducados->contains($caducado));
        $this->assertFalse($caducados->contains($vigente));
    }

    public function test_lote_is_caducado_method(): void
    {
        $lote = LoteFarmacia::factory()->caducado()->create();
        $this->assertTrue($lote->isCaducado());

        $loteVigente = LoteFarmacia::factory()->create([
            'caducidad' => now()->addMonth(),
        ]);
        $this->assertFalse($loteVigente->isCaducado());
    }

    public function test_almacen_fefo_excluye_caducados(): void
    {
        $producto = \App\Models\Producto::factory()->create();

        $loteValido = \App\Models\LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 50,
            ]);

        $loteCaducado = \App\Models\LoteAlmacen::factory()
            ->for($producto)
            ->caducado()
            ->create(['cantidad_actual' => 30]);

        $disponibles = \App\Models\LoteAlmacen::disponiblesFefo()->get();

        $this->assertCount(1, $disponibles);
        $this->assertTrue($disponibles->first()->id === $loteValido->id);
    }

    public function test_almacen_fefo_incluye_lotes_sin_caducidad_al_final(): void
    {
        $producto = \App\Models\Producto::factory()->create();

        $loteSinCaducidad = \App\Models\LoteAlmacen::factory()
            ->for($producto)
            ->sinCaducidad()
            ->create(['cantidad_actual' => 50]);

        $loteConCaducidad = \App\Models\LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 50,
            ]);

        $disponibles = \App\Models\LoteAlmacen::disponiblesFefo()->get();

        $this->assertCount(2, $disponibles);
        $this->assertEquals($loteConCaducidad->id, $disponibles->first()->id);
        $this->assertEquals($loteSinCaducidad->id, $disponibles->last()->id);
    }

    public function test_dispensacion_descuenta_lote_mas_proximo_primero(): void
    {
        $usuario = $this->actingAsRol(\App\Models\Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        $loteLejano = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addYear(),
                'cantidad_actual' => 100,
            ]);

        $loteProximo = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 30,
            ]);

        $receta = \App\Models\Receta::factory()
            ->for(\App\Models\DerechoHabiente::factory()->create())
            ->for(\App\Models\Medico::factory()->create())
            ->for($usuario)
            ->create();

        $detalle = \App\Models\DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => 20, 'cantidad_surtida' => 0]);

        $service = new \App\Services\DispensacionService();
        $service->dispensar($receta, [$detalle->id => 20], $usuario);

        $loteProximo->refresh();
        $loteLejano->refresh();
        $this->assertEquals(10, $loteProximo->cantidad_actual);
        $this->assertEquals(100, $loteLejano->cantidad_actual);
    }

    public function test_dispensacion_cruza_varios_lotes_fefo(): void
    {
        $usuario = $this->actingAsRol(\App\Models\Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        $lote1 = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 10,
            ]);

        $lote2 = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonths(3),
                'cantidad_actual' => 10,
            ]);

        $lote3 = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addYear(),
                'cantidad_actual' => 100,
            ]);

        $receta = \App\Models\Receta::factory()
            ->for(\App\Models\DerechoHabiente::factory()->create())
            ->for(\App\Models\Medico::factory()->create())
            ->for($usuario)
            ->create();

        $detalle = \App\Models\DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => 15, 'cantidad_surtida' => 0]);

        $service = new \App\Services\DispensacionService();
        $service->dispensar($receta, [$detalle->id => 15], $usuario);

        $lote1->refresh();
        $lote2->refresh();
        $lote3->refresh();
        $this->assertEquals(0, $lote1->cantidad_actual);
        $this->assertEquals(5, $lote2->cantidad_actual);
        $this->assertEquals(100, $lote3->cantidad_actual);
    }

    public function test_dispensacion_falla_si_stock_insuficiente(): void
    {
        $usuario = $this->actingAsRol(\App\Models\Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 5,
            ]);

        $receta = \App\Models\Receta::factory()
            ->for(\App\Models\DerechoHabiente::factory()->create())
            ->for(\App\Models\Medico::factory()->create())
            ->for($usuario)
            ->create();

        $detalle = \App\Models\DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => 20, 'cantidad_surtida' => 0]);

        $service = new \App\Services\DispensacionService();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->dispensar($receta, [$detalle->id => 20], $usuario);
    }
}
