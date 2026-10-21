<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;

class DatosEspecialista
{

    public function datosEspecialista($id_esp)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER DATOS DE ESPECIALISTA')->first();

        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio . $id_esp);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }

    public function listaEspecialistas()
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTAR ESPECIALISTAS')->first();
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio);
        //dd(session('access_token'));
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        return null;
    }

    public function datosEspecialista_ci($ci)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER DATOS DE ESPECIALISTA CI')->first();

        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio . $ci);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
