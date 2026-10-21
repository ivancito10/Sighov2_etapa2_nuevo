@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-aprobar :id_solicitud="$id"/>
@stop