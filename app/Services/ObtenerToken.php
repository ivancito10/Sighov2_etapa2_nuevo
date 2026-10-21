<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ObtenerToken
{
    public function obtenerTokenConServicio()
    {
        //$token = null;
        $url_service = LinkServicio::where('nombre_servicio', 'like', 'OBTENER TOKEN MICROSERVICIO')->first();
        //dd('link:'.$url_service);
        if ($url_service != null) {
            $login = $url_service->link_servicio;
            $credenciales = [
                // 'id_user' => auth()->user()->id,
                'email' => auth()->user()->email,
                'password' => auth()->user()->password
            ];
            //dd($credenciales);
            $response = Http::post($login, $credenciales);
            //dd($response, $response->json());
            if ($response->successful()) {
                $accessToken = $response->json('access_token');
                // Guardar el access_token en la sesión
                session(['access_token' => $accessToken]);
                return true;
            }
            return false;
        }
    }

    public function obtenerToken(): ?string
    {
        try {
            $token = session('access_token');
            if (!$token) {
                return $this->obtenerTokenConServicio();
            }
            $url = LinkServicio::where('nombre_servicio', 'VERIFICACION TOKEN MICROSERVICIOS')->value('link_servicio');
            $res = Http::withToken($token)->get($url);
            //dd($res->status());
            if ($res->status() === 401) {
                // expiró o es inválido
                return $this->obtenerTokenConServicio();
            }
            session(['access_token' => $token]);
            return true;
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al obtener token", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    }

    /*   public function refresh_token()
    {
        try {
            $token = session('access_token');
            $url_service = LinkServicio::where('nombre_servicio', 'like', 'REFRESH TOKEN MICROSERVICIOS')->first();
            $response = Http::withToken($token)->post($url_service->link_servicio);
            //dd($response, $response->json());
            if ($response->successful()) {
                $accessToken = $response->json('access_token');
                session(['access_token' => $accessToken]);
            }
            if ($response->status() != 200) {
                $this->obtenerTokenConServicio();
                // return 'no hay token bro';
            }
            return $token;
        } catch (\Throwable $e) {
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));

            Log::error("Error al refrescar token", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
            return null;
        }
    } */

    // lo nuevo 
    public function verificarToken()
    {
        // return 'weno';
        $token = session('access_token');
        $url_service = LinkServicio::where('nombre_servicio', 'like', 'VERIFICACION TOKEN MICROSERVICIOS')->first();
        // dd($url_service->link_servicio);
        $response = Http::withToken($token)->get($url_service->link_servicio);

        if (!$response->status() != 200) {
            $this->obtenerToken_con_servicio();
            // return 'no hay token bro';
        }
        return $token;
    }
    public function obtenerToken_con_servicio()
    {
        $token = null;
        $url_service = LinkServicio::where('nombre_servicio', 'like', 'OBTENER TOKEN MICROSERVICIO')->first();
        //dd($url_service->link_servicio);
        if ($url_service != null) {
            //dd('entro');
            $login = $url_service->link_servicio;
            $credenciales = [
                // 'id_user' => auth()->user()->id,
                'email' => auth()->user()->email,
                'password' => auth()->user()->password
            ];
            // dd($credenciales);
            $response = Http::post($login, $credenciales);
            //dd($response);
            if ($response->successful()) {
                $accessToken = $response->json('access_token');

                // Guardar el access_token en la sesión
                session(['access_token' => $accessToken]);
                // dd('token');
                // Verificar que se haya guardado correctamente
                // dd(session('access_token'),'sesion'); // Debería mostrar el token
            }
            // dd($response->json('access_token'));
        }
    }
    public function refresh_token()
    {
        $token = session('access_token');
        $url_service = LinkServicio::where('nombre_servicio', 'like', 'REFRESH TOKEN MICROSERVICIOS')->first();
        $response = Http::withToken($token)->post($url_service->link_servicio);
        if ($response->successful()) {
            $accessToken = $response->json('access_token');
            session(['access_token' => $accessToken]);
        }
        if ($response->status() != 200) {
            $this->obtenerToken_con_servicio();
            // return 'no hay token bro';
        }
        return $token;
    }
}
