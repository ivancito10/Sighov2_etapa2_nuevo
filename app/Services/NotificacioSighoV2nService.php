<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use Illuminate\Support\Carbon;

class NotificacioSighoV2nService
{
    // Add your service logic here
    public function enviar_notificacion(
        $name_rol,
        $modulo,
        $asunto,
        $mensaje,
    ) {
        $modulo = strtoupper($modulo);
        DB::commit();
        $rol = Role::where('name', $name_rol)->first();
        if ($rol) {
            $receptores = RolUsuario::where('role_id', $rol->id)->get();
            foreach ($receptores as $receptor) {
                NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' => $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => $modulo . ' - ' . $asunto,
                    'mensaje' => $mensaje,
                ]);
            }
        }
    }
}
