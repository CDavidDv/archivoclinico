<?php

namespace Database\Factories;

use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medico>
 */
class MedicoFactory extends Factory
{
    protected $model = Medico::class;

    public function definition(): array
    {
        return [
            'nombre'           => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'rfc'              => strtoupper(fake()->unique()->bothify('????######???')),
            'numero_empleado'  => fake()->unique()->numerify('EMP-####'),
            'cargo'            => 'Médico General',
            'area'             => 'Consulta Externa',
            'tipo'             => fake()->randomElement(['base', 'confianza', 'residente']),
        ];
    }
}
