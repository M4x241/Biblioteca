<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Usuario;
use App\Models\Libro;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = Usuario::all();
        $libros = Libro::all();

        if ($usuarios->isEmpty() || $libros->isEmpty()) {
            return;
        }

        // // crear reservas aleatorias
        // foreach ($usuarios as $usuario) {
        //     $libro = $libros->random();
        //     Reserva::create([
        //         'usuario_id' => $usuario->id,
        //         'libro_id' => $libro->id,
        //         'fecha_reserva' => now(),
        //         'fecha_vencimiento' => now()->addDays(7),
        //         'estado' => 'activa',
        //     ]);
        // }
    }
}
