<?php

namespace App\Console;

use App\Jobs\ActualizarPreafiliaciones;
use App\Jobs\EjecutarEventoAgendaWebCache;
use App\Jobs\ProcesosAutomatizadosAfiliacion;
use App\Jobs\ProcesosAutomatizadosPlataforma;
use App\Jobs\ProcesosAutomatizadosVigencia;
use App\Jobs\ReplicarAgendasWebApk;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        /*$schedule->job(new EjecutarEventoAgendaWebCache); //->dailyAt('18:00');

        //->dailyAt('17:08');

        $schedule->job(new ActualizarPreafiliaciones)->dailyAt('12:47');

        $schedule->job(new ReplicarAgendasWebApk)->dailyAt('17:05');

        $schedule->job(new ProcesosAutomatizadosVigencia)->dailyAt('00:30');

        $schedule->job(new ProcesosAutomatizadosPlataforma)->dailyAt('00:55');

        $schedule->job(new ProcesosAutomatizadosAfiliacion)->dailyAt('01:15');*/

        $schedule->command('cache:clear')->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
