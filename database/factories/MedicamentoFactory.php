<?php

namespace Database\Factories;

use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicamento>
 */
class MedicamentoFactory extends Factory
{
    protected $model = Medicamento::class;

    public function definition(): array
    {
        return [
            'clave'            => fake()->unique()->bothify('MED-####'),
            'nombre'           => fake()->words(2, true),
            'sustancia_activa' => fake()->word(),
            'presentacion'     => 'Tabletas 500 mg, caja c/20',
            'unidad_medida'    => 'pieza',
            'stock_minimo'     => fake()->numberBetween(5, 20),
            'id_producto'      => null,
            'activo'           => true,
        ];
    }
}
