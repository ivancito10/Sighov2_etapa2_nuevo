<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivateRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = ['127.0.0.1', '::1']; // Solo el servidor local

        if (!in_array($request->ip(), $allowedIps)) {
            return response()->json([
                'status' => 403,
                'success' => false,
                'message' => 'Acceso restringido: solo el sistema puede llamar esta ruta',
            ], 403);
        }
        return $next($request);
    }
}
