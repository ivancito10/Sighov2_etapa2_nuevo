<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ObtenerDatosUnidadPorId
{
    public function obtenerDatosUnidadPorId($idUnidad)
    {
        // Clave única para la caché
        $cacheKey = "datos_unidad_{$idUnidad}";
        // Guardamos/recuperamos de caché durante 1 día
        return Cache::remember($cacheKey, now()->addDay(), function () use ($idUnidad) {
            $linkSolo = LinkServicio::where('nombre_servicio', 'OBTENER DATOS UNIDAD POR ID')->first();
            
            if (!$linkSolo) {
                return null;
            }

            $link_servicio = $linkSolo->link_servicio . $idUnidad;
            $response = Http::withToken(session('access_token'))->get($link_servicio);
            
            if ($response->successful()) {
                // Retornamos todo el 'data' o null
                return $response->json()['data'] ?? null;
            } else {
                // respuesta para ver el error, manejar en todos los servicios
                //dd($response->json());
                // $respuesta['status'] = $response->json()['status'];
                // $respuesta['message'] = $response->json()['message'];
                $respuesta['params'] = $idUnidad;
                // $respuesta['error'] = $response->json()['error'] ?? null;
                //dd($respuesta);
                return  $respuesta ?? null;
            }

            return null;
        });
    }
}
