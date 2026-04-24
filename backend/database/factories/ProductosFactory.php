<?php

namespace Database\Factories;

use App\Models\Productos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productos>
 */
class ProductosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->word(),
            'stock' => fake()->numberBetween(1, 100),
            'precio_compra' => fake()->randomFloat(2, 100, 500),
            'precio_venta' => fake()->randomFloat(2, 600, 1000),
            'cantidad' => fake()->numberBetween(1, 50),
            'marca' => fake()->company(),
            'visibilidad' => 'visible',
            'medida' => 'unidad',
        ];
    }
}
