<?php

namespace Database\Factories;

use App\Models\SolicitudAbastecimiento;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SolicitudAbastecimiento>
 */
class SolicitudAbastecimientoFactory extends Factory
{
    protected $model = SolicitudAbastecimiento::class;

    public function definition(): array
    {
        return [
            'modulo_solicitante'  => SolicitudAbastecimiento::MODULO_FARMACIA,
            'id_usuario_solicita' => Usuario::factory()->rol(Usuario::ROL_FARMACIA),
            'estatus'             => SolicitudAbastecimiento::ESTATUS_PENDIENTE,
            'fecha_solicitud'     => now()->toDateString(),
        ];
    }

    public function aprobada(): static
    {
        return $this->state(fn () => [
            'estatus' => SolicitudAbastecimiento::ESTATUS_APROBADA,
        ]);
    }

    public function rechazada(): static
    {
        return $this->state(fn () => [
            'estatus'        => SolicitudAbastecimiento::ESTATUS_RECHAZADA,
            'motivo_rechazo' => 'Sin presupuesto',
        ]);
    }

    public function delArchivo(): static
    {
        return $this->state(fn () => [
            'modulo_solicitante' => SolicitudAbastecimiento::MODULO_ARCHIVO,
        ]);
    }
}
