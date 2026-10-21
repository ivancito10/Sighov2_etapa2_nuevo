@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    

    @can('entregar.farmacia_solicitudes_pendientes_unidades')
    <livewire:farmacia.farmacia.solicitudes-unidades.solicitudes-unidades-entregar :id_solicitud="$id"/>
    @else

        <p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a esta sección.</b></em></p>
    @endcan
@stop