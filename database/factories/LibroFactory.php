<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Usar picsum.photos para imágenes aleatorias de libros
        $randomId = $this->faker->numberBetween(1, 1000);
        
        return [
            'titulo' => $this->faker->sentence(3),
            'imagen' => "https://picsum.photos/seed/{$randomId}/300/450",
            'autor' => $this->faker->name,
            'categoria' => $this->faker->word,
            'sinopsis' => $this->faker->paragraph,
        ];
    }
}