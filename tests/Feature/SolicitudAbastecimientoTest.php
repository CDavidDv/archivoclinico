<?php

namespace Tests\Feature;

use App\Models\DetalleSolicitudAbastecimiento;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitudAbastecimientoTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmacia_puede_crear_solicitud(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $producto = Producto::factory()->create();

        $response = $this->post(route('solicitudes.store'), [
            'modulo_solicitante' => SolicitudAbastecimiento::MODULO_FARMACIA,
            'fecha_solicitud'    => now()->toDateString(),
            'detalles'           => [
                ['id_producto' => $producto->id, 'cantidad_solicitada' => 10],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('solicitudes_abastecimiento', [
            'estatus' => SolicitudAbastecimiento::ESTATUS_PENDIENTE,
        ]);
    }

    public function test_solicitud_genera_folio_automatico(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->create();

        $this->assertNotNull($solicitud->folio);
        $this->assertStringStartsWith('SOL-', $solicitud->folio);
    }

    public function test_solicitud_pendiente_puede_aprobarse(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->create();
        $this->assertTrue($solicitud->puedeAprobarse());
    }

    public function test_solicitud_aprobada_no_puede_aprobarse(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->aprobada()->create();
        $this->assertFalse($solicitud->puedeAprobarse());
    }

    public function test_solicitud_pendiente_puede_rechazarse(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->create();
        $this->assertTrue($solicitud->puedeRechazarse());
    }

    public function test_solicitud_aprobada_puede_surtirse(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->aprobada()->create();
        $this->assertTrue($solicitud->puedeSurtirse());
    }

    public function test_solicitud_pendiente_no_puede_surtirse(): void
    {
        $solicitud = SolicitudAbastecimiento::factory()->create();
        $this->assertFalse($solicitud->puedeSurtirse());
    }

    public function test_almacen_puede_aprobar_solicitud(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $solicitud = SolicitudAbastecimiento::factory()->create();

        $response = $this->put(route('solicitudes.aprobar', $solicitud));

        $response->assertRedirect();
        $solicitud->refresh();
        $this->assertEquals(SolicitudAbastecimiento::ESTATUS_APROBADA, $solicitud->estatus);
    }

    public function test_almacen_puede_rechazar_solicitud(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $solicitud = SolicitudAbastecimiento::factory()->create();

        $response = $this->put(route('solicitudes.rechazar', $solicitud), [
            'motivo_rechazo' => 'Sin stock disponible',
        ]);

        $response->assertRedirect();
        $solicitud->refresh();
        $this->assertEquals(SolicitudAbastecimiento::ESTATUS_RECHAZADA, $solicitud->estatus);
        $this->assertEquals('Sin stock disponible', $solicitud->motivo_rechazo);
    }

    public function test_no_se_puede_aprobar_solicitud_ya_aprobada(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $solicitud = SolicitudAbastecimiento::factory()->aprobada()->create();

        $response = $this->put(route('solicitudes.aprobar', $solicitud));

        $response->assertRedirect();
    }

    public function test_no_se_puede_rechazar_solicitud_ya_rechazada(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $solicitud = SolicitudAbastecimiento::factory()->rechazada()->create();

        $response = $this->put(route('solicitudes.rechazar', $solicitud));

        $response->assertRedirect();
    }
}
