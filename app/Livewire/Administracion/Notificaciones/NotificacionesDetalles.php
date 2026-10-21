<?php

namespace App\Livewire\Administracion\Notificaciones;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Administracion\NotificacionUsuarios;
use Carbon\Carbon;
class NotificacionesDetalles extends Component
{
    public $notificaciones=[];
    public $numero;

    #[On('notificaiones-detalles')]
      public function ver(){
      $this->notificaciones=NotificacionUsuarios::where('estado','like','NO LEIDO')->where('id_usu_receptor',auth()->user()->id)->get();
      $this->numero = NotificacionUsuarios::where('estado','like','NO LEIDO')->where('id_usu_receptor',auth()->user()->id)->count();

      foreach ($this->notificaciones as $notificacion) {
        $notificacion->estado = 'LEIDO';
        $notificacion->fecha_hora_leida = Carbon::now();
        $notificacion->save();
    }
      $this->dispatch('ModalNotificaciones');
 
    }
    public function notificacion_abierta($id){
        NotificacionUsuarios::where('id',$id)
        ->update([
            'estado' => 'ABIERTO',
            'fecha_hora_abierto'=>Carbon::now()
        ]);
        $this->skipRender();
    }
    public function render()
    {
       

        return view('livewire.Administracion.notificaciones.notificaciones-detalles');
    }
}
