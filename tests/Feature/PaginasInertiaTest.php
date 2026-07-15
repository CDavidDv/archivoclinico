<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PaginasInertiaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cada ruta index -> componente Inertia esperado.
     * El administrador tiene acceso a todo (middleware Spatie).
     *
     * @return array<string, array{0:string, 1:string}>
     */
    public static function rutasIndex(): array
    {
        return [
            'dashboard'          => ['dashboard', 'Dashboard'],
            'proveedores'        => ['proveedores.index', 'Proveedores/Index'],
            'productos'          => ['productos.index', 'Productos/Index'],
            'medicamentos'       => ['medicamentos.index', 'Medicamentos/Index'],
            'medicos'            => ['medicos.index', 'Medicos/Index'],
            'personal_archivos'  => ['personal_archivos.index', 'PersonalArchivos/Index'],
            'derecho_habientes'  => ['derecho_habientes.index', 'DerechoHabientes/Index'],
            'expedientes'        => ['expedientes.index', 'Expedientes/Index'],
            'documentos'         => ['documentos.index', 'Documentos/Index'],
            'prestamos'          => ['prestamos.index', 'Prestamos/Index'],
            'solicitudes'        => ['solicitudes.index', 'Solicitudes/Index'],
            'recetas'            => ['recetas.index', 'Recetas/Index'],
            'dispensaciones'     => ['dispensaciones.index', 'Dispensaciones/Index'],
            'entradas_farmacia'  => ['entradas_farmacia.index', 'EntradasFarmacia/Index'],
            'salidas_farmacia'   => ['salidas_farmacia.index', 'SalidasFarmacia/Index'],
            'alertas'            => ['farmacia.alertas', 'Farmacia/Alertas'],
            'entradas_almacen'   => ['entradas_almacen.index', 'EntradasAlmacen/Index'],
            'salidas_almacen'    => ['salidas_almacen.index', 'SalidasAlmacen/Index'],
            'transferencias'     => ['transferencias.index', 'Transferencias/Index'],
            'existencias'        => ['almacen.existencias', 'Almacen/Existencias'],
            'movimientos'        => ['movimientos.index', 'Movimientos/Index'],
            'usuarios'           => ['usuarios.index', 'Usuarios/Index'],
        ];
    }

    /**
     * @dataProvider rutasIndex
     */
    public function test_index_renderiza_componente_inertia(string $ruta, string $componente): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        $this->get(route($ruta))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component($componente));
    }

    /**
     * Cada ruta create -> componente Inertia esperado.
     *
     * @return array<string, array{0:string, 1:string}>
     */
    public static function rutasCreate(): array
    {
        return [
            'proveedores'       => ['proveedores.create', 'Proveedores/Create'],
            'productos'         => ['productos.create', 'Productos/Create'],
            'medicamentos'      => ['medicamentos.create', 'Medicamentos/Create'],
            'medicos'           => ['medicos.create', 'Medicos/Create'],
            'personal_archivos' => ['personal_archivos.create', 'PersonalArchivos/Create'],
            'derecho_habientes' => ['derecho_habientes.create', 'DerechoHabientes/Create'],
            'expedientes'       => ['expedientes.create', 'Expedientes/Create'],
            'documentos'        => ['documentos.create', 'Documentos/Create'],
            'prestamos'         => ['prestamos.create', 'Prestamos/Create'],
            'solicitudes'       => ['solicitudes.create', 'Solicitudes/Create'],
            'recetas'           => ['recetas.create', 'Recetas/Create'],
            'entradas_farmacia' => ['entradas_farmacia.create', 'EntradasFarmacia/Create'],
            'salidas_farmacia'  => ['salidas_farmacia.create', 'SalidasFarmacia/Create'],
            'entradas_almacen'  => ['entradas_almacen.create', 'EntradasAlmacen/Create'],
            'salidas_almacen'   => ['salidas_almacen.create', 'SalidasAlmacen/Create'],
            'transferencias'    => ['transferencias.create', 'Transferencias/Create'],
            'usuarios'          => ['usuarios.create', 'Usuarios/Create'],
        ];
    }

    /**
     * @dataProvider rutasCreate
     */
    public function test_create_renderiza_componente_inertia(string $ruta, string $componente): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        $this->get(route($ruta))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component($componente));
    }

    public function test_invitado_es_redirigido_a_login(): void
    {
        $this->get(route('medicos.index'))->assertRedirect(route('login'));
    }
}
