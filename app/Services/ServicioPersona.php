<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Http;

class ServicioPersona
{
    public function datosPersonaInstitucion($id_per)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        //$link_servicio = LinkServicio::where('nombre_servicio', 'OBETENER DATOS PERSONA Y SU INSITUCION PRINCIPAL')->first();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBETENER DATOS PERSONA Y SU INSITUCION PRINCIPAL')->first();
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio.$id_per);
        //dd($response['message']);
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }

        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }

    public function datosPersona($id_per)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        //$link_servicio = LinkServicio::where('nombre_servicio', 'OBETENER DATOS PERSONA Y SU INSITUCION PRINCIPAL')->first();
        $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER PERSONA FARMACIA')->first();
        $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio.$id_per);
        //dd($response->status());

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        } else {
            // respuesta para ver el error, manejar en todos los servicios
            //dd($response->json());
            $respuesta['status'] = $response->json()['status'];
            $respuesta['message'] = $response->json()['message'];
            $respuesta['params'] = $id_per;
            $respuesta['error'] = $response->json()['error'] ?? null;

            //dd($respuesta);
            return $respuesta ?? null;
        }

        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }

    public function obtenerListadoAsegurados()
    {
        $link_servicio = LinkServicio::where('nombre_servicio', 'POBLACION ASEGURADA - BASICO')
            ->first();

        if (! $link_servicio) {
            return null;
        }

        $response = Http::withToken(session('access_token'))
            ->get($link_servicio->link_servicio);
        if ($response->successful()) {
            return $response;
            //return $response->json()['data'] ?? null;
        }

        return null;
    }

    public function agregarAlergiaAfiliacionRepuesto($id_persona, $alergia_nueva, $id_user_updated)
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        //$link_servicio = LinkServicio::where('nombre_servicio', 'OBETENER DATOS PERSONA Y SU INSITUCION PRINCIPAL')->first();
        $link_servicio = LinkServicio::where('nombre_servicio', 'ACTUALIZAR ALERGIAS PERSONA')->first();
        $response = Http::withToken(session('access_token'))->put($link_servicio->link_servicio, [
            'id_persona' => $id_persona,
            'alergias' => $alergia_nueva,
            'id_user_updated' => $id_user_updated,
        ]);
        //dd($response);
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }

        // en caso de salir error 400 revisar a detalle la solucion
        return null;
    }

    public function agregarAlergiaAfiliacion($id_persona, $alergia_nueva, $id_user_updated){
        $persona = Persona::find($id_persona);
        $respuesta = false;

        if ($persona) {
            $persona->alergias = $alergia_nueva;
            $persona->id_user_updated = $id_user_updated;
            $persona->save();

            $respuesta = true;
        }

        return $respuesta;
    }

    // public function personaObtenerReceta($id_per){
    //     $authTokenService = new ObtenerToken();
    //     $authTokenService->refresh_token();
    //     $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER PERSONA FARMACIA')->first();
    //     $response = Http::withToken(session('access_token'))->get($link_servicio->link_servicio.$id_per);
    //     dd('dentra',$response->json());
    //     if ($response->successful()) {
    //         return $response->json()['data'] ?? null;
    //     }
    //     // en caso de salir error 400 revisar a detalle la solucion
    //     return null;
    // }
}
