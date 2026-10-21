<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;
class ServicioAgendaGeneral
{
    public function obtenerAgendaGeneral($fecha)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'AGENDA GENERAL POR FECHA')->first();
        
        if (!$link_servicio) {
            return null;
        }

        $response = Http::withToken(session('access_token'))
            ->post($link_servicio->link_servicio, [
                'fecha_agenda' => $fecha,
            ]);

        if ($response->successful()) {
            return $response;
        }

        return null;
    }


    public function obtenerAgendaCitaMedicaPersona($id_agenda)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER DATOS AGENDA')->first();
        
        if (!$link_servicio) { return null; }

        $response = Http::withToken(session('access_token'))
            ->post($link_servicio->link_servicio, ['id_agenda' => $id_agenda, ]);
        //dd($response->json()); 
        if ($response->successful()) {
            return $response;
        } 

        return null;
    }
}
