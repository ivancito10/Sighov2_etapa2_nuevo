<?php

namespace App\Services\ConsultaExterna;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;

class ObtencionQrDocumentos
{
    public function generarCodigoDocumento($fecha)
    {
        $servicioDocumentos = new ObtenerToken();
        $servicioDocumentos->refresh_token();

        $servicio = LinkServicio::where('nombre_servicio', 'GENERAR QR')->first();

        $token = session('access_token');

        $response = Http::withToken($token)->post($servicio->link_servicio, ['fecha_generado' => $fecha]);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
    }

    public function registrarDatosDocumento(array $data)
    {
        $servicioDocumentos = new ObtenerToken();
        $servicioDocumentos->refresh_token();

        $servicio = LinkServicio::where('nombre_servicio', 'REGISTRAR PDF')->first();

        $token = session('access_token');

        $response = Http::withToken($token)->post($servicio->link_servicio, $data);
        //dd($response->json());
        return $response->json();
    }
}
