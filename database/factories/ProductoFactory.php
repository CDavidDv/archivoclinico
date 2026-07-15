<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'clave'         => fake()->unique()->bothify('PROD-####'),
            'nombre'        => fake()->words(3, true),
            'descripcion'   => fake()->sentence(),
            'categoria'     => fake()->randomElement(Producto::CATEGORIAS),
            'unidad_medida' => 'pieza',
            'stock_minimo'  => fake()->numberBetween(5, 20),
            'activo'        => true,
        ];
    }

    public function medicamento(): static
    {
        return $this->state(fn () => ['categoria' => Producto::CATEGORIA_MEDICAMENTO]);
    }

    public function insumo(): static
    {
        return $this->state(fn () => ['categoria' => Producto::CATEGORIA_INSUMO]);
    }
}
