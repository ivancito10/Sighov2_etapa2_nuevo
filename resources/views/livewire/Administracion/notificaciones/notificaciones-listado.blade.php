@extends('principal')

@section('contenido')
<div>
    <!--<h1>CAMBIO DE CONTRASEÑA</h1> -->
    <h4 style="color:black;"><b> NOTIFICACIONES</b></h4>
    <div class="card-body">
        @livewire('administracion.notificaciones.notificaciones-table')

        <!-- Componente del Listado de Notificaciones -->
        @livewire('administracion.notificaciones.notificaciones-historial')
    </div>
    </div>
</div>
@stop
