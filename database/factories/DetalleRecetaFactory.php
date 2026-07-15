<?php

namespace Database\Factories;

use App\Models\DetalleReceta;
use App\Models\Medicamento;
use App\Models\Receta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetalleReceta>
 */
class DetalleRecetaFactory extends Factory
{
    protected $model = DetalleReceta::class;

    public function definition(): array
    {
        return [
            'id_receta'          => Receta::factory(),
            'id_medicamento'     => Medicamento::factory(),
            'cantidad_prescrita' => fake()->numberBetween(1, 10),
            'cantidad_surtida'   => 0,
            'dosis'              => '1 cada 8 horas',
        ];
    }
}
