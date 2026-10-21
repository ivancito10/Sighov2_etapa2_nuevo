@extends('principal')

@section('contenido')

    @can('menu.solicitudes_pendientes_almacen_general')
        @livewire('solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
