@extends('principal')

@section('contenido')
    @can('menu.materiales_almacen_general')
        @livewire('almacen-general.materiales.materiales-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan

@stop
