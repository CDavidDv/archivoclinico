<?php

namespace Tests\Feature;

use App\Models\DetalleReceta;
use App\Models\DerechoHabiente;
use App\Models\Dispensacion;
use App\Models\LoteFarmacia;
use App\Models\Medico;
use App\Models\Medicamento;
use App\Models\Receta;
use App\Models\Usuario;
use App\Services\DispensacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_receta_genera_folio_automatico(): void
    {
        $receta = Receta::factory()->create();

        $this->assertNotNull($receta->folio);
        $this->assertStringStartsWith('REC-', $receta->folio);
    }

    public function test_receta_pendiente_puede_dispensarse(): void
    {
        $receta = Receta::factory()->create(['estatus' => Receta::ESTATUS_PENDIENTE]);
        $this->assertTrue($receta->puedeDispensarse());
    }

    public function test_receta_parcial_puede_dispensarse(): void
    {
        $receta = Receta::factory()->create(['estatus' => Receta::ESTATUS_PARCIAL]);
        $this->assertTrue($receta->puedeDispensarse());
    }

    public function test_receta_surtida_no_puede_dispensarse(): void
    {
        $receta = Receta::factory()->surtida()->create();
        $this->assertFalse($receta->puedeDispensarse());
    }

    public function test_receta_cancelada_no_puede_dispensarse(): void
    {
        $receta = Receta::factory()->cancelada()->create();
        $this->assertFalse($receta->puedeDispensarse());
    }

    public function test_receta_pendiente_puede_cancelarse(): void
    {
        $receta = Receta::factory()->create(['estatus' => Receta::ESTATUS_PENDIENTE]);
        $this->assertTrue($receta->puedeCancelarse());
    }

    public function test_receta_surtida_no_puede_cancelarse(): void
    {
        $receta = Receta::factory()->surtida()->create();
        $this->assertFalse($receta->puedeCancelarse());
    }

    public function test_dispensacion_descontstock_feo(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        $loteProximo = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 10,
            ]);

        $loteLejano = LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addYear(),
                'cantidad_actual' => 50,
            ]);

        $receta = Receta::factory()
            ->for(DerechoHabiente::factory()->create())
            ->for(Medico::factory()->create())
            ->for($usuario)
            ->create();

        $detalle = DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => 15, 'cantidad_surtida' => 0]);

        $service = app(DispensacionService::class);
        $dispensacion = $service->dispensar($receta, [$detalle->id => 15], $usuario);

        $this->assertNotNull($dispensacion);
        $this->assertInstanceOf(Dispensacion::class, $dispensacion);

        $loteProximo->refresh();
        $loteLejano->refresh();
        $this->assertEquals(0, $loteProximo->cantidad_actual);
        $this->assertEquals(45, $loteLejano->cantidad_actual);

        $detalle->refresh();
        $this->assertEquals(15, $detalle->cantidad_surtida);

        $receta->refresh();
        $this->assertEquals(Receta::ESTATUS_SURTIDA, $receta->estatus);
    }

    public function test_dispensacion_parcial_cambia_estatus(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_FARMACIA);

        $medicamento = Medicamento::factory()->create();

        LoteFarmacia::factory()
            ->for($medicamento)
            ->create([
                'caducidad'       => now()->addMonth(),
                'cantidad_actual' => 100,
            ]);

        $receta = Receta::factory()
            ->for(DerechoHabiente::factory()->create())
            ->for(Medico::factory()->create())
            ->for($usuario)
            ->create();

        DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => 10, 'cantidad_surtida' => 0]);

        DetalleReceta::factory()
            ->for($receta)
            ->for(Medicamento::factory()->create())
            ->create(['cantidad_prescrita' => 5, 'cantidad_surtida' => 0]);

        $detalles = $receta->detalles()->get();
        $service = app(DispensacionService::class);

        $service->dispensar($receta, [$detalles[0]->id => 10], $usuario);

        $receta->refresh();
        $this->assertEquals(Receta::ESTATUS_PARCIAL, $receta->estatus);
    }

    public function test_no_se_puede_dispensar_receta_cancelada(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_FARMACIA);

        $receta = Receta::factory()->cancelada()->create();

        $service = app(DispensacionService::class);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->dispensar($receta, [], $usuario);
    }

    public function test_scope_pendientes_devuelve_pendientes_y_parciales(): void
    {
        $pendiente = Receta::factory()->create(['estatus' => Receta::ESTATUS_PENDIENTE]);
        $parcial = Receta::factory()->create(['estatus' => Receta::ESTATUS_PARCIAL]);
        $surtida = Receta::factory()->surtida()->create();
        $cancelada = Receta::factory()->cancelada()->create();

        $pendientes = Receta::pendientes()->get();

        $this->assertTrue($pendientes->contains($pendiente));
        $this->assertTrue($pendientes->contains($parcial));
        $this->assertFalse($pendientes->contains($surtida));
        $this->assertFalse($pendientes->contains($cancelada));
    }
}
