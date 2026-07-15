<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre_usuario' => fake()->unique()->userName(),
            'email'          => fake()->unique()->safeEmail(),
            'telefono'       => fake()->numerify('##########'),
            'password'       => 'password',
            'rol'            => fake()->randomElement(Usuario::ROLES),
        ];
    }

    public function rol(string $rol): static
    {
        return $this->state(fn () => ['rol' => $rol]);
    }

    public function administrador(): static
    {
        return $this->rol(Usuario::ROL_ADMINISTRADOR);
    }
}
