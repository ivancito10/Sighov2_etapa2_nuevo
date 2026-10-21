<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PruebaColaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
        // Esto se verá en la terminal donde corras el worker
        echo "¡Hola! Estoy procesando un trabajo en segundo plano desde Redis...\n";

        // También lo guardamos en el log por seguridad
        logger("El Job se ejecutó correctamente a las: " . now());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
