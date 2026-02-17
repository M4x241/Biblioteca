<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
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
            'fecha_reserva' => now(),
            'fecha_vencimiento' => now()->addDays(7),
            'estado' => 'activa',
        ];
    }
}
