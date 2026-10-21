<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;

class ListaCuentasContables
{
    public function listaCuentasContables(){
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTA DE CUENTAS CONTABLES')->first();
        // dd($link_servicio);
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio);
        //dd($response->json());
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}