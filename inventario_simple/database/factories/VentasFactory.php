<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ventas>
 */
class VentasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'referencia' => $this->faker->unique()->numerify('VENTA-#####'),
            'tipo' => $this->faker->randomElement(['manual', 'compra','traslado']),  // manual, compra, traslado, etc.
            'observaciones' => $this->faker->optional()->text(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
