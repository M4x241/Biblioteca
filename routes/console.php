<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Registrar tareas programadas aquí para que `schedule:list` las detecte
// Usamos app(Schedule::class) para registrar las tareas en el scheduler
if (app()->bound(Schedule::class)) {
    $schedule = app(Schedule::class);

    // Ejecutar el comando que expira reservas cada minuto
    $schedule->command('reservas:expire-loop')
             ->everyMinute()
             ->appendOutputTo(storage_path('logs/scheduler.log'));

    // Ejecutar el comando que marca préstamos vencidos cada 5 minutos
    $schedule->command('devoluciones:expire') // { changed code }
             ->everyFiveMinutes()              // { changed code }
             ->appendOutputTo(storage_path('logs/scheduler.log')); // { changed code }

            // Mantener el scheduler en ejecución continua (usa `schedule:work`)
            $schedule->command('schedule:work')
                     ->runInBackground()
                     ->withoutOverlapping()
                     ->appendOutputTo(storage_path('logs/scheduler.log'));
}
