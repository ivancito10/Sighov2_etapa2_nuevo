<?php

namespace App\Livewire\Administracion\Notificaciones;

use App\Models\Administracion\Notificacion;
use App\Models\Administracion\NotificacionUsuarios;
use Livewire\Component;
use Illuminate\Support\Carbon;

class NotificacionesHistorial extends Component
{   
    protected $listeners = ['verNotificacion'];
    public $notificacion_id;
    public $notificacion_asunto;
    public $notificacion_mensaje;
    public $notificacion_emisor;
    public $notificacion_fecha_recibida;
    public $notificacion_hora_recibida;
    public $notificacion_fecha_abierto;
    public $notificacion_hora_abierto;
    public $notificacion_prioridad;

    public function verNotificacion($data)
    {   $datos= NotificacionUsuarios::where('id', $data)->first();
        //dd($datos);
        if (!$datos) {
            return; 
        }
        $updates = ['estado' => 'ABIERTO'];
        if($datos->fecha_hora_leida == null){
            $updates['fecha_hora_leida'] = now();
            $updates['fecha_hora_abierto'] = now();
        }elseif($datos->fecha_hora_abierto == null){
            $updates['fecha_hora_abierto'] = now();
        }
        $datos->update($updates);
        $this->notificacion_id = $datos->id;
        $this->notificacion_asunto= $datos->asunto;
        $this->notificacion_mensaje=$datos->mensaje;
        $this->notificacion_emisor=$datos->emisor->name;
        $this->notificacion_fecha_recibida=Carbon::parse($datos->fecha_hora_enviada)->format('d-m-Y');
        $this->notificacion_hora_recibida=Carbon::parse($datos->fecha_hora_enviada)->format('H:i:s');
        $this->notificacion_fecha_abierto=Carbon::parse($datos->fecha_hora_abierto)->format('d-m-Y');
        $this->notificacion_hora_abierto=Carbon::parse($datos->fecha_hora_abierto)->format('H:i:s');
        //dd($this->notificacion_mensaje,$this->notificacion_emisor);
        if($datos->prioridades == 1){
            $this->notificacion_prioridad='BAJA';
        }elseif($datos->prioridades == 2){
            $this->notificacion_prioridad='MODERADA';
        }else{
            $this->notificacion_prioridad='ALTA';
        }
        
        $this->dispatch('modalVerNotificacion');
        $this->dispatch('pg:eventRefresh-NotificacionesTable');
        //$this->skipRender();
    }
    public function render()
    {
        return view('livewire.Administracion.notificaciones.notificaciones-historial');
    }
}
