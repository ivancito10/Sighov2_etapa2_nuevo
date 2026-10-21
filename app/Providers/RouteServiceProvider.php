<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/administracion/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/Publicos/publicos.php'));

            //ETAPA 2
            Route::middleware('web')
                ->group(base_path('routes/farmacia/almacen_farmacia.php'));

            Route::middleware('web')
                ->group(base_path('routes/farmacia/farmacia.php'));

            Route::middleware('web')
                ->group(base_path('routes/solicitudes/solicitudes.php'));

            Route::middleware('web')
                ->group(base_path('routes/atencion_medica/atencion_medica.php'));

            Route::middleware('web')
                ->group(base_path('routes/almacen_general/almacen_general.php'));

            Route::middleware('web')
                ->group(base_path('routes/atencion_medica/agendas.php'));

            Route::middleware('web')
                ->group(base_path('routes/bioestadistica/bioestadistica.php'));

            Route::middleware('web')
                ->group(base_path('routes/liquidaciones/liquidaciones.php'));

            Route::middleware('web')
            ->group(base_path('routes/fisioterapia/fisioterapia.php'));

            Route::middleware('web')
                ->group(base_path('routes/enfermeria/enfermeria.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
