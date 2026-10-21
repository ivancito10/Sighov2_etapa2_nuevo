<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;
use App\Models\Laboratorio\Solicitudes;

class LaboratorioResultados
{
    // Add your service logic here
    public function datosLaboratorioResultados($id_solicitud)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'RESULTADO EXAMEN PDF')->first();
        // return [$link_servicio->link_servicio,$id_solicitud];
        $response = Http::withToken(session('access_token'))
            ->post($link_servicio->link_servicio, [
                'id_solicitud' => $id_solicitud
            ]);
        // return $response->json();
        // //dd($response, $link_servicio); 
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }
}
