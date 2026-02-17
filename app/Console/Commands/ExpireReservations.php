<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    protected $signature = 'reservas:expire-loop';
    protected $description = 'Marcar reservas pendientes como vencidas continuamente en bucle';

    public function handle()
    {
        while (true) {
            $hoy = Carbon::now();

            $updated = Reserva::where('estado', Reserva::STATUS_CONFIRMED)
                ->where('fecha_vencimiento', '<', $hoy)
                ->update(['estado' => Reserva::STATUS_EXPIRED]);

            if ($updated > 0) {
                $this->info("Reservas vencidas actualizadas: {$updated}");
            }
            sleep(5);
        }
    }
}
