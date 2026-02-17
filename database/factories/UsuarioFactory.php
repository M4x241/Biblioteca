<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'correo' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('secret'),
            'rol' => 'usuario',
            'telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'estado' => 'activo',
        ];
    }
}
