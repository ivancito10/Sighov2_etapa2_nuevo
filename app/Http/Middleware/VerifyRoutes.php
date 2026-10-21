<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;

class VerifyRoutes
{
    protected $exceptRoutes = [
        'api/obtener_imagen_usuario/*',
        'api/obtener_imagen_persona/*',
        'api/obtener_imagen_especialista/*',
        // 'api/publico/descargar_documento/*',
        // 'api/sin-token/*', // Puedes agregar todas las que quieras aquí
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->exceptRoutes as $ruta) {
            if ($request->is($ruta)) {
                if (URL::hasValidSignature($request)) {
                    return $next($request);
                } else {
                    return response()->json([
                        'status' => 403,
                        'success' => false,
                        'message' => 'Firma inválida o expirada',
                    ], 403);
                }
            }
        }
        return $next($request);
    }
}
