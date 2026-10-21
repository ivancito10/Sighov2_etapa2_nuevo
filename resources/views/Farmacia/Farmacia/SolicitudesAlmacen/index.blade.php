@extends('principal')

@section('contenido')

   
    @can('menu.solicitud_almacen')
        @livewire('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main')
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan

@stop
