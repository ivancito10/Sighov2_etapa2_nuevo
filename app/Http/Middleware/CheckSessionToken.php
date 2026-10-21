<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSessionToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user && $request->session()->get('session_token') !== $user->session_token) {
            Auth::logout(); // Cierra la sesión del usuario

            // Opcional: puedes redirigir al usuario a la página de inicio de sesión con un mensaje
            return redirect('login')->withErrors('Tu sesión ha finalizado porque has iniciado sesión desde otro dispositivo.');
        }

        return $next($request);
    }

}
