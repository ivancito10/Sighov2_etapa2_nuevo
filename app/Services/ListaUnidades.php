<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;
class ListaUnidades
{
    public function listaUnidades()
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTA DE UNIDADES')->first();
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        return null;
    }
}
