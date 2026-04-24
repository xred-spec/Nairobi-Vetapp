<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Animales;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Raza>
 */
class RazaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'animal_id' => Animales::factory(),
            'visibilidad' => 'visible'
        ];
    }
}
