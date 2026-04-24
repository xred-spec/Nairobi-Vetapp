<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;
use App\Models\Raza;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mascota>
 */
class MascotaFactory extends Factory
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
            'sexo' =>fake()->randomElement(['macho', 'hembra']),
            'peso' => fake()->numberBetween(5, 20),
            'descripcion' => 'descripción de mascota',
            'fecha_nacimiento' => fake()->date(),
            'visibilidad' => 'visible',
            'cliente_id' => fake()->numberBetween(1, 104),
            'raza_id' => fake()->numberBetween(1, 27)
        ];
    }
}
