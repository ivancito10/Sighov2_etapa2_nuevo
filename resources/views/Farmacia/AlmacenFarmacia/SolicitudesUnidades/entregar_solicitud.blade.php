@extends('principal')

@section('contenido')
    <!-- Contenido específico de la vista Index -->
    
    <livewire:farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-entregar :id_solicitud="$id"/>
@stop