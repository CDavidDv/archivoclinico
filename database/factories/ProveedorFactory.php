<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proveedor>
 */
class ProveedorFactory extends Factory
{
    protected $model = Proveedor::class;

    public function definition(): array
    {
        return [
            'nombre'    => fake()->company(),
            'rfc'       => strtoupper(fake()->unique()->bothify('???######???')),
            'telefono'  => fake()->numerify('##########'),
            'email'     => fake()->unique()->companyEmail(),
            'direccion' => fake()->address(),
            'activo'    => true,
        ];
    }
}
