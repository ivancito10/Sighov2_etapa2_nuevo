@extends('principal')

@section('contenido')

    @can('menu.ingresos_almacen_general')
        @livewire('almacen-general.ingresos.ingresos-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan

@stop
