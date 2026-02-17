<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestamo>
 */
class PrestamoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => function () {
                return \App\Models\Usuario::factory()->create()->id;
            },
            'libro_id' => function () {
                return \App\Models\Libro::factory()->create()->id;
            },
            'reserva_id' => null,
            'fecha_prestamo' => now(),
            'fecha_devolucion' => null,
            'estado' => 'disponible',
        ];
    }
}
