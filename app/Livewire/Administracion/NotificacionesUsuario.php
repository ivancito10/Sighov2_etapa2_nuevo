<?php

namespace App\Livewire\Administracion;

use Livewire\Component;
use App\Models\Administracion\NotificacionUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\User;
use App\Http\Controllers\Administracion\NotificacionUsuarioController;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// php artisan make:controller Administracion/NotificacionUsuarioController
class NotificacionesUsuario extends Component
{
    public $recibidos;
    public $iduser;
    public $mensajes;
    public $tiempo;

    public $numero;
    public $mostrarMenu = false;


    public function obtenerNotificaciones()
    {
        //
        $this->iduser = Auth::id();
        $idUsuarioLogin = $this->iduser;
        $this->recibidos = NotificacionUsuario::where('estado', 0)->where('idUsuario', $idUsuarioLogin)->count();
        $this->mensajes = NotificacionUsuario::where('idUsuario', $idUsuarioLogin)->orderby('FechaHoraEnviada', 'desc')->get();
    }
    public function verNotificaciones()
    {
        // dd($this->recibidos);

        $this->mostrarMenu = !$this->mostrarMenu;
        NotificacionUsuario::where('idUsuario', $this->iduser)
            ->update([
                'estado' => 1,
                'FechaHoraRecibida' => NotificacionUsuarioController::obtnerFechaActual()
            ]);
    }
    public function calcularDiferencia($fechaEnviada, $fechaRecibida)
    {
        // Convierte las cadenas de fecha en objetos Carbon
        $fechaEnviada = Carbon::parse($fechaEnviada);
        $fechaRecibida = Carbon::parse($fechaRecibida);

        // Calcula la diferencia
        $diferencia = $fechaEnviada->diffForHumans($fechaRecibida);

        // Devuelve el mensaje
        return "Hace {$diferencia} se envió el mensaje.";
    }
    public function mensajeLeido($idMensaje)
    {
        //
        NotificacionUsuario::where("id", $idMensaje)->update([
            'estado' => 2,
            'FechaHoraAbierto' => NotificacionUsuarioController::obtnerFechaActual()
        ]);
    }

    public function render()
    {
        $this->obtenerNotificaciones();
        return view('livewire.Administracion.notificaciones-usuario', [
            'recibidos' => $this->recibidos,
            'iduser' => $this->iduser,
            'mensajes' => $this->mensajes,
            'fecha' => NotificacionUsuarioController::obtnerFechaActual()
        ]);
    }

    public function numero_notificaciones(){

        $this->numero = User::where('id','>',1)->count();
        //dd($this->numero);
       
    }
    public function abrir_modal_notificacion()
    {
        $this->dispatch('ModalNotificaciones');
        $this->mensajes="aasd";
        $this->skipRender();   
    }
    
}
