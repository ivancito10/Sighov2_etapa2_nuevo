@extends('principal')

@section('contenido')
    @can('menu.proveedores_almacen_general')
        @livewire('AlmacenGeneral.Proveedores.proveedores-main')
    @else
        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
                    esta sección.</b></em></p>
    @endcan
@stop
