<?php

namespace App\Services;

use App\Models\Administracion\ApiReplicaLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RespuestaApisService
{

    public function guardarRespuesta(
        $datos_enviados,
        $respuesta_servidor,
        $sistema_destino,
        $modulo_destino,
        $modulo_emisor,
        $id_user_created
    ) {
        // Datos estáticos
        // $sistema_destino = 'gehma';
        // dd($respuesta_servidor->status());
        $codigo_respuesta =$respuesta_servidor->status();
        // $modulo_destino = 'vigencia asegurados convenio';
        // $modulo_emisor = 'vigencia convenio interior';

        try {
            // Preparar los datos para la inserción
            $datos = [
                'sistema_destino' => $sistema_destino,
                'datos_enviados' => json_encode($datos_enviados),  // Si quieres almacenar datos, asegúrate de definirlos o pasarlos aquí
                'codigo_respuesta' => $codigo_respuesta,
                'respuesta_servidor' => json_encode($respuesta_servidor->json()), // Convertir el array en JSON
                'modulo_destino' => $modulo_destino,
                'modulo_emisor' => $modulo_emisor,
                'id_user_created'=>$id_user_created,
                'created_at' => Carbon::now()->format('d-m-Y H:i:s'), // Formato correcto de la fecha
                'updated_at' => Carbon::now()->format('d-m-Y H:i:s'), // Asegúrate de incluir 'updated_at' también
            ];

            // Crear el registro en la base de datos
            ApiReplicaLogs::create($datos);
        } catch (\Throwable $th) {
            Log::error('Error al guardar la respuesta ' . $th->getMessage());
            dd($th->getMessage()); // Si hay un error, mostrar el mensaje
        }
    }
}
