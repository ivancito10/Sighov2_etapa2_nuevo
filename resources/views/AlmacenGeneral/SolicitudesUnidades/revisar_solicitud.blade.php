@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:almacen-general.solicitudes-unidades.solicitudes-unidades-revisar :id_solicitud="$id"/>
@stop