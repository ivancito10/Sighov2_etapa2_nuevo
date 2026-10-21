@extends('principal')

@section('contenido')

    
     @can('registrar.solicitud_almacen')
     <livewire:farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-create />
     @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
