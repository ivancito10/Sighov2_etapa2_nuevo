<?php

namespace App\Livewire\Administracion\Notificaciones;
use App\Models\User;
use Livewire\Component;
use App\Models\Administracion\NotificacionUsuarios;

class NotificacionesMain extends Component
{
    public $numero;
    public $nuevos=false;
    public function render()
    {
        
        return view('livewire.Administracion.notificaciones.notificaciones-main');
    }
    
    public function numero_notificaciones(){

        $this->numero = NotificacionUsuarios::where('estado','like','NO LEIDO')->where('id_usu_receptor', auth()->user()->id)->count();
        if($this->numero!=0)
        {
          $this->nuevos=true;
        }
        else{
            $this->nuevos=false;
        }
       
    }
    public function abrir_modal_notificacion()
    {
 
        $this->dispatch('notificaiones-detalles')->to(NotificacionesDetalles::class);
        
    }


}
