<div>
    <x-notificacion-button wire:poll.keep-alive="numero_notificaciones" :nro="$this->numero" :nueva="$this->nuevos" 
     wire:click="abrir_modal_notificacion" style=" width: 35px;height: auto; padding:2px"/>
</div>