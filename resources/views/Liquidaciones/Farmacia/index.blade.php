@extends('principal')

@section('contenido')
    @can('menu.liquidacion-recetas')
        @livewire('liquidaciones.farmacia.farmacia-main')
    @endcan
@stop
