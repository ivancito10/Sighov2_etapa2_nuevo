<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocketService
{
    public function emision_anulacion_socket($agenda)
    {
        try {
            $servicio = LinkServicio::where('nombre_servicio', 'SERVER_SOCKET_IO')->first();
            if (!$servicio) {
                throw new \Exception("Servidor socket no registrado en la Base de datos");
            }
            $url_server_socket = $servicio->link_servicio;
            $response = Http::post($url_server_socket . '/agenda-socket', [
                'message' => "Actualizacion de registro de agenda",
                'data' => $agenda
            ]);

            // Verificar si la respuesta fue exitosa
            if ($response->status() == 500) {
                throw new \Exception("Error en la comunicacion con el socket. Código de respuesta: " . $response->status());
            }
        } catch (\Exception $e) {
            // Loguear el error para diagnóstico
            Log::error("Error al intentar conectar con el socket: " . $e->getMessage());

            // Lanzar una excepción para interrumpir la creación del registro
            throw new \Exception("No se pudo completar la actualizacion del registro debido a un error con el socket.");
        }
    }
}
