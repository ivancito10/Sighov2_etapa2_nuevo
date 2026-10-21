@extends('principal')

@section('contenido')
    <!--  aqui mi errror 09/01/2025 -->
    

    @can('editar.solicitud_almacen')
        <livewire:farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-edit :idSolicitud="$id" />
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop
