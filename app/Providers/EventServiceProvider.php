<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\VerificarCachePoblacionAsegurada;
use App\Listeners\VerificarCachePoblacionAseguradaListener;
use App\Listeners\VerificarCacheEspecialidadesListener;
use App\Listeners\VerificarCacheEspecialistasListener;
use App\Listeners\VerificarCacheHorariosEspecialistasListener;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Evento de autenticación por defecto
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // 🔥 Tu evento personalizado
        VerificarCachePoblacionAsegurada::class => [
            VerificarCachePoblacionAseguradaListener::class,
            VerificarCacheEspecialidadesListener::class,
            VerificarCacheEspecialistasListener::class,
            VerificarCacheHorariosEspecialistasListener::class,
            
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
