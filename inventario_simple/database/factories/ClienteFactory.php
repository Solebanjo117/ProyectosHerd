<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->phoneNumber(),
            'correo' => $this->faker->unique()->safeEmail(),
            'direccion' => $this->faker->address(),
            'deuda' => $this->faker->randomFloat(2, 0, 1000), // Deuda entre 0 y 1000
        ];
    }
}
