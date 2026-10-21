@extends('principal')

@section('contenido')
   
  
     @can('menu.farmacia_solicitudes_pendientes_unidades')
      @livewire('farmacia.farmacia.solicitudes-unidades.solicitudes-unidades-main')
     @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
