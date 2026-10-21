<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ObtenerCuentaContablePorId
{
    public function obtenerCuentaContablePorId($idCuenta)
    {

        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        // Definimos una clave única para identificar los datos en caché
        $cacheKey = "cuenta_contable_{$idCuenta}";

        return Cache::remember($cacheKey, now()->addDay(), function () use ($idCuenta) {
            // Si no existe en caché, se ejecutará este closure
            $linkSolo = LinkServicio::where('nombre_servicio', 'OBTENER CUENTA CONTABLE POR ID')->first();

            $link_servicio = $linkSolo->link_servicio.$idCuenta;

            $response = Http::withToken(session('access_token'))->get($link_servicio);

            if ($response->successful()) {
                return $response->json()['data'][0] ?? null;
            }

            return null;
        });
    }
}
