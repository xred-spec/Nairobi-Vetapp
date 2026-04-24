<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'municipio' => fake()->lastName(),
            'colonia' => fake()->lastName(),
            'codigo_postal' => '20000',
            'calle' => fake()->firstName(),
            'numero_exterior' => fake()->numberBetween(1, 100), 
            'user_id' => User::factory()
        ];
    }
}
