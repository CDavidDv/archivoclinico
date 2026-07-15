<?php

namespace Tests\Feature;

use App\Models\DerechoHabiente;
use App\Models\Medicamento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolAccesoTest extends TestCase
{
    use RefreshDatabase;

    /* =====================================================
       ARCHIVO CLÍNICO — gestión (solo archivo)
    ===================================================== */

    public function test_archivo_puede_acceder_a_gestion_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_ARCHIVO);

        $this->get(route('derecho_habientes.create'))->assertOk();
        $this->get(route('expedientes.create'))->assertOk();
        $this->get(route('documentos.create'))->assertOk();
        $this->get(route('medicos.index'))->assertOk();
        $this->get(route('personal_archivos.index'))->assertOk();
        $this->get(route('prestamos.index'))->assertOk();
    }

    public function test_medico_no_puede_acceder_a_gestion_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('derecho_habientes.create'))->assertForbidden();
        $this->post(route('derecho_habientes.store'), [])->assertForbidden();
    }

    public function test_farmacia_no_puede_acceder_a_gestion_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('derecho_habientes.create'))->assertForbidden();
    }

    public function test_almacen_no_puede_acceder_a_gestion_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $this->get(route('derecho_habientes.create'))->assertForbidden();
    }

    public function test_administrador_puede_acceder_a_gestion_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        $this->get(route('derecho_habientes.create'))->assertOk();
        $this->get(route('expedientes.create'))->assertOk();
    }

    /* =====================================================
       ARCHIVO CLÍNICO — consulta (archivo, medico)
    ===================================================== */

    public function test_medico_puede_consultar_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('derecho_habientes.index'))->assertOk();
    }

    public function test_farmacia_no_puede_consultar_archivo(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('derecho_habientes.index'))->assertForbidden();
    }

    /* =====================================================
       FARMACIA
    ===================================================== */

    public function test_farmacia_puede_acceder_a_modulos_farmacia(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('medicamentos.index'))->assertOk();
        $this->get(route('farmacia.alertas'))->assertOk();
    }

    public function test_archivo_no_puede_acceder_a_farmacia(): void
    {
        $this->actingAsRol(Usuario::ROL_ARCHIVO);

        $this->get(route('medicamentos.index'))->assertForbidden();
    }

    public function test_almacen_no_puede_acceder_a_farmacia(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $this->get(route('medicamentos.index'))->assertForbidden();
    }

    /* =====================================================
       ALMACÉN
    ===================================================== */

    public function test_almacen_puede_acceder_a_modulos_almacen(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $this->get(route('proveedores.index'))->assertOk();
        $this->get(route('productos.index'))->assertOk();
        $this->get(route('almacen.existencias'))->assertOk();
    }

    public function test_farmacia_no_puede_acceder_a_almacen(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('proveedores.index'))->assertForbidden();
    }

    public function test_medico_no_puede_acceder_a_almacen(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('proveedores.index'))->assertForbidden();
    }

    /* =====================================================
       ADMINISTRACIÓN (solo administrador)
    ===================================================== */

    public function test_administrador_puede_acceder_a_administracion(): void
    {
        $this->actingAsRol(Usuario::ROL_ADMINISTRADOR);

        $this->get(route('usuarios.index'))->assertOk();
        $this->get(route('movimientos.index'))->assertOk();
    }

    public function test_archivo_no_puede_acceder_a_administracion(): void
    {
        $this->actingAsRol(Usuario::ROL_ARCHIVO);

        $this->get(route('usuarios.index'))->assertForbidden();
    }

    public function test_medico_no_puede_acceder_a_administracion(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('usuarios.index'))->assertForbidden();
    }

    public function test_farmacia_no_puede_acceder_a_administracion(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('usuarios.index'))->assertForbidden();
    }

    public function test_almacen_no_puede_acceder_a_administracion(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $this->get(route('usuarios.index'))->assertForbidden();
    }

    /* =====================================================
       RECETAS (medico crea, farmacia ve)
    ===================================================== */

    public function test_medico_puede_crear_recetas(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('recetas.create'))->assertOk();
    }

    public function test_farmacia_no_puede_crear_recetas(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('recetas.create'))->assertForbidden();
    }

    public function test_farmacia_puede_ver_cola_recetas(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('recetas.index'))->assertOk();
    }

    public function test_medico_puede_ver_cola_recetas(): void
    {
        $this->actingAsRol(Usuario::ROL_MEDICO);

        $this->get(route('recetas.index'))->assertOk();
    }

    public function test_archivo_no_puede_ver_recetas(): void
    {
        $this->actingAsRol(Usuario::ROL_ARCHIVO);

        $this->get(route('recetas.index'))->assertForbidden();
    }

    /* =====================================================
       SOLICITUDES DE ABASTECIMIENTO
    ===================================================== */

    public function test_farmacia_puede_crear_solicitudes(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $this->get(route('solicitudes.create'))->assertOk();
    }

    public function test_almacen_puede_aprobar_solicitudes(): void
    {
        $this->actingAsRol(Usuario::ROL_ALMACEN);

        $solicitud = \App\Models\SolicitudAbastecimiento::factory()->create();

        $this->put(route('solicitudes.aprobar', $solicitud))->assertRedirect();
    }

    public function test_farmacia_no_puede_aprobar_solicitudes(): void
    {
        $this->actingAsRol(Usuario::ROL_FARMACIA);

        $solicitud = \App\Models\SolicitudAbastecimiento::factory()->create();

        $this->put(route('solicitudes.aprobar', $solicitud))->assertForbidden();
    }
}
