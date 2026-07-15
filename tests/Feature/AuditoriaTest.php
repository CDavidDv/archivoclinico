<?php

namespace Tests\Feature;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditoriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_registra_movimiento(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = Producto::factory()->create();

        $this->assertDatabaseHas('movimientos', [
            'id_usuario'           => $usuario->id,
            'accion'               => 'crear',
            'tabla_afectada'       => 'productos',
            'id_registro_afectado' => $producto->id,
        ]);
    }

    public function test_edicion_registra_movimiento(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = Producto::factory()->create();
        $producto->update(['nombre' => 'Producto Actualizado']);

        $movimientos = Movimiento::where('tabla_afectada', 'productos')
            ->where('id_registro_afectado', $producto->id)
            ->where('accion', 'editar')
            ->get();

        $this->assertCount(1, $movimientos);
        $this->assertEquals($usuario->id, $movimientos->first()->id_usuario);
    }

    public function test_eliminacion_registra_movimiento(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        $proveedor = Proveedor::factory()->create();
        $proveedorId = $proveedor->id;
        $proveedor->delete();

        $this->assertDatabaseHas('movimientos', [
            'id_usuario'           => $usuario->id,
            'accion'               => 'eliminar',
            'tabla_afectada'       => 'proveedores',
            'id_registro_afectado' => $proveedorId,
        ]);
    }

    public function test_movimientos_asociados_a_usuario(): void
    {
        $usuario = $this->actingAsRol(Usuario::ROL_ALMACEN);

        Producto::factory()->count(3)->create();

        $movimientos = $usuario->movimientos;

        $this->assertCount(3, $movimientos);
        $movimientos->each(function ($mov) use ($usuario) {
            $this->assertEquals($usuario->id, $mov->id_usuario);
        });
    }

    public function test_fecha_accion_se_registra(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $producto = Producto::factory()->create();

        $movimiento = Movimiento::where('tabla_afectada', 'productos')
            ->where('id_registro_afectado', $producto->id)
            ->where('accion', 'crear')
            ->first();

        $this->assertNotNull($movimiento->fecha_accion);
    }
}
