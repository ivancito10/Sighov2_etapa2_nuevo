<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\ObtenerToken;

class ObtenerDatosPersonalSsu
{
    //funcion antigua para recuperar el personal del ssu con cache
    public function obtenerDatosPersonalSsu_cache($idPersona)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        //dd(LinkServicio::where('nombre_servicio', 'OBTENER DATOS PERSONAL SSU')->first());
        // Clave única para la caché
        $cacheKey = "datos_personal_ssu_{$idPersona}";

        // Guardamos/recuperamos de caché durante 1 día
        return Cache::remember($cacheKey, now()->addDay(), function () use ($idPersona) {
            $linkSolo = LinkServicio::where('nombre_servicio', 'OBTENER DATOS PERSONAL SSU')->first();
            //dd($linkSolo);
            if (!$linkSolo) {
                return null;
            }

            $link_servicio = $linkSolo->link_servicio . $idPersona;
            $response = Http::withToken(session('access_token'))->get($link_servicio);
            // dd($response);
            if ($response->successful()) {
                // Retornamos solo la primera posición del 'data' o null
                return $response->json()['data'][0] ?? null;
            }
            //dd($response->json()['data'][0]);
            return null;
        });
    }
    public function obtenerDatosPersonalSsu($idPersona)
    {
        $authTokenService = new ObtenerToken();
        
        $authTokenService->refresh_token();
        $linkSolo = LinkServicio::where('nombre_servicio', 'OBTENER DATOS PERSONAL SSU')->first();
        $link_servicio = $linkSolo->link_servicio . $idPersona;
        $response = Http::withToken(session('access_token'))->get($link_servicio);
        //dd($response->json());
        if ($response->successful()) {
            // Retornamos solo la primera posición del 'data' o null
            return $response->json()['data'][0] ?? null;
        }
        //dd($response->json()['data'][0]);
        return null;
    }
}
