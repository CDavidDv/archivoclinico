<?php

namespace Database\Factories;

use App\Models\DerechoHabiente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DerechoHabiente>
 */
class DerechoHabienteFactory extends Factory
{
    protected $model = DerechoHabiente::class;

    public function definition(): array
    {
        $rfc = strtoupper(fake()->unique()->bothify('????######???'));

        return [
            'nombre'               => fake()->firstName(),
            'apellido_paterno'     => fake()->lastName(),
            'apellido_materno'     => fake()->lastName(),
            'rfc'                  => $rfc,
            'nss'                  => fake()->unique()->numerify('###########'),
            'clave_identificacion' => fake()->numberBetween(1, 99),
            'clave_generada'       => substr($rfc, 0, 10) . '/' . fake()->numberBetween(1, 99),
            'fecha_nacimiento'     => fake()->date('Y-m-d', '-18 years'),
            'genero'               => fake()->randomElement(['M', 'F']),
            'fecha_registro'       => now()->toDateString(),
        ];
    }
}
