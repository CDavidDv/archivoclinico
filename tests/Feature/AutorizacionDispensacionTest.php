<?php

namespace Tests\Feature;

use App\Models\Autorizacion;
use App\Models\DerechoHabiente;
use App\Models\DetalleReceta;
use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Receta;
use App\Models\Usuario;
use App\Services\DispensacionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AutorizacionDispensacionTest extends TestCase
{
    use RefreshDatabase;

    private function recetaCon(Medicamento $medicamento, int $prescrita, int $stock, ?DerechoHabiente $dh = null): array
    {
        $usuario = $this->actingAsRol(Usuario::ROL_FARMACIA);

        LoteFarmacia::factory()->for($medicamento)->create([
            'caducidad'       => now()->addYear(),
            'cantidad_actual' => $stock,
        ]);

        $receta = Receta::factory()
            ->for($dh ?? DerechoHabiente::factory()->create())
            ->for(Medico::factory()->create())
            ->for($usuario)
            ->create();

        $detalle = DetalleReceta::factory()
            ->for($receta)
            ->for($medicamento)
            ->create(['cantidad_prescrita' => $prescrita, 'cantidad_surtida' => 0]);

        return [$usuario, $receta, $detalle];
    }

    public function test_exceder_prescrito_sin_autorizacion_falla(): void
    {
        $med = Medicamento::factory()->create();
        [$usuario, $receta, $detalle] = $this->recetaCon($med, 5, 100);

        $service = new DispensacionService();

        $this->expectException(ValidationException::class);
        $service->dispensar($receta, [$detalle->id => 8], $usuario);
    }

    public function test_exceder_prescrito_con_autorizacion_registra_bitacora(): void
    {
        $med = Medicamento::factory()->create();
        [$usuario, $receta, $detalle] = $this->recetaCon($med, 5, 100);

        $admin = Usuario::factory()->rol(Usuario::ROL_ADMINISTRADOR)->create();

        $service = new DispensacionService();
        $disp = $service->dispensar($receta, [$detalle->id => 8], $usuario, null, $admin, 'Corrección de receta');

        $this->assertEquals(8, $detalle->fresh()->cantidad_surtida);
        $this->assertEquals(Receta::ESTATUS_SURTIDA, $receta->fresh()->estatus);

        $this->assertDatabaseHas('autorizaciones', [
            'tipo'                => Autorizacion::TIPO_EXCESO_CANTIDAD,
            'id_dispensacion'     => $disp->id,
            'id_usuario_solicita' => $usuario->id,
            'id_usuario_autoriza' => $admin->id,
            'cantidad_prescrita'  => 5,
            'cantidad_autorizada' => 8,
            'motivo'              => 'Corrección de receta',
        ]);
    }

    public function test_presentacion_comercial_por_cajas_marca_su_tipo(): void
    {
        // Caja de 3 piezas; se prescriben 2 → entregar 1 caja = 3 piezas.
        $med = Medicamento::factory()->create(['piezas_por_presentacion' => 3]);
        [$usuario, $receta, $detalle] = $this->recetaCon($med, 2, 100);

        $admin = Usuario::factory()->rol(Usuario::ROL_ADMINISTRADOR)->create();

        $service = new DispensacionService();
        $disp = $service->dispensar($receta, [$detalle->id => 3], $usuario, null, $admin, 'Presentación en caja');

        $this->assertDatabaseHas('autorizaciones', [
            'tipo'            => Autorizacion::TIPO_PRESENTACION_COMERCIAL,
            'id_dispensacion' => $disp->id,
        ]);
    }

    public function test_controlado_anticipado_bloquea_sin_autorizacion(): void
    {
        $dh  = DerechoHabiente::factory()->create();
        $med = Medicamento::factory()->create(['controlado' => true, 'dias_restriccion' => 28]);

        // Primer surtido (dentro de su cantidad prescrita, sin excepciones).
        [$usuario, $receta1, $detalle1] = $this->recetaCon($med, 5, 100, $dh);
        (new DispensacionService())->dispensar($receta1, [$detalle1->id => 5], $usuario);

        // Segundo intento inmediato para el mismo derechohabiente y medicamento.
        $receta2 = Receta::factory()
            ->for($dh)->for(Medico::factory()->create())->for($usuario)->create();
        $detalle2 = DetalleReceta::factory()->for($receta2)->for($med)
            ->create(['cantidad_prescrita' => 5, 'cantidad_surtida' => 0]);

        $this->expectException(ValidationException::class);
        (new DispensacionService())->dispensar($receta2, [$detalle2->id => 5], $usuario);
    }

    public function test_controlado_anticipado_con_autorizacion_registra_bitacora(): void
    {
        $dh  = DerechoHabiente::factory()->create();
        $med = Medicamento::factory()->create(['controlado' => true, 'dias_restriccion' => 28]);
        $admin = Usuario::factory()->rol(Usuario::ROL_ADMINISTRADOR)->create();

        [$usuario, $receta1, $detalle1] = $this->recetaCon($med, 5, 100, $dh);
        (new DispensacionService())->dispensar($receta1, [$detalle1->id => 5], $usuario);

        $receta2 = Receta::factory()
            ->for($dh)->for(Medico::factory()->create())->for($usuario)->create();
        $detalle2 = DetalleReceta::factory()->for($receta2)->for($med)
            ->create(['cantidad_prescrita' => 5, 'cantidad_surtida' => 0]);

        $disp = (new DispensacionService())
            ->dispensar($receta2, [$detalle2->id => 5], $usuario, null, $admin, 'Urgencia médica');

        $this->assertDatabaseHas('autorizaciones', [
            'tipo'            => Autorizacion::TIPO_CONTROLADO_ANTICIPADO,
            'id_dispensacion' => $disp->id,
            'motivo'          => 'Urgencia médica',
        ]);
    }

    public function test_dentro_de_lo_prescrito_no_requiere_autorizacion(): void
    {
        $med = Medicamento::factory()->create();
        [$usuario, $receta, $detalle] = $this->recetaCon($med, 10, 100);

        $disp = (new DispensacionService())->dispensar($receta, [$detalle->id => 4], $usuario);

        $this->assertEquals(4, $detalle->fresh()->cantidad_surtida);
        $this->assertDatabaseCount('autorizaciones', 0);
    }
}
