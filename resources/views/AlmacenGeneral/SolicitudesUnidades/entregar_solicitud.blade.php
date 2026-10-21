@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:almacen-general.solicitudes-unidades.solicitudes-unidades-entregar :id_solicitud="$id"/>
@stop