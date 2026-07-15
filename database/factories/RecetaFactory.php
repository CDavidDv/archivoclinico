<?php

namespace Database\Factories;

use App\Models\DerechoHabiente;
use App\Models\Medico;
use App\Models\Receta;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Receta>
 */
class RecetaFactory extends Factory
{
    protected $model = Receta::class;

    public function definition(): array
    {
        return [
            'id_derecho_habiente' => DerechoHabiente::factory(),
            'id_medico'           => Medico::factory(),
            'id_usuario'          => Usuario::factory()->rol(Usuario::ROL_MEDICO),
            'fecha_receta'        => now()->toDateString(),
            'diagnostico'         => fake()->sentence(3),
            'estatus'             => Receta::ESTATUS_PENDIENTE,
        ];
    }

    public function surtida(): static
    {
        return $this->state(fn () => ['estatus' => Receta::ESTATUS_SURTIDA]);
    }

    public function cancelada(): static
    {
        return $this->state(fn () => ['estatus' => Receta::ESTATUS_CANCELADA]);
    }
}
