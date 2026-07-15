<?php

namespace Database\Factories;

use App\Models\LoteFarmacia;
use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoteFarmacia>
 */
class LoteFarmaciaFactory extends Factory
{
    protected $model = LoteFarmacia::class;

    public function definition(): array
    {
        return [
            'id_medicamento'  => Medicamento::factory(),
            'numero_lote'     => fake()->unique()->bothify('LF-#####'),
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
}
