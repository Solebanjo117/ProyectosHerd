<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salidas>
 */
class SalidasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           
            'tipo' => $this->faker->randomElement(['venta', 'ajuste']),
            'cliente_id' => null, // Puede ser nulo para ajustes
            'referencia' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['pendiente', 'completado', 'cancelado']),
            'observaciones' => $this->faker->optional()->paragraph(),
        ];
    }
}
