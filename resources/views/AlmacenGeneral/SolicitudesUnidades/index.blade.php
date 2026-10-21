@extends('principal')

@section('contenido')
    @can('menu.solicitudes_almacen_general')
        @livewire('almacen-general.solicitudes-unidades.solicitudes-unidades-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan

@stop
