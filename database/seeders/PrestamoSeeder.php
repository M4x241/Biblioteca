<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prestamo;
use App\Models\Reserva;

class PrestamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservas = Reserva::all(); 

        // foreach ($reservas as $reserva) {
        //     Prestamo::create([
        //         'usuario_id' => $reserva->usuario_id,
        //         'libro_id' => $reserva->libro_id,
        //         'reserva_id' => $reserva->id,
        //         'fecha_prestamo' => now(),
        //         'fecha_devolucion' => null,
        //         'estado' => 'disponible',
        //     ]);
        // }
    }
}
