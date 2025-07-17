<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        $categorias = [
    "Electrónica",
    "Moda",
    "Hogar",
    "Juguetes",
    "Deportes",
    "Belleza",
    "Alimentos",
    "Automotriz",
    "Libros",
    "Mascotas"];
        
        return [
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
            'precio' => $this->faker->randomFloat(2, 1, 1000),
            'cantidad' => $this->faker->numberBetween(1, 100),
            'categoria' => $this->faker->randomElement($categorias),
            'codigo_barras' => $this->faker->unique()->ean13(),
            'activo' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
            'imagen' => $this->faker->imageUrl(640, 480, 'products', true, 'Faker', true)
        ];
    }
}
