<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;
class ServicioPersonaInstitucion
{
    // Add your service logic here
    public function personaInstitucion($ci_per){
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER INSTITUCIONES DE PERSONA POR ID')->first();
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio.$ci_per);
        //dd($response );
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
