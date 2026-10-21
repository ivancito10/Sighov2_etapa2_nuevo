<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
class NotificacionUsuarioController extends Controller
{
    //
    //
    public function registrarNotificacion(Request $request)
    {
        //
        $fechaActual = Carbon::now();
        $fechaFormateada = $fechaActual->format('d M Y H:i:s');
        $dato = $request->all(
            'id',
            'estado',
            'tipo_prioridades',
            'IDPersonaEmisor',
            'IDPersonaReceptor',
            'usuarioEmisor',
            'prioridades',
            'FechaHoraEnviada',
            'FechaHoraRecibida',
            'FechaHoraAbierto',
            'Mensaje',
            'Asunto',
            'idUsuario'
        );
        $dato['FechaHoraEnviada'] = $fechaFormateada;
        $dato['FechaHoraRecibida'] = $fechaFormateada;
        $notificacion = NotificacionUsuarios::create($dato);

        // $dato = response()->json($dato);
        return response()->json($notificacion, 200);
    }
    public function listarNotificacion(Request $request)
    {
        //
        $mensajes = NotificacionUsuarios::all();
        return response($mensajes, 200);
    }
    public static function obtnerFechaActual()
    {

        $fechaActual = Carbon::now();
        $fechaFormateada = $fechaActual->format('d M Y H:i:s');
        return $fechaFormateada;
    }
    public static function calcularDiferencia($fechaEnviada, $fechaRecibida)
    {
        // Convierte las cadenas de fecha en objetos Carbon
        $fechaEnviada = Carbon::parse($fechaEnviada);
        $fechaRecibida = Carbon::parse($fechaRecibida);

        // Calcula la diferencia
        $diferencia = $fechaEnviada->diffForHumans($fechaRecibida);

        // Devuelve el mensaje
        return "Hace {$diferencia} se envió el mensaje.";
    }
    public static function noLeidos()
    {
        //
        $noLeidos = NotificacionUsuarios::where('idUsuario', 39)->get();
        return $noLeidos;
    }
    public static function visto()
    {
        NotificacionUsuarios::where('estado', 0)->update(['estado' => 1]);
        $noLeidos = 0;
        return $noLeidos;
    }
}
