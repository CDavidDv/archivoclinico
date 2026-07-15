<?php

namespace Database\Factories;

use App\Models\DetalleSolicitudAbastecimiento;
use App\Models\Producto;
use App\Models\SolicitudAbastecimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetalleSolicitudAbastecimiento>
 */
class DetalleSolicitudAbastecimientoFactory extends Factory
{
    protected $model = DetalleSolicitudAbastecimiento::class;

    public function definition(): array
    {
        return [
            'id_solicitud'         => SolicitudAbastecimiento::factory(),
            'id_producto'          => Producto::factory(),
            'cantidad_solicitada'  => fake()->numberBetween(1, 50),
            'cantidad_surtida'     => 0,
        ];
    }
}
