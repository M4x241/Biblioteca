<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prestamo;
use Carbon\Carbon;

class ExpireDevoluciones extends Command
{
    protected $signature = 'devoluciones:expire';
    protected $description = 'Marcar préstamos vencidos como atrasados';

    public function handle()
    {
        while (true) {
            $this->info("Comando en bucle iniciado... presiona Ctrl+C para detener.");
            $hoy = Carbon::now();

            $updated = Prestamo::where('estado', Prestamo::STATUS_OCUPADO)
            ->where('fecha_devolucion', '<', $hoy)
            ->update(['estado' => Prestamo::STATUS_ATRASADO]);

             $this->info("Préstamos vencidos actualizados: {$updated}");

            sleep(5);
        }
    }
}