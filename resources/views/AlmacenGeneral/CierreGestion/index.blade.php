@extends('principal')

@section('contenido')

    @can('menu.cierre_gestion_almacen_general')
        @livewire('almacen-general.cierre-gestion.cierre-gestion-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
