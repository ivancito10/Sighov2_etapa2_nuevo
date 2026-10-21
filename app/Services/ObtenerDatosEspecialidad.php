<?php

namespace App\Services;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;

class ObtenerDatosEspecialidad
{
     public function datosEspecialidad($id_esp){
        
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER DATOS ESPECIALIDAD')->first();
        
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio.$id_esp);
        
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
