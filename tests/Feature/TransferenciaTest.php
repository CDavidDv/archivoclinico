<?php

namespace Tests\Feature;

use App\Models\DetalleSolicitudAbastecimiento;
use App\Models\LoteAlmacen;
use App\Models\Medicamento;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use App\Models\Usuario;
use App\Services\TransferenciaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferenciaTest extends TestCase
{
    use RefreshDatabase;

    private function crearProductoConMedicamento(): Producto
    {
        $producto = Producto::factory()->create(['categoria' => Producto::CATEGORIA_MEDICAMENTO]);
        Medicamento::factory()->create(['id_producto' => $producto->id]);
        return $producto->fresh('medicamento');
    }

    public function test_transferencia_a_farmacia_descuenta_almacen_y_crea_entrada_farmacia(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = $this->crearProductoConMedicamento();

        $lote = LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 100,
            ]);

        $datos = [
            'destino'  => 'farmacia',
            'fecha'    => now()->toDateString(),
        ];

        $renglones = [
            ['id_producto' => $producto->id, 'cantidad' => 30],
        ];

        $service = app(TransferenciaService::class);
        $transferencia = $service->transferir($datos, $renglones, $usuario);

        $this->assertNotNull($transferencia);
        $this->assertEquals('farmacia', $transferencia->destino);

        $lote->refresh();
        $this->assertEquals(70, $lote->cantidad_actual);

        $entradaFarmacia = $transferencia->entradaFarmacia;
        $this->assertNotNull($entradaFarmacia);
    }

    public function test_transferencia_fallida_no_descuenta_nada(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = $this->crearProductoConMedicamento();

        LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 10,
            ]);

        $datos = [
            'destino'  => 'farmacia',
            'fecha'    => now()->toDateString(),
        ];

        $renglones = [
            ['id_producto' => $producto->id, 'cantidad' => 50],
        ];

        $service = app(TransferenciaService::class);

        try {
            $service->transferir($datos, $renglones, $usuario);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Expected
        }

        $lote = LoteAlmacen::where('id_producto', $producto->id)->first();
        $this->assertEquals(10, $lote->cantidad_actual);
    }

    public function test_transferencia_con_solicitud_marca_surtida(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = $this->crearProductoConMedicamento();

        LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 100,
            ]);

        $solicitud = SolicitudAbastecimiento::factory()->aprobada()->create();

        DetalleSolicitudAbastecimiento::factory()
            ->for($solicitud, 'solicitud')
            ->for($producto)
            ->create(['cantidad_solicitada' => 20, 'cantidad_surtida' => 0]);

        $datos = [
            'destino'      => 'farmacia',
            'id_solicitud' => $solicitud->id,
            'fecha'        => now()->toDateString(),
        ];

        $renglones = [
            ['id_producto' => $producto->id, 'cantidad' => 20],
        ];

        $service = app(TransferenciaService::class);
        $transferencia = $service->transferir($datos, $renglones, $usuario);

        $solicitud->refresh();
        $this->assertEquals(SolicitudAbastecimiento::ESTATUS_SURTIDA, $solicitud->estatus);
        $this->assertEquals($usuario->id, $solicitud->id_usuario_atiende);
    }

    public function test_transferencia_rechaza_producto_sin_vinculo_farmacia(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = Producto::factory()->create();
        $this->assertNull($producto->medicamento);

        LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 50,
            ]);

        $datos = [
            'destino'  => 'farmacia',
            'fecha'    => now()->toDateString(),
        ];

        $renglones = [
            ['id_producto' => $producto->id, 'cantidad' => 10],
        ];

        $service = app(TransferenciaService::class);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $service->transferir($datos, $renglones, $usuario);
    }

    public function test_folios_se_generan_automaticamente(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = Producto::factory()->create();

        LoteAlmacen::factory()
            ->for($producto)
            ->create([
                'caducidad'       => now()->addMonths(6),
                'cantidad_actual' => 50,
            ]);

        $service = app(TransferenciaService::class);

        $t1 = $service->transferir(
            ['destino' => 'area', 'area_destino' => 'Urgencias', 'fecha' => now()->toDateString()],
            [['id_producto' => $producto->id, 'cantidad' => 5]],
            $usuario
        );

        $t2 = $service->transferir(
            ['destino' => 'area', 'area_destino' => 'Consultorio', 'fecha' => now()->toDateString()],
            [['id_producto' => $producto->id, 'cantidad' => 5]],
            $usuario
        );

        $this->assertEquals('TRF-000001', $t1->folio);
        $this->assertEquals('TRF-000002', $t2->folio);
    }
}
