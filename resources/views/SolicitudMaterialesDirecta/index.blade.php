@extends('principal')

@section('contenido')
    @can('menu.solicitudes_materiales_almacen_general')
        @livewire('solicitud-materiales-directa.solicitud-materiales-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
