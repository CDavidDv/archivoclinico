<?php

namespace Database\Factories;

use App\Models\PersonalArchivo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonalArchivo>
 */
class PersonalArchivoFactory extends Factory
{
    protected $model = PersonalArchivo::class;

    public function definition(): array
    {
        return [
            'nombre'           => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'rfc'              => strtoupper(fake()->unique()->bothify('????######???')),
            'numero_empleado'  => fake()->unique()->numerify('ARC-####'),
            'cargo'            => 'Auxiliar de Archivo',
            'area'             => 'Archivo Clínico',
            'tipo'             => fake()->randomElement(['base', 'confianza']),
        ];
    }
}
