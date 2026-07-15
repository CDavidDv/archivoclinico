<?php

namespace Database\Factories;

use App\Models\LoteAlmacen;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoteAlmacen>
 */
class LoteAlmacenFactory extends Factory
{
    protected $model = LoteAlmacen::class;

    public function definition(): array
    {
        return [
            'id_producto'     => Producto::factory(),
            'numero_lote'     => fake()->unique()->bothify('LA-#####'),
            'caducidad'       => fake()->dateTimeBetween('+2 months', '+2 years')->format('Y-m-d'),
            'cantidad_actual' => fake()->numberBetween(10, 100),
        ];
    }

    public function caducado(): static
    {
        return $this->state(fn () => [
            'caducidad' => now()->subDays(fake()->numberBetween(1, 90))->toDateString(),
        ]);
    }

    public function porCaducar(int $dias = 15): static
    {
        return $this->state(fn () => [
            'caducidad' => now()->addDays($dias)->toDateString(),
        ]);
    }

    public function sinCaducidad(): static
    {
        return $this->state(fn () => ['caducidad' => null]);
    }
}
